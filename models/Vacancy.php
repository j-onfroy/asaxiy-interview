<?php

namespace app\models;

use yii\db\ActiveRecord;

class Vacancy extends ActiveRecord
{
    public static function tableName()
    {
        return 'vacancy';
    }

    public function rules()
    {
        return [
            [['job_title', 'skills', 'job_about'], 'required'],
            [['job_title', 'skills', 'job_title'], 'string', 'min' => 5]
        ];
    }

    public function getInterview()
    {
        return $this->hasOne(Interview::class, ['job_id' => 'id']);
    }
}