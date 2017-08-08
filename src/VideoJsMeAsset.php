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
    public $sourcePath = '@vendor/hzhihua/assets';

    public function init()
    {
        parent::init();
        $this->js = array_merge($this->js, $this->getJs());
        $this->css = array_merge($this->css, $this->getCss());
        $this->depends = array_merge($this->depends, $this->getDepends());
    }

    public function getDepends()
    {
        return [
            'yii\\web\\JqueryAsset',
            VideoJsAsset::className(),
            VideoJsFlashAsset::className(),
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