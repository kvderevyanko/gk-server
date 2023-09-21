<?php

namespace app\controllers;

use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\helpers\Url;
use yii\web\Controller;


class HelpController extends Controller
{


    /**
     * Вывод справки
     * @param string $docName
     * @param string $folder
     * @return string
     */
    public function actionIndex(string $docName = "", string $folder = ""): string
    {
        $fileFolder = "";
        if($docName) {
            $fileFolder = explode('/', $docName);
            $fileFolder = array_slice($fileFolder, 0, -1);
            $fileFolder = implode('/', $fileFolder);
        }
        $fileFolder = $folder.$fileFolder.'/';

        $html = $this->getDoc($docName, $folder, $fileFolder);
        return $this->render('index', ['html' => $html]);
    }

    /**
     * Приходят параметры документа. Находим документ, приводим маркдаун к html, заменяем ссылки, обрабатываем изображения
     * @param string $docName
     * @param string $folder
     * @param string $fileFolder
     * @return array|false|string|string[]
     * @throws ErrorException
     * @throws Exception
     */
    private function getDoc(string $docName, string $folder, string $fileFolder)
    {
        if (!$docName)
            $docName = 'README.md';

        if (!$folder)
            $folder = '';

        $docsFolder = Yii::getAlias('@app/docs/') . $folder;

        $file = $docsFolder . $docName;
        if (file_exists($file)) {
            $html = file_get_contents($file);
            $html = Markdown::process($html, 'extra');

            //Заменяем ссылки
            preg_match_all('/href=["\']?([^"\'>]+)["\']?/', $html, $matches);
            if (count($matches) && array_key_exists(1, $matches)) {
                foreach ($matches[1] as $originalLink) {
                    $link = $this->mdLinkReplace($originalLink, $folder, $fileFolder);
                    $html = str_replace('href="' . $originalLink . '"', 'href="' . $link . '"', $html);
                }
            }

            //Заменяем изображения
            preg_match_all('/src=["\']?([^"\'>]+)["\']?/', $html, $matches);
            if (count($matches) && array_key_exists(1, $matches)) {
                foreach ($matches[1] as $originalLink) {
                    $link = $this->mdFileReplace($originalLink, $folder, $fileFolder);
                    $html = str_replace('src="' . $originalLink . '"', 'src="' . $link . '"', $html);
                }
            }
            return $html;

        } else {
            return '<h1>404</h1> <h4>'.Html::a('На главную справки', ['index']).'</h4>';
        }
    }

    /**
     * Замена ссылки в файле с маркдауном
     * @param string $link
     * @param string $folder
     * @param string $fileFolder
     * @return string
     */
    private function mdLinkReplace(string $link, string $folder, string $fileFolder): string
    {
        //Проверяем, начинается ли урл с http - если да, то это скорее всего ссылка, возвращаем, как есть
        $checkHttp = stripos(trim($link), "http://");
        $checkHttps = stripos(trim($link), "https://");
        if ($checkHttp === 0 || $checkHttps === 0)
            return $link;

        return Url::to(['index', 'docName' => $link, 'folder' => $fileFolder]);
    }

    /**
     * Принимает имя файла из ссылки и делает нужные манипуляции
     * @param string $link
     * @param string $folder
     * @param string $fileFolder
     * @return string
     * @throws ErrorException
     * @throws Exception
     */
    private function mdFileReplace(string $link, string $folder, string $fileFolder): string
    {
        //Делаем проверку на картинку, если есть - изображение копируем в web/assets, и возвращаем урл на него
        $checkImage = stripos(trim($link), "image");
        if ($checkImage === 0) {
            //Убираем соль из имени файла, типа ?raw=true
            $link = parse_url($link);
            $link = $link['path'];
            $linkDir = "/assets/help" . $fileFolder;
            $dirCopy = Yii::getAlias('@webroot') . $linkDir;
            $linkUrl = Yii::getAlias('@web') . $linkDir . $link;


            if (!file_exists($dirCopy . $link)) {
                $originalFile = Yii::getAlias('@app/docs') . $fileFolder . $link;

                if (file_exists($originalFile)) {
                    /*Если файл имеет дирректорию типа images/date-course-group.png, получаем дирректорию,
                     * создаём дирректорию типа $dirCopy.images/date-course-group.png и  удаляем её через хелпер
                     * В итоге у нас удалиться папка date-course-group.png, а остальное останется
                     */
                    FileHelper::createDirectory($dirCopy . $link);
                    FileHelper::removeDirectory($dirCopy . $link);
                    @copy($originalFile, $dirCopy.$link);
                }


            }

            return $linkUrl;
        }
        return $link;
    }

}