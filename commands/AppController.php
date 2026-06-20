<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;


class AppController extends Controller
{
    /**
     * Создание базы и простановка прав
     */
    public function actionStart()
    {
        chmod(\Yii::getAlias('@app')."/runtime", 0775);
        chmod(\Yii::getAlias('@app')."/web/assets", 0775);
        chmod(\Yii::getAlias('@app')."/db", 0775);

        $fileDb= \Yii::getAlias('@app')."/db/sqlite.db";
        if(!file_exists($fileDb)) {
            touch($fileDb);
            chmod($fileDb, 0664);
            $this->stdout(
                "Назначьте каталогу db и SQLite-файлу владельца/группу веб-процесса.\n"
            );
        }

        $this->stdout(
            "Добавляйте cron только для используемых функций; актуальные команды "
            ."описаны в docs/project/operations.md.\n"
        );
    }
}
