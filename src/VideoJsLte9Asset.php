<?php
/**
 * Created by PhpStorm.
 * User: Hzhihua
 * Date: 17-8-5
 * Time: 上午10:26
 */

namespace hzhihua\videojs;

use yii\web\View;

class VideoJsLte9Asset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/hzhihua/yii2-videojs-widget/video.js/dist';

    public $jsOptions = [
        'condition' => 'lte IE9',
        'position' => View::POS_HEAD,
    ];

    public function init()
    {
        parent::init();
        $this->js = array_merge($this->js, $this->getJs());
        $this->css = array_merge($this->css, $this->getCss());
        $this->depends = array_merge($this->depends, $this->getDepends());
    }

    public function getDepends() {
        return [];
    }

    public function getJs() {
        return [
            '//cdn.bootcss.com/html5shiv/r29/html5.min.js',
            'ie8/videojs-ie8.min.js',
        ];
    }

    public function getCss() {
        return [];
    }

}