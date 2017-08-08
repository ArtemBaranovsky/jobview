<?php

use yii\helpers\Url;
use yii\helpers\Html;
use modules\users\Module;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
$assetPath = Yii::$app->assetManager->getPublishedUrl(Yii::$app->params['assetPath']);
?>

<?php if (Yii::$app->user->isGuest): ?>
    <div class="login-box">
        <div class="login-logo">
            <a href="/">
                <img src="<?= $assetPath ?>/dist/img/logo.png" alt="<?= Yii::$app->name ?>">
            </a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">

            <div class="error-box-title text-red">
                <?= Html::encode($this->title) ?>
            </div>

            <div class="error-box-message">
                <?= nl2br(Html::encode($message)) ?>
            </div>

        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
<?php else: ?>
    <div class="site-error">

        <h1><?= Html::encode($this->title) ?></h1>

        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>
    </div>
<?php endif; ?>