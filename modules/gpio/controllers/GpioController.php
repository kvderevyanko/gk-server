<?php

namespace app\modules\gpio\controllers;

use app\components\CommandsSettings\CommandsGpioSettings;
use app\models\Commands;
use app\models\Device;
use app\modules\gpio\models\Gpio;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
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
     * @param int $deviceId
     * @return string
     */
    public function actionIndex(int $deviceId): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Gpio::find()
                ->leftJoin(Device::tableName(), Device::tableName() . ".id = " . Gpio::tableName() . ".deviceId")
                ->andWhere([
                    Device::tableName() . ".active" => Device::STATUS_ACTIVE,
                    Device::tableName() . ".id" => $deviceId,
                ])
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'deviceId' => $deviceId,
        ]);
    }

    /**
     * @param int $deviceId
     * @return Response|string
     */
    public function actionCreate(int $deviceId)
    {
        $model = new Gpio();
        if ($model->load(Yii::$app->request->post())) {
            $model->deviceId = $deviceId;
            if ($model->save()) {
                $this->saveCommands($model);
                return $this->redirect(['index', 'deviceId' => $model->deviceId]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'deviceId' => $deviceId
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
            $this->saveCommands($model);
            return $this->redirect(['index', 'deviceId' => $model->deviceId]);
        }
        $commands = CommandsGpioSettings::get($model->deviceId, $model->pin);
        return $this->render('update', [
            'model' => $model,
            'commands' => Json::encode($commands),
            'deviceId' => $model->deviceId
        ]);
    }

    /**
     * Сохранение команд GPIO
     * @param Gpio $model
     * @return void
     */
    private function saveCommands(Gpio $model): void
    {
        $commands = Yii::$app->request->post('Commands');
        if (!is_array($commands))
            $commands = [];
        CommandsGpioSettings::set($model->deviceId, $model->pin, $commands);
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
