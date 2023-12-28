<?php

namespace app\models;

use yii\db\ActiveRecord;


class Candidate extends ActiveRecord
{

    public static function tableName()
    {
        return 'candidate';
    }

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email', 'address', 'country_of_origin', 'phone_number', 'birthday'], 'required'],
            [['resume_url'], 'file', 'extensions' => 'pdf', 'mimeTypes' => 'application/pdf'],
            [['first_name', 'last_name'], 'string', 'min' => 5],
            [['address'], 'string', 'min' => 10],
            ['email', 'email'],
            ['email', 'unique', 'message' => 'This email already exists'],
            [['phone_number'], 'string', 'length' => 13],
            ['phone_number', 'unique', 'message' => 'This phone number already exists'],
        ];
    }

    public function getInterview()
    {
        return $this->hasOne(Interview::class,['user_id'=>'id']);
    }
}