<?php

namespace app\modules\ssd1306\controllers;


use app\modules\ssd1306\models\Ssd1306Images;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * Команды для дисплея Ssd1306
 * Class DefaultController
 * @package app\controllers
 */
class DefaultController extends Controller
{

    public function actionIndex(){

        return $this->render('index');
    }

    /**
     * Рисование по пикселям
     * @return string
     */
    public function actionDraw(){

        return $this->render('draw');
    }

    /**
     * Сохранение пиксельного изображения
     * @return bool|int
     */
    public function actionSaveImage (){
        $params = \Yii::$app->request->post('params');
        if($params && is_array($params)) {
            $ssd1306Images = new Ssd1306Images();
            $ssd1306Images->attributes = $params;
            $ssd1306Images->code = $params['code'];
            return $ssd1306Images->save();
        }
        return 0;
    }

    /**
     * Получение списка изображений
     * @return Ssd1306Images[]|array|\yii\db\ActiveRecord[]
     */
    public function actionGetImagesList(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return Ssd1306Images::find()->orderBy(['id' => SORT_DESC])->all();
    }

    public function actionCode($id){
        $ssd1306Image = Ssd1306Images::findOne($id);
        if($ssd1306Image === null)
            throw new NotFoundHttpException('Не найдена картинка');

        $result = [];

        try {
            $code = Json::decode($ssd1306Image->code);
        } catch (\Exception $e) {
            $code = [];
        }

        foreach ($code as $row) {
            //Сколько элементов по 8 содержится в этом массиве
            $total = round(count($row)/8);

            //Получаем срезы по 8 элементов и заносим их в массив в виде строки
            for ($i = 0; $i<$total; $i++) {
                $tmp = array_slice($row, ($i*8), 8);
                $result[] = 'B'.implode('', $tmp);
            }

        }
        print_r(implode(", ", $result));
        print_r("\n");
        print_r("\n");
        print_r($code);

        return '';

    }

}
