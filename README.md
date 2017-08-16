# yii2-videojs-widget
> 基于 **videojs 6.0** **videojs-falsh 2.0** 开发的yii2小部件 详见 [composer.json](composer.json)

![](https://raw.githubusercontent.com/wiki/Hzhihua/yii2-videojs-widget/videojs.png)

## [videojs官网](http://videojs.com/) [GitHub](https://github.com/videojs/video.js)
## 特色
更新时间 2017/8/9
1. 新增音量调节记录
2. 新增视频播放记录（flash不支持）  
3. 新增播放速率记录（flash不支持）
4. 新增视频标题显示（[特感谢 青蛙哥](http://www.cnblogs.com/afrog/p/6689179.html)）
5. 新增支持一个页面多视频播放

## 安装

### 1. 在命令行中执行
```install
composer require --prefer-dist "hzhihua/yii2-videojs-widget:~1.0"
```
### 2. 或者添加到composer.json require配置中
```install
"hzhihua/yii2-videojs-widget": "~1.0"
```
命令行运行
```code
composer update "hzhihua/yii2-videojs-widget"
```
若提示输入token，输入[GitHub token](http://www.cnblogs.com/Hzhihua/p/7064976.html)

## 使用
### 简单应用
```useage
<?= \hzhihua\videojs\VideoJsWidget::widget([
    'options' => [  // video tag attibutes
        'class' => 'video-js vjs-default-skin vjs-big-play-centered',
        'title' => '这里是视频标题',
        'poster' => "//vjs.zencdn.net/v/oceans.png",  // 视频播放封面地址
        'controls' => true, // 显示控制页面
        'width' => '300', // 设置宽度
        'data' => [
            'setup' => [
                'language' => Yii::$app->language, // 设置语言
            ],
        ],
    ],
    'jsOptions' => [
        'playbackRates' => [0.5, 1, 1.5, 2],  // 播放速率选项
    ],
    'tags' => [
        'source' => [
            ['src' => '//vjs.zencdn.net/v/oceans.mp4', 'type' => 'video/mp4'],
        ],
        'p' => [
            ['content' => '您的浏览器不支持媒体播放'],
        ],
    ]
]);
?>
```
### 详细应用
```usage
<?= \hzhihua\videojs\VideoJsWidget::widget([
     'options' => [  // video tag attibutes
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
//       'height' => '260',
         'data' => [
             'setup' => [
//               'aspectRatio' => '16:9',  // responsive 响应式比例
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
//                    'bigPlayButton' => false,
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
```
#### 详见
[examples](examples/views)
[videojs](https://github.com/videojs/video.js/tree/master/docs/guides)
