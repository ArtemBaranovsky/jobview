<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "job".
 *
 * @property int $id
 * @property string $title Название вакансии
 * @property string $salary ЗП
 * @property int $employment_id Тип занятости
 */
class Job extends \yii\db\ActiveRecord
{

//    public $employmentTitle;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jobs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'salary', 'employment_id'], 'required'],
            [['employment_id'], 'integer'],
            [['title', 'salary'], 'string', 'max' => 255],
//            [['employmentTitle'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название вакансии',
            'salary' => 'ЗП',
            'employment_id' => 'Тип занятости',
        ];
    }

    public function getEmploymentList(){
        return ArrayHelper::map(Employment::find()->all(), 'id', 'title');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployment()
    {
        return $this->hasOne(Employment::className(), ['id' => 'employment_id']);
    }

//    public function attributes()
//    {
//        // делаем поле зависимости доступным для поиска
//        return array_merge(parent::attributes(), ['employment.title']);
//    }
}
