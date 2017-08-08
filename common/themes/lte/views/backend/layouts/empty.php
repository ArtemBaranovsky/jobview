<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use themes\lte\assets\EmptyAsset;


/**
Пустой лейаут без шапки и всего остального
@var $this yii\web\View
@var $content string
 */

EmptyAsset::register($this);
$assetPath = Yii::$app->assetManager->getPublishedUrl(Yii::$app->params['assetPath']);
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <meta name="description" content="<<<SET DATA>>>">
        <meta name="keywords" content="<<<SET DATA>>>">

        <?php $this->head() ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page is-site-bg-img2">

    <?= $content ?>



    <?php
    if (Yii::$app->session->hasFlash('success')){
        Modal::begin([
            'id'            => 'message-success',
            'clientOptions' => ['show' => true],
            'header'        => '<h4 class="modal-title">' . Yii::t('app', 'TITLE_MODAL_SUCCESS') . '</h4>',
        ]);
        echo '<p>' . Yii::$app->session->getFlash('success') . '</p>';
        Modal::end();
    }elseif (Yii::$app->session->hasFlash('error')){
        Modal::begin([
            'id'            => 'message-error',
            'clientOptions' => ['show' => true],
            'headerOptions' => ['class' => 'bg-red'],
            'header'        => '<h4 class="modal-title">' . Yii::t('app', 'TITLE_MODAL_ERROR') . '</h4>',
        ]);
        echo '<p>' . Yii::$app->session->getFlash('error') . '</p>';
        Modal::end();
    }elseif (Yii::$app->session->hasFlash('warning')){
        Modal::begin([
            'id'            => 'message-warning',
            'clientOptions' => ['show' => true],
            'headerOptions' => ['class' => 'bg-yellow'],
            'header'        => '<h4 class="modal-title">' . Yii::t('app', 'TITLE_MODAL_WARNING') . '</h4>',
        ]);
        echo '<p>' . Yii::$app->session->getFlash('warning') . '</p>';
        Modal::end();
    }
    ?>

    <?= $this->renderFile('@common/views/analytics_counters.php') ?>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>