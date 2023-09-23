<?php

namespace app\modules\gpio\controllers;

use app\models\Commands;
use app\models\Device;
use app\modules\gpio\models\Gpio;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\httpclient\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class GpioController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Gpio::find()
                ->leftJoin(Device::tableName(), Device::tableName().".id = ".Gpio::tableName().".deviceId")
                ->andWhere([Device::tableName().".active" => Device::STATUS_ACTIVE])
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionCreate(): string
    {
        $model = new Gpio();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Commands::deleteAll(['deviceId' => $model->deviceId, 'pin' => $model->pin]);
            $commands = Yii::$app->request->post('Commands');

            if($commands && is_array($commands) && array_key_exists('conditionType', $commands) &&
                is_array($commands['conditionType'])) {
                $i = 0;
                foreach ($commands['conditionType'] as $key => $conditionType) {
                    $command = new Commands();
                    $command->deviceId = $model->deviceId;
                    $command->pin = $model->pin;
                    $command->pinType = Commands::PIN_TYPE_GPIO;
                    $command->conditionType = $conditionType;

                    if(array_key_exists('conditionFrom', $commands) && is_array($commands['conditionFrom']) &&
                        array_key_exists($key, $commands['conditionFrom'])) {
                        $command->conditionFrom = $commands['conditionFrom'][$key];
                    }

                    if(array_key_exists('conditionTo', $commands) && is_array($commands['conditionTo']) &&
                        array_key_exists($key, $commands['conditionTo'])) {
                        $command->conditionTo = $commands['conditionTo'][$key];
                    }

                    if(array_key_exists('pinValue', $commands) && is_array($commands['pinValue']) &&
                        array_key_exists($key, $commands['pinValue'])) {
                        $command->pinValue = $commands['pinValue'][$key];
                    }

                    if(array_key_exists('active', $commands) && is_array($commands['active']) &&
                        array_key_exists($key, $commands['active'])) {
                        $command->active = $commands['active'][$key];
                    }
                    $command->conditionSort = $i;
                    $command->save();
                    $i++;
                }


            }
            return $this->redirect(['index', 'id' => $model->id]);
        }

        $commands = Commands::find()
            ->where(['deviceId' => $model->deviceId, 'pin' => $model->pin])
            ->orderBy(['conditionSort' => SORT_ASC])->all();

        return $this->render('update', [
            'model' => $model,
            'commands' => Json::encode($commands)
        ]);
    }

    /**
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return Gpio|null
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Gpio
    {
        if (($model = Gpio::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
