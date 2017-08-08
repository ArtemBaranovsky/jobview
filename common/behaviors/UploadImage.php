<?php

namespace common\behaviors;

use yii;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use common\components\Image;
use yii\db\BaseActiveRecord;
use yii\base\InvalidParamException;
use mongosoft\file\UploadImageBehavior;
use Imagine\Image\ManipulatorInterface;

/**
 * Class UploadImage
 * @package common\behaviors
 */
class UploadImage extends UploadImageBehavior {
    /**
     * Атрибут модели в который сохраняем название файла.
     * По умолчанию использовано значение $this->attribute.
     * @var
     */
    public $modelAttribute;
    /**
     * @var bool
     */
    public $crop = false;
    /**
     * @var
     */
    protected $_file;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modelAttribute = empty($this->modelAttribute) ? $this->attribute : $this->modelAttribute;
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        if (in_array($model->scenario, $this->scenarios)) {
            if (($file = $model->getAttribute($this->modelAttribute)) instanceof UploadedFile) {
                $this->_file = $file;
            } else {
                if ($this->instanceByName === true) {
                    $this->_file = UploadedFile::getInstanceByName($this->attribute);
                } else {
                    $this->_file = UploadedFile::getInstance($model, $this->attribute);
                }
            }
            if ($this->_file instanceof UploadedFile) {
                $this->_file->name = $this->getFileName($this->_file);
                $model->setAttribute($this->modelAttribute, $this->_file);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        /** @var BaseActiveRecord $model */
        $model = $this->owner;
        if (in_array($model->scenario, $this->scenarios)) {
            if ($this->_file instanceof UploadedFile) {
                if (!$model->getIsNewRecord() && $model->isAttributeChanged($this->modelAttribute)) {
                    if ($this->unlinkOnSave === true) {
                        $this->delete($this->attribute, true);
                    }
                }
                $model->setAttribute($this->modelAttribute, $this->_file->name);
            } else {
                // Protect attribute
                unset($model->{$this->modelAttribute});
            }
        } else {
            if (!$model->getIsNewRecord() && $model->isAttributeChanged($this->modelAttribute)) {
                if ($this->unlinkOnSave === true) {
                    $this->delete($this->attribute, true);
                }
            }
        }
    }

    /**
     * @@inheritdoc
     */
    public function afterSave()
    {
        if ($this->_file instanceof UploadedFile) {
            $path = $this->getUploadPath($this->modelAttribute);
            if (is_string($path) && FileHelper::createDirectory(dirname($path))) {
                $this->save($this->_file, $path);
                $this->afterUpload();
            } else {
                throw new InvalidParamException("Directory specified in 'path' attribute doesn't exist or cannot be created.");
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function afterUpload()
    {
        if ($this->crop){
            $this->crop($this->getUploadPath($this->modelAttribute));
        }
        if ($this->createThumbsOnSave) {
            $this->createThumbs();
        }
    }

    /**
     * @inheritdoc
     */
    protected function createThumbs()
    {
        $path = $this->getUploadPath($this->modelAttribute);
        foreach ($this->thumbs as $profile => $config) {
            $thumbPath = $this->getThumbUploadPath($this->modelAttribute, $profile);
            if ($thumbPath !== null) {
                if (!FileHelper::createDirectory(dirname($thumbPath))) {
                    throw new InvalidParamException("Directory specified in 'thumbPath' attribute doesn't exist or cannot be created.");
                }
                if (!is_file($thumbPath)) {
                    $this->generateImageThumb($config, $path, $thumbPath);
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        $attribute = $this->modelAttribute;
        if ($this->unlinkOnDelete && $attribute) {
            $this->delete($attribute);
        }
    }

    /**
     * @return array|bool
     */
    protected function cropConfig()
    {
        $post = Yii::$app->request->post('crop');

        if ($post === null || isset(
                $post[$this->attribute]['x'],
                $post[$this->attribute]['y'],
                $post[$this->attribute]['w'],
                $post[$this->attribute]['h']
            ) === false) {
            return false;
        }

        $x = $post[$this->attribute]['x'];
        $y = $post[$this->attribute]['y'];
        $w = $post[$this->attribute]['w'];
        $h = $post[$this->attribute]['h'];

        return [
            'x' => abs($x),
            'y' => abs($y),
            'w' => abs($w),
            'h' => abs($h)
        ];
    }

    /**
     * @param $path
     * @return bool
     */
    protected function crop($path)
    {
        if (($config = $this->cropConfig()) === false){
            return false;
        }

        list($imageWidth, $imageHeight) = getimagesize($path);

        if ($config['x'] > $imageWidth){
            $config['x'] = 0;
        }
        if ($config['y'] > $imageHeight){
            $config['y'] = 0;
        }
        if ($config['w'] < 1){
            $config['w'] = $imageWidth;
        }
        if ($config['h'] < 1){
            $config['h'] = $imageHeight;
        }

        $image = Image::getImagine()->open($path);
        $image->crop(
            new Point($config['x'], $config['y']),
            new Box($config['w'], $config['h'])
        );

        $image->save($path);
    }

    /**
     * @inheritdoc
     */
    protected function generateImageThumb($config, $path, $thumbPath)
    {
        $width = ArrayHelper::getValue($config, 'width');
        $height = ArrayHelper::getValue($config, 'height');
        $quality = ArrayHelper::getValue($config, 'quality', 100);
        $mode = ArrayHelper::getValue($config, 'mode', ManipulatorInterface::THUMBNAIL_INSET);

        if (!$width || !$height) {
            $image = Image::getImagine()->open($path);
            $ratio = $image->getSize()->getWidth() / $image->getSize()->getHeight();
            if ($width) {
                $height = ceil($width / $ratio);
            } else {
                $width = ceil($height * $ratio);
            }
        }

        // Fix error "PHP GD Allowed memory size exhausted".
        ini_set('memory_limit', '512M');
        Image::thumbnail($path, $width, $height, $mode, false)->save($thumbPath, ['quality' => $quality]);
    }

    /**
     * @inheritdoc
     */
    public function deleteImages($attribute, $old = false)
    {
        parent::delete($attribute, $old);

        $this->owner->updateAttributes([
            $attribute => ''
        ]);
    }
}