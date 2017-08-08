<?php
/**
 * Created by PhpStorm.
 * User: hzhihua
 * Date: 17-8-4
 * Time: 下午4:35
 */

use hzhihua\videojs\VideoJsWidget;

?>
<?= VideoJsWidget::widget([
        'options' => [  // video tag attibutes
//            'id' => 'video',  // id值  ==>  id=""
            'class' => 'video-js vjs-default-skin vjs-big-play-centered',
            'title' => '这里是视频标题',
            'poster' => "//vjs.zencdn.net/v/oceans.png",  // 视频播放封面地址
            'controls' => true, // 显示控制页面
            'preload' => false,
            'playsinline' => true, // 禁止在iPhone Safari中自动全屏   ios10以后版本
            'webkit-playsinline' => true, // 禁止在iPhone Safari中自动全屏   ios10以前版本
            'autoplay' => false, // 是否自动播放
            'loop' => false, // 循环播放
            'hidden' => false, // 是否隐藏
            'width' => '500',
//            'height' => '260',
            'data' => [
                'setup' => [
//                    'aspectRatio' => '16:9',  // responsive 响应式比例
                    'techOrder' => ['html5', 'flash'],  // 默认HTML5播放  不支持HTML5自动转flash播放
                    'language' => Yii::$app->language,
                ],
            ],
        ],
        'jsOptions' => [
            'playbackRates' => [0.5, 1, 1.5, 2],  // 播放速率选项
//            'controlBar' => [
//                'children' => [
//                    'playToggle' => true,
////                    'bigPlayButton' => false,
//
//                    'currentTimeDisplay' => true,
//                    'timeDivider' => true,
//                    'durationDisplay' => true,
//                    'liveDisplay' => true,
//
//                    'flexibleWidthSpacer' => true,
//                    'progressControl' => true,
//
//                    'muteToggle' => true,  // 声音图标
//                    'volumeControl' => true,  // 声音控制条
//
//                    'captionsButton' => true,  // 字幕
//
//                    'chaptersButton' => true,
//                    'playbackRateMenuButton' => true,
//                    'subtitlesButton' => true,
//
//                     'settingsMenuButton'=>[
//                          'entries'=>[
//                              'subtitlesButton',
//                              'playbackRateMenuButton',
//                          ],
//                     ],
//
//                    'fullscreenToggle' => true,
//                ],
//            ],
        ],
        'initFunction' => '
            function () {
                console.log(this);
            }
        ',
        'tags' => [
            'source' => [
                ['src' => '//vjs.zencdn.net/v/oceans.mp4', 'type' => 'video/mp4'],
                ['src' => 'audio.mp3', 'type' => 'audio/mp3'],
            ],
            'p' => [
                ['content' => '您的浏览器不支持html5视频播放'],
            ],
//            'track' => [ // 字幕
//                ['kind' => 'captions', 'src' => './example-captions.vtt', 'srclang' => 'zh-CN', 'label' => '中文字幕'],
//                ['kind' => 'captions', 'src' => './example-captions.vtt', 'srclang' => 'en', 'label' => '英文字幕']
//            ]
        ]
    ]); ?>

