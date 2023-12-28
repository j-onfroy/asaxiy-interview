<?php

namespace app\models;

use yii\db\ActiveRecord;

class Interview extends ActiveRecord
{
    public static function tableName()
    {
        return 'interview';
    }

    public function rules(): array
    {
        return [
            [['interview_date'], 'filter', 'filter' => function ($value) {
                $parsedDate = date_create_from_format('Y-m-d', $value);
                return $parsedDate ? $parsedDate->format('Y-m-d') : null;
            }],
            [['interview_time'], 'filter', 'filter' => function ($value) {
                $parsedTime = date_create_from_format('H:i', $value);
                return $parsedTime ? $parsedTime->format('H:i:00') : null;
            }],
            [['note_message'],'string','min'=>5]

        ];
    }

    public function getCandidate()
    {
        return $this->hasOne(Candidate::class, ['id' => 'user_id']);
    }

    public function getVacancy()
    {
        return $this->hasOne(Vacancy::class, ['id' => 'job_id']);
    }

}