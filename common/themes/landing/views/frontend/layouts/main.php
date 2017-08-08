<?php

use yii\helpers\Url;
use yii\helpers\Html;
use themes\landing\assets\MainAsset;

/* @var $this yii\web\View */
/* @var $content string */

MainAsset::register($this);
?>
    <!DOCTYPE html>
    <html>
    <head>
        <title><?= Yii::$app->name ?></title>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="<<<SET DATA>>>">
        <meta name="keywords"
              content="<<<SET DATA>>>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <?= Html::csrfMetaTags() ?>
        <?php $this->head() ?>
    </head>
    <body>

    <h1>Привет)</h1>
    <p>
        &raquo; <a href="<?= Url::to(['/users/default/login']) ?>">Авторизация</a>
    </p>
    <p>
        &raquo; <a href="<?= Url::to(['/users/default/registration']) ?>">Регистрация</a>
    </p>

    <?= $this->renderFile('@common/views/analytics_counters.php') ?>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>