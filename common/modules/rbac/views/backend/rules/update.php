<?php

/* @var $this yii\web\View */
/* @var $model \modules\rbac\models\RolesForm */

$this->title = 'Роли пользователей';
$this->params['pageTitle'] = $this->title;
$this->params['pageIcon'] = 'male';
$this->params['place'] = 'rules';
$this->params['breadcrumbs'][] = ['label' => 'Роли пользователей', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Изменить';
?>

<div class="rules-update">
    <?= $this->render('_form', [
        'model' => $model
    ]) ?>
</div>
