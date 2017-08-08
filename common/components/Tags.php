<?php

namespace common\components;

use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Class Tags
 * @package common\components
 */
class Tags {
    /**
     * @param $string
     * @param bool $encode
     * @return mixed|string
     */
    public static function check($string, $encode = true)
    {
        $redirect = Url::to(['/main/default/redirect']);

        if ($encode){
            $string = Html::encode($string);
        }

        $string = self::rand($string, false);

        $tags = [
            '#\[b](.*?)\[/b\]#ui'                                  => '<b>$1</b>',
            '#\\[url=(https?://.+?)\\](.+?)\\[/url\\]#'            => '<a href="' . $redirect . '?url=$1" target="_blank">$2</a>'
        ];

        return preg_replace(array_keys($tags), $tags, $string);
    }

    /**
     * Тег случайной строки
     * @param $string
     * @param bool $encode
     * @return mixed
     */
    public static function rand($string, $encode = true)
    {
        $string = str_replace('{rand}', mt_rand(1111, 9999), $string);
        $string = preg_replace_callback('#\[rand\](.*?)\[/rand\]#', function ($matches){
            $array = explode('|', $matches[1]);
            $key   = array_rand($array);

            return $array[$key];
        }, $string);

        return $encode ? Html::encode($string) : $string;
    }
}