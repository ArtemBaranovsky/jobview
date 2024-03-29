<?php
Yii::setAlias('@root', dirname(dirname(__DIR__)));

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@mail', dirname(__DIR__) . '/mail');
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

Yii::setAlias('@modules', dirname(__DIR__) . '/modules');
Yii::setAlias('@themes', dirname(__DIR__) . '/themes');
Yii::setAlias('@files', dirname(__DIR__) . '/files');
Yii::setAlias('@filesUrl', '/files');