<?php

namespace app\models;

use yii\db\ActiveRecord;

class Interview extends ActiveRecord{
    public static function tableName()
    {
        return 'interview';
    }

}