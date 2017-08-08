<?php

/* @var $this yii\web\View */
/* @var $unsubscribeUrl string */

$domain  = Yii::$app->params['domain'];
$rateUrl = $domain . 'payment/rate';
?>

Внимание!
Чтобы продолжить продвижение в Инстаграм вам необходимо подключить тариф на сайте hamster.pro

Чтобы подключить тариф, перейдите по ссылке: <?= $rateUrl ?>


----------------------------
Вы получили это письмо так как зарегистрированы на сайте hamster.pro
Чтобы отписаться от рассылки перейдите по ссылке: <?= $unsubscribeUrl ?>