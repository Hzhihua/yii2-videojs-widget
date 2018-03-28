<?php
/**
 * Created by PhpStorm.
 * User: hzhihua
 * Date: 17-8-5
 * Time: 上午10:26
 */

namespace hzhihua\videojs;

class VideoJsFlashAsset extends \yii\web\AssetBundle
{

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
        return [
            'https://cdn.jsdelivr.net/npm/videojs-flash@2/dist/videojs-flash.min.js',
        ];
    }

    public function getCss()
    {
        return [];
    }

}