<?php
/**
 * Created by PhpStorm.
 * User: Hzhihua
 * Date: 17-8-5
 * Time: 上午1:39
 *
 * The yii2-videojs-widget is a Yii 2 wrapper for the video.js
 * See more: http://www.videojs.com/
 * Base on Wanderson Bragança https://github.com/wbraganca/yii2-videojs-widget
 *
 * @link      https://github.com/hzhihua/yii2-videojs-widget
 * @copyright Copyright (c) 2017 Hzhihua
 */

namespace hzhihua\videojs;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\InvalidConfigException;

/**
 * 详细的使用信息请查看 examples 目录
 *
 * 简单应用：
 * ```php
 * <?= \hzhihua\videojs\VideoJsWidget::widget([
 *     'options' => [  // video tag attibutes
 *         'class' => 'video-js vjs-default-skin vjs-big-play-centered',
 *         'title' => '这里是视频标题',
 *         'poster' => "//vjs.zencdn.net/v/oceans.png",  // 视频播放封面地址
 *         'controls' => true, // 显示控制页面
 *         'width' => '300', // 设置宽度
 *         'data' => [
 *             'setup' => [
 *                 'language' => \hzhihua\videojs\VideoJsWidget::getLanguage(), // 设置语言
 *             ],
 *         ],
 *     ],
 *     'jsOptions' => [
 *         'playbackRates' => [0.5, 1, 1.5, 2],  // 播放速率选项
 *     ],
 *     'tags' => [
 *         'source' => [
 *             ['src' => '//vjs.zencdn.net/v/oceans.mp4', 'type' => 'video/mp4'],
 *         ],
 *         'p' => [
 *             ['content' => '您的浏览器不支持媒体播放'],
 *         ],
 *     ]
 * ]);
 * ?>
 * ```
 *
 * @Github: https://github.com/Hzhihua/yii2-videojs-widget
 */
class VideoJsWidget extends \yii\base\Widget
{
    /**
     * video/object 标签属性
     * width/height 会生成style属性
     * @var array
     */
    public $options = [];

    /**
     * videojs实例化参数 转成json格式
     * ```videojs
     *      videojs('#id', json_encode($jsOptions));
     * ```
     * @var array
     */
    public $jsOptions = [];

    /**
     * videojs 初始化函数调用
     * ```php
     *  'initFunction' => 'function () {
     *      console.log(this);
     * }',
     * ```
     * ```videojs
     *    videojs('#id', json_encode($jsOptions), $initFunction);
     * ```
     * 注意  function外面单引号
     * @var string
     */
    public $initFunction = '';

    /**
     * 生成video/object标签的子标签
     * ```tag
     * 'tags' => [
     *      'source' => [
     *           ['src' => 'video.mp4', 'type' => 'video/mp4'],
     *           ['src' => 'audio.mp3', 'type' => 'audio/mp3'],
     *      ],
     *      'span' => [
     *          'content' => '您的浏览器不支持html5媒体播放',
     *      ],
     * ],
     * ```
     * 这将会生成
     * ```tag
     * <video>
     *      <source src="video.mp4" type="video/mp4">
     *      <source src="audio.mp3" type="audio/mp3">
     *      <span>您的浏览器不支持html5媒体播放</span>
     * </video>
     * ```
     * @var array
     */
    public $tags = [];

    /**
     * 用于标示播放器
     * ```php
     * <div class="hzhihua-video ..."><video></video></div>
     *
     * $('.hzhihua-video').each(function(){
     *      var player = videojs(...);
     *      ...
     * });
     * ```
     * @var string
     */
    protected $eachSeletor = 'videojs-each-selector';

    /**
     * 确保js只注册一次
     * @var int
     */
    protected static $hasRegistered = 0;

    /**
     * 初始化变量
     */
    public function init()
    {
        parent::init();

        if (!isset($this->options['id'])) {
            $this->options['id'] = 'videojs-' . $this->getId();
        }

        $this->options['class'] = $this->options['class'] . ' ' . $this->eachSeletor;

    }

    /**
     * 运行入口
     */
    public function run()
    {
        parent::run();

        if (!static::$hasRegistered) {
            static::$hasRegistered++;

            $this->registerAsset();
            $this->registerJs();
        }

        echo $this->generateTag();
    }

    /**
     * 输出视频标签
     * @return string
     * @throws InvalidConfigException
     */
    public function generateTag()
    {
        $html = Html::beginTag('video', $this->options);
        if (!empty($this->tags) && is_array($this->tags)) {
            foreach ($this->tags as $tagName => $tags) {
                if (is_array($this->tags[$tagName])) {
                    foreach ($tags as $tagOptions) {
                        $tagContent = '';
                        if (isset($tagOptions['content'])) {
                            $tagContent = $tagOptions['content'];
                            unset($tagOptions['content']);
                        }
                        $html .= Html::tag($tagName, $tagContent, $tagOptions);
                    }
                } else {
                    throw new InvalidConfigException("Invalid config for 'tags' property.");
                }
            }
        }
        $html .= Html::endTag('video');

        return $html;
    }

    /**
     * 注册资源
     * @return object
     */
    public function registerAsset()
    {
        $view = $this->getView();
        return VideoJsMeAsset::register($view);
    }

    /**
     * 注册js
     */
    public function registerJs()
    {
        $view = $this->getView();
        $js = $this->getRegisterJs();

        $view->registerJs($js);
    }

    /**
     * 获取需要注册的js
     * @return string
     */
    public function getRegisterJs()
    {
        $js  = '';
        
        $js .= $this->eachSelectorBegin(); // start
        $js .= $this->videoJsInstance(); // instance
        $js .= $this->addTitleBar();
        $js .= $this->getPauseEvent();
        $js .= $this->getPlayEvent();
        $js .= $this->getTimeUpdateEvent();
        $js .= $this->getVolumeChangeEvent();
        $js .= $this->getPlaybackRateEvent();
        $js .= $this->eachSelectorEnd(); // end

        return $js;

    }

    /**
     * 获取语言
     * @param string $language
     * @return string
     */
    public static function getLanguage($language = null)
    {
        (null === $language) && $language = Yii::$app->language;
        return $language === 'en-US' ? 'en' : $language;
    }

    /**
     * videojs 初始化
     */
    public function videoJsInstance()
    {
        $jsonOption = Json::encode($this->jsOptions);

        $js = '';

        if ($this->initFunction) {
            $js .= "var player = videojs('#'+this.id, $jsonOption, {$this->initFunction});";
        } else {
            $js .= "var player = videojs('#'+this.id, $jsonOption);";
        }

        return $js;
    }

    /**
     * videojs eachSeletor 开始
     * 通过对 $('.selector') 的遍历
     * 对页面中的每个视频进行初始化
     * @return string
     */
    public function eachSelectorBegin()
    {
        $js = ';';

        $js .= "\$('.{$this->eachSeletor}').each(function () {";

        return $js;
    }

    /**
     * eachSelector 结束
     * @return string
     */
    public function eachSelectorEnd()
    {
        $js = '});';
        return $js;
    }

    /**
     * 添加视频标题
     * @return string
     */
    public function addTitleBar()
    {
        $js  = 'var videoTitle = $("#"+this.id).attr("title");';
        $js .= 'player.addChild(\'TitleBar\', {text: videoTitle});';

        return $js;
    }

    /**
     * 获取videojs 暂停事件
     * @return string
     */
    public function getPauseEvent()
    {
        return 'player.on(\'pause\', pauseEvent);';
    }

    /**
     * 获得videojs 播放事件
     * @return string
     */
    public function getPlayEvent()
    {
        return 'player.on(\'play\', playEvent);';
    }

    /**
     * 获取videojs 播放进度变化事件
     * @return string
     */
    public function getTimeUpdateEvent()
    {
        return 'player.on(\'timeupdate\', timeUpdateEvent);';
    }

    /**
     * 获取videojs 音量变化事件
     * @return string
     */
    public function getVolumeChangeEvent()
    {
        return 'player.on(\'volumechange\', volumeChangeEvent);';
    }

    /**
     * 获取videojs 播放速率变化事件
     * @return string
     */
    public function getPlaybackRateEvent()
    {
        // jquery change 方法不能监听 div 内容改变事件
        // >>> 在google chrome可以使用
        // >>> $('seletor').bind('DOMNodeInserted', function () {});
        // >>> 但对360浏览器, ie浏览器不起作用
        // @see http://blog.itblood.com/jquery-how-to-listen-div-content-change.html
        //
        // jquery bind 传参数
        // >>> $('selector').bind('click', {name:'clicked'}, function(event){});
        // >>> $('selector').bind('click', {name:'clicked'}, functionName);
        // >>> >>> var functionName = function (event) {console.log(event.data.name)};

        // 仅对 google chrome 起作用，对ie 360浏览器不起作用
        //  return '$("#"+player.id_).children(".vjs-control-bar").children(".vjs-playback-rate").children("div.vjs-playback-rate-value").bind("DOMNodeInserted", {player:player}, storagePlaybackRate);';

        // 对速率按钮以及速率ul表绑定click点击事件
        $js = 'var playbackRate = $("#"+player.id_).children(".vjs-control-bar").children(".vjs-playback-rate");';
        $js .= 'playbackRate.children("button.vjs-playback-rate").click({player: player}, storagePlaybackRate);';
        $js .= 'playbackRate.children(".vjs-menu").children("ul.vjs-menu-content").click({player: player}, storagePlaybackRate);';
        return $js;
    }

}
