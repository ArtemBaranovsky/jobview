<?php

namespace common\components;

use Yii;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\Palette\RGB;
use Imagine\Image\ManipulatorInterface;

/**
 * Class Image
 * @package common\components
 */
class Image extends \yii\imagine\Image
{
    /**
     * @param string $filename
     * @param int $width
     * @param int $height
     * @param string $mode
     * @param bool $saveSmallOrigin Сохранять оригинал если $width и $height больше чем размер изображения
     * @return \Imagine\Image\ImageInterface|ManipulatorInterface
     */
    public static function thumbnail($filename, $width, $height, $mode = ManipulatorInterface::THUMBNAIL_OUTBOUND, $saveSmallOrigin = true)
    {
        $box = new Box($width, $height);
        $img = static::getImagine()->open(Yii::getAlias($filename));

        if ($saveSmallOrigin && (($img->getSize()->getWidth() <= $box->getWidth() && $img->getSize()->getHeight() <= $box->getHeight()) || (!$box->getWidth() && !$box->getHeight()))) {
            return $img->copy();
        }

        $img = $img->thumbnail($box, $mode);

        $palette = new RGB();
        $color = $palette->color(static::$thumbnailBackgroundColor, static::$thumbnailBackgroundAlpha);

        // create empty image to preserve aspect ratio of thumbnail
        $thumb = static::getImagine()->create($box, $color);

        // calculate points
        $size = $img->getSize();

        $startX = 0;
        $startY = 0;
        if ($size->getWidth() < $width) {
            $startX = ceil($width - $size->getWidth()) / 2;
        }
        if ($size->getHeight() < $height) {
            $startY = ceil($height - $size->getHeight()) / 2;
        }

        $thumb->paste($img, new Point($startX, $startY));

        return $thumb;
    }
}