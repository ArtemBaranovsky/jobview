<?php

namespace modules\users\models;

use yii;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%users_log}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $message
 * @property string $params
 * @property string $data_old
 * @property string $data_new
 * @property string $url
 * @property string $ua
 * @property string $ip
 * @property integer $created_at
 *
 * @property BaseUsers $user
 */
class BaseLog extends yii\db\ActiveRecord
{
    /**
     * Действие, авторизация
     */
    const TYPE_AUTH = 'auth';
    /**
     * Действие, добавление данных
     */
    const TYPE_INSERT = 'create';
    /**
     * Действие, изменение данных
     */
    const TYPE_UPDATE = 'update';
    /**
     * Действие, удаление данных
     */
    const TYPE_DELETE = 'delete';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_log}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'user_id'    => 'Пользователь',
            'type'       => 'Тип',
            'message'    => 'Действие',
            'params'     => 'Параметры',
            'data_old'   => 'Старые данные',
            'data_new'   => 'Новые данные',
            'url'        => 'Ссылка',
            'ua'         => 'User Agent',
            'ip'         => 'IP Адрес',
            'created_at' => 'Время',
        ];
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(BaseUsers::className(), ['id' => 'user_id']);
    }

    /**
     * @return array
     */
    public function valuesType()
    {
        return [
            self::TYPE_AUTH    => 'Авторизация',
            self::TYPE_UPDATE  => 'Изменение',
            self::TYPE_INSERT  => 'Добавление',
            self::TYPE_DELETE  => 'Удаление',
        ];
    }

    /**
     * Создание записи в журнале действий для конкретного юзера
     * @param $type string          Передается константа из valuesType()
     * @param $message string       Передается текст действия (описание)
     * @param array $params array   Параметры языкового перевода {name}. Например: HELLO_USER_{name}
     * @param array $data_old       Массив старых даных
     * @param array $data_new       Массив новых данных
     * @param array $only           Ограничение отслеживаемых полей
     * @param int $user_id integer  Кому принадлежит действие
     */
    public static function create($type, $message, array $params = [], array $data_old = [], array $data_new = [], $only = [], $user_id = 0)
    {
        $model = new BaseLog();
        $data  = $model->findUpdatedAttributes($type, $data_old, $data_new, $only);

        if (
            (
                $type == self::TYPE_UPDATE &&
                (count($data['old']) || count($data['new']))
            ) ||
            $type != self::TYPE_UPDATE
        ){
            $model->user_id     = $user_id !== 0 ? $user_id : Yii::$app->user->id;
            $model->type        = $type;
            $model->message     = $message;
            $model->params      = json_encode($params);
            $model->data_old    = json_encode($data['old']);
            $model->data_new    = json_encode($data['new']);
            $model->url         = Url::to();
            $model->ua          = Yii::$app->request->userAgent;
            $model->ip          = Yii::$app->request->userIP;
            $model->created_at  = time();

            $model->save();
        }
    }

    /**
     * @return array
     */
    public function valuesData()
    {
        $values  = [];
        $old     = json_decode($this->data_old, true);
        $new     = json_decode($this->data_new, true);

        if (is_array($old) === false || is_array($new) === false){
            return $values;
        }

        $arr     = array_merge(array_keys($new), array_keys($old));
        $arr     = array_unique($arr);

        foreach ($arr as $field){
            $value_old = isset($old[$field]) ? $old[$field] : null;
            $value_new = isset($new[$field]) ? $new[$field] : null;

            if (is_array($value_old) || is_array($value_new)){
                $values[] = [
                    'key' => $field,
                    'old' => is_array($value_old) ? $this->arrayToString($value_old) : $value_old,
                    'new' => is_array($value_new) ? $this->arrayToString($value_new) : $value_new,
                ];
            }else{
                $json_old = json_decode($value_old, true);
                $json_new = json_decode($value_new, true);

                if (is_array($json_old) || is_array($json_new)){
                    $values = array_merge($values, $this->findDataArray($field, $json_old, $json_new));
                }else{
                    $values[] = [
                        'key' => $field,
                        'old' => Html::encode($value_old),
                        'new' => Html::encode($value_new),
                    ];
                }
            }
        }

        return $values;
    }

    /**
     * @param $field
     * @param $old
     * @param $new
     * @return array
     */
    protected function findDataArray($field, $old, $new)
    {
        $values      = [];
        $new_keys    = is_array($new) ? array_keys($new) : [];
        $old_keys    = is_array($old) ? array_keys($old) : [];
        $arr         = array_merge($new_keys, $old_keys);
        $arr         = array_unique($arr);

        foreach ($arr as $key => $value){
            $valueOld = isset($old[$value]) ? $old[$value] : '';
            $valueNew = isset($new[$value]) ? $new[$value] : '';

            if (is_string($valueOld) && is_string($valueNew) && $valueOld != $valueNew){
                $values[] = [
                    'key' => $field . "[$value]",
                    'old' => Html::encode($valueOld),
                    'new' => Html::encode($valueNew),
                ];
            }
        }

        return $values;
    }

    /**
     * @param $type
     * @param $data_old
     * @param $data_new
     * @param $only
     * @return array
     */
    protected function findUpdatedAttributes($type, $data_old, $data_new, $only)
    {
        $array = array_merge(array_keys($data_old), array_keys($data_new));
        $only  = is_array($only) && count($only) ? $only : false;
        $data  = [
            'new' => [],
            'old' => []
        ];

        foreach ($array as $key){
            $old = isset($data_old[$key]) ? $data_old[$key] : null;
            $new = isset($data_new[$key]) ? $data_new[$key] : null;

            if (
                $only && in_array($key, $only) === false ||
                $this->isEqual($old, $new) && $type == self::TYPE_UPDATE
            ){
                continue;
            }elseif ($key == 'password'){
                $new = '******';
                $old = '******';
            }

            $data['new'][$key] = $new;
            if ($type != self::TYPE_INSERT){
                $data['old'][$key] = $old;
            }
        }

        return $data;
    }

    /**
     * @param $array
     * @return string
     */
    protected function arrayToString($array)
    {
        $values = [];

        foreach ($array as $value){
            if (is_array($value)){
                $values[] = $this->arrayToString($value);
            }else{
                $values[] = Html::encode($value);
            }
        }

        return Html::ul($values, ['encode' => false]);
    }

    /**
     * Сравниваем две переменные
     * @param $a
     * @param $b
     * @return bool
     */
    protected function isEqual($a, $b)
    {
        // Is object
        $a = is_object($a) ? (array) $a : $a;
        $b = is_object($b) ? (array) $b : $b;

        // Is array
        $a = is_array($a) ? $this->arrayToString($a) : $a;
        $b = is_array($b) ? $this->arrayToString($b) : $b;

        // Etc types
        $a = (string) $a;
        $b = (string) $b;

        return $a == $b;
    }
}
