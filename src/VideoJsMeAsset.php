<?php
/**
 * Created by PhpStorm.
 * User: Hzhihua
 * Date: 17-8-5
 * Time: 上午10:26
 */

namespace hzhihua\videojs;

class VideoJsMeAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/hzhihua/yii2-videojs-widget/assets';

    public function init()
    {
        parent::init();

        $this->js = array_merge($this->getJs(), $this->js);
        $this->css = array_merge($this->getCss(), $this->css);
        $this->depends = array_merge($this->getDepends(), $this->depends);
    }

    public function getDepends()
    {
        return [
            'yii\\web\\JqueryAsset',
            VideoJsAsset::class,
            VideoJsFlashAsset::class,
        ];
    }

    public function getJs()
    {
        return [
            'video-js-me.js',
        ];
    }

    public function getCss()
    {
        return [
            'video-js-me.css',
        ];
    }

}