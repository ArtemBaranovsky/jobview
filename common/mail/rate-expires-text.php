<?php

/* @var $this yii\web\View */
/* @var $expires_days integer */
/* @var $unsubscribeUrl string */

$domain  = Yii::$app->params['domain'];
$rateUrl = $domain . 'payment/rate';
?>

Здравствуйте!
Напоминаем, что <?= \MessageFormatter::formatMessage(
    'ru_RU', '{count, plural, =0{сегодня} =1{завтра} one{через # день} few{через # дня} many{через # дней} other{через # дней}}', [
        'count' => $expires_days
    ]
) ?> истекает срок действия вашего тарифа на сайте hamster.pro!
После чего продвижение ваших Инстаграм аккаунтов будет приостановлено.

Чтобы продлить тариф, перейдите по ссылке: <?= $rateUrl ?>


----------------------------
Вы получили это письмо так как зарегистрированы на сайте hamster.pro
Чтобы отписаться от рассылки перейдите по ссылке: <?= $unsubscribeUrl ?>