<?php

use yii\helpers\Url;
use yii\helpers\Html;
use modules\main\Module;
use modules\users\models\frontend\Users;
use modules\account\models\frontend\Account;

/* @var $this \yii\web\View */
/* @var $user Users */

$this->title = Module::t('main', 'TITLE_MAIN') . ' ' . Yii::$app->name;
?>

Frontend