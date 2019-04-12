<?php

namespace app\components;

class ActiveRecord extends \yii\db\ActiveRecord
{

    public static function getDb ()
    {
        return \Yii::$app->dbTenant;
    }

}