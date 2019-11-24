<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "employment".
 *
 * @property int $id
 * @property string $title
 */
class Employment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Employment type',
        ];
    }

    public function getJob()
    {
        return $this->hasOne(Job::className(), ['employment_id' => 'id']);
    }
}
