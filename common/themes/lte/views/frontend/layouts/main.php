<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\Breadcrumbs;
use themes\lte\assets\MainAsset;

/* @var $this yii\web\View */
/* @var $content string */

MainAsset::register($this);

if (isset($this->params['place'])){
    $this->registerJs('
        var place = $("#place-' . $this->params['place'] . '");
        if (place.parent().parent().prop("tagName") == "LI"){
            place.parent().parent().addClass("open");
            place.parent().show();
        }
        place.addClass("active");
    ');
}

$assetPath       = Yii::$app->assetManager->getPublishedUrl(Yii::$app->params['assetPath']);
$push_menu       = isset($_COOKIE['push_menu']) && $_COOKIE['push_menu'] == 'close' ? 'sidebar-collapse' : '';
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition skin-blue sidebar-mini <?= $push_menu ?>">
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="<?= Url::to(['/']) ?>" class="logo hidden-xs">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">
                    <?= mb_substr(Yii::$app->name, 0, 1) ?>
                </span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">
                    <?= Yii::$app->name ?>
                </span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <?php if (Yii::$app->user->can('backend-access')): ?>
                            <!-- Backend -->
                            <li>
                                <a href="/backend">
                                    <i class="fa fa-user-secret"></i>
                                </a>
                            </li>
                            <!-- End Backend -->
                        <?php endif ?>

                        <!-- User -->
                        <li>
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user"></i>
                                <span><?= Html::encode(Yii::$app->user->identity->name) ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?= Url::to(['/users/default/index']) ?>">
                                        <i class="fa fa-gear"></i>
                                        <?= Yii::t('app', 'LINK_SETTING') ?>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?= Url::to(['/users/default/logout']) ?>" data-method="post">
                                        <i class="fa fa-power-off"></i>
                                        <?= Yii::t('app', 'LINK_EXIT') ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- End User -->
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li id="place-blank">
                        <a href="<?= Url::to(['/--------']) ?>">
                            <i class="fa fa-envelope"></i>
                            <span>-----------</span>
                        </a>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <?php if (isset($this->params['pageTitle'])): ?>
                    <h1 class="page-title">
                        <?php if (isset($this->params['pageIcon'])): ?>
                            <i class="fa fa-<?= $this->params['pageIcon'] ?>"></i>
                        <?php endif; ?>
                        <?= Html::encode($this->params['pageTitle']); ?>
                    </h1>
                <?php endif; ?>
                <?= Breadcrumbs::widget([
                    'homeLink' => [
                        'label' => Yii::t('app', 'LINK_HOME'),
                        'url' => ['/']
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
                ]) ?>
            </section>

            <!-- Main content -->
            <section class="content">

                <?= $content ?>

            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>
                &copy; <a href="<?= Url::to(['/']) ?>"><?= Yii::$app->name ?></a>
            </strong>
            <div class="pull-right">
                <div class="btn-group dropup dropleft visible-xs">
                    <button type="button" class="btn btn-primary btn-xs btn-flat" data-toggle="dropdown">
                        <i class="fa fa-info"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <?= Html::a('Публичная оферта', ['/main/default/offer'], [
                                'target' => '_blank'
                            ]) ?>
                        </li>
                        <li>
                            <?= Html::a('Политика конфиденциальности', ['/main/default/offer'], [
                                'target' => '_blank'
                            ]) ?>
                        </li>
                    </ul>
                </div>
                <ul class="menu hidden-xs">
                    <li>
                        <?= Html::a('Договор-оферта', ['/main/default/offer'], [
                            'target' => '_blank'
                        ]) ?>
                    </li>
                    <li>
                        <?= Html::a('Политика конфиденциальности', ['/main/default/policy'], [
                            'target' => '_blank'
                        ]) ?>
                    </li>
                </ul>
            </div>
        </footer>

        <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

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

    /**
     * Подсказка при создании тикета
     */
    Modal::begin([
        'id'            => 'support-view-faq',
        'headerOptions' => ['class' => 'bg-yellow'],
        'header'        => '<h4 class="modal-title">' . Yii::t('app', 'TITLE_MODAL_WARNING') . '</h4>',
    ]);
    ?>

    <p class="text-center">
        Прежде чем задавать вопрос рекомендуем ознакомится с разделом
        &laquo;<?= Html::a('Справочник', ['/main/default/faq']) ?>&raquo;
    </p>
    <p class="text-center">
        <?= Html::a('Задать вопрос', ['/support/default/create'], [
            'class' => 'btn btn-primary',
        ]) ?>
    </p>

    <?php if (Yii::$app->user->can('admin') === false): ?>
        <?= $this->renderFile('@common/views/analytics_counters.php') ?>
    <?php endif; ?>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>