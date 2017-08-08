<?php

/* @var $this yii\web\View */
/* @var $model \modules\rbac\models\RolesForm */

$this->title = 'Роли пользователей';
$this->params['pageTitle'] = $this->title;
$this->params['pageIcon'] = 'puzzle-piece';
$this->params['place'] = 'roles';
$this->params['breadcrumbs'][] = ['label' => 'Роли пользователей', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Добавить';
?>

<div class="roles-create">
    <?= $this->render('_form', [
        'model' => $model
    ]) ?>
</div>