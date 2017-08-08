<?php

namespace common\components;

/**
 * Class Formatter
 * @package common\components
 */
class Formatter extends \yii\i18n\Formatter {
    /**
     * @param $date string
     * @param $format string
     * @return int|false
     */
    public function asDateToTimestamp($date, $format)
    {
        $time = \DateTime::createFromFormat($format, $date);

        if ($time){
            $time = $time->setTime(0, 0, 0);
            return $time->getTimestamp();
        }else{
            return false;
        }
    }

    /**
     * @param $dateTime
     * @param $format
     * @return bool|int
     */
    public function asDateTimeToTimestamp($dateTime, $format)
    {
        $time = \DateTime::createFromFormat($format, $dateTime);

        return $time ? $time->getTimestamp() : false;
    }
}