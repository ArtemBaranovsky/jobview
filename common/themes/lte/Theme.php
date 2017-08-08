<?php

namespace themes\lte;

use yii;
use yii\helpers\Html;

/**
 * Class Theme
 * @package themes\lte
 */
class Theme extends yii\base\Theme {
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@frontend/views' => '@themes/lte/views/frontend',
        '@backend/views' => '@themes/lte/views/backend',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::$container->set('yii\widgets\LinkPager', [
            'maxButtonCount' => 5
        ]);

        Yii::$container->set('yii\grid\CheckboxColumn', [
            'options' => [
                'style' => 'width: 30px'
            ]
        ]);

        Yii::$container->set('yii\grid\GridView', [
            'layout'       => "{items}\n<div class=\"text-center\">{pager}</div>",
            'tableOptions' => [
                'class' => 'table responsive',
                'width' => '100%',
            ]
        ]);

        Yii::$container->set('yii\widgets\ActiveForm', [
            'fieldClass' => 'common\widgets\ActiveField'
        ]);

        Yii::$container->set('yii\grid\ActionColumn', [
            'buttons' => [
                'up' => function ($url, $model, $key) {
                    return Html::a('<i class="fa fa-arrow-up"></i>', $url, [
                        'class' => 'btn btn-xs btn-info',
                        'title' => Yii::t('app', 'BTN_UP'),
                        'data-pjax' => 0,
                    ]);
                },
                'down' => function ($url, $model, $key) {
                    return Html::a('<i class="fa fa-arrow-down"></i>', $url, [
                        'class' => 'btn btn-xs btn-info',
                        'title' => Yii::t('app', 'BTN_DOWN'),
                        'data-pjax' => 0,
                    ]);
                },
                'view' => function ($url, $model, $key) {
                    return Html::a('<i class="fa fa-eye"></i> ' . Yii::t('app', 'BTN_VIEW'), $url, [
                        'class' => 'btn btn-xs btn-primary',
                        'title' => Yii::t('app', 'BTN_VIEW'),
                        'data-pjax' => 0,
                    ]);
                },
                'update' => function ($url, $model, $key) {
                    return Html::a('<i class="fa fa-edit"></i> ' . Yii::t('app', 'BTN_UPDATE'), $url, [
                        'class' => 'btn btn-xs btn-warning',
                        'title' => Yii::t('app', 'BTN_UPDATE'),
                        'data-pjax' => 0,
                    ]);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('<i class="fa fa-times"></i> ' . Yii::t('app', 'BTN_DELETE'), $url, [
                        'class' => 'btn btn-xs btn-danger', 'data-method' => 'post',
                        'title' => Yii::t('app', 'BTN_DELETE'),
                        'data' => [
                            'pjax' => 0,
                            'confirm' => Yii::t('app', 'CONFIRM_DELETE')
                        ],
                    ]);
                }
            ],
            'template' => '<div class="text-center">{update} {delete}</div>'
        ]);
    }
}