<?php
/**
 * Created by PhpStorm.
 * User: hzhihua
 * Date: 17-8-5
 * Time: 上午10:26
 */

namespace hzhihua\videojs;

use Yii;
use yii\web\View;

class VideoJsAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/npm-asset/video.js/dist';

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
            'yii\\bootstrap\\BootstrapAsset',
        ];
    }

    public function getJs()
    {
        $language = VideoJsWidget::getLanguage();
        return [
        	'video.min.js',
            "lang/{$language}.js",
        ];
    }

    public function getCss()
    {
        return [
        	'video-js.min.css',
        ];
    }

}