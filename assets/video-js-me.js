// Get the Component base class from Video.js
// @see http://www.cnblogs.com/afrog/p/6689179.html
// 从Videojs中获取一个基础组件
var Component = videojs.getComponent('Component');

// The videojs.extend function is used to assist with inheritance. In
// an ES6 environment, `class TitleBar extends Component` would work
// identically.
// videojs.extend方法用来实现继承，等同于ES6环境中的class titleBar extends Component用法
var TitleBar = videojs.extend(Component, {

  // The constructor of a component receives two arguments: the
  // player it will be associated with and an object of options.
  // 这个构造函数接收两个参数：
  // player将被用来关联options中的参数
  constructor: function(player, options) {

    // It is important to invoke the superclass before anything else, 
    // to get all the features of components out of the box!
    // 在做其它事之前先调用父类的构造函数是很重要的，
    // 这样可以使父组件的所有特性在子组件中开箱即用。
    Component.apply(this, arguments);

    // If a `text` option was passed in, update the text content of 
    // the component.
    // 如果在options中传了text属性，那么更新这个组件的文字显示
    if (options.text) {
      this.updateTextContent(options.text);
    }
  },

  // The `createEl` function of a component creates its DOM element.
  // 创建一个DOM元素
  createEl: function() {
    return videojs.dom.createEl('div', {

      // Prefixing classes of elements within a player with "vjs-" 
      // is a convention used in Video.js.
      //给元素加vjs-开头的样式名，是videojs内置样式约定俗成的做法
      className: 'vjs-title-bar'
    });
  },

  // This function could be called at any time to update the text 
  // contents of the component.
  // 这个方法可以在任何需要更新这个组件内容的时候调用
  updateTextContent: function(text) {

    // If no text was provided, default to "Text Unknown"
    // 如果options中没有提供text属性，默认显示Text Unknow
    if (typeof text !== 'string') {
      text = 'Text Unknown';
    }

    // Use Video.js utility DOM methods to manipulate the content
    // of the component's element.
    // 使用Video.js提供的DOM方法来操作组件元素
    videojs.dom.emptyEl(this.el());
    videojs.dom.appendContent(this.el(), text);
  }
});

// Register the component with Video.js, so it can be used in players.
// 在videojs中注册这个组件，才可以使用哦.
videojs.registerComponent('TitleBar', TitleBar);

////////////////// FUNCTION ////////////////////
/**
 * html5 localStorage 本地数据库
 * ```JS
 * storage('hzhihua');              获取 hzhihua 的值
 * storage('hzhihua', 'student');   设置 hzhihua 的值为 student
 * ```
 * @param key   名称
 * @param value 值
 * @returns {*}
 */
var storage = function (key, value) {

    if (undefined === typeof(localStorage)) {
        return false;
    }

    if (undefined !== value) {
        // 取值
        return localStorage.setItem(key, value);
    }

    return localStorage.getItem(key);
};

/**
 * 获取播放视频名称
 * @param _this  事件 this
 * @returns {string} 视频名称
 */
var getVideoName = function (_this) {
    var src = _this.children('.vjs-tech')[0].currentSrc;
    var lastPosition = src.lastIndexOf('/');
    return src.substr(lastPosition+1);
};

////////////////////////// EVENT /////////////////////
/**
 * 播放事件
 */
var playEvent = function () {
    var _this = this;
    var _id = _this.id_;
    var _idSelector = $('#'+_id);

    _idSelector.children('.vjs-title-bar').addClass('vjs-hidden'); // 关闭标题
    _idSelector.children('.vjs-loading-spinner').addClass('vjs-hidden'); // 关闭loading图标

    $('.vjs-tech').each(function () {
        var _this = $(this)[0];
        if (_id !== _this.parentElement.id) {
            // 暂停其他正在播放的媒体 不对flash起作用
            'VIDEO' === _this.tagName ? _this.pause() : '';
        }
    });

    var videoName = getVideoName(_this);
    var volume = storage(videoName + '-volume');
    var playbackRate = storage(videoName + '-playbackRate');
    var visitedTime = storage(videoName + '-visited-time');
    _this.volume(null !== volume ? volume : 0.5);

    if ('html5' === _this.techName_.toLowerCase()) {
        _this.playbackRate(null !== playbackRate ? playbackRate : 1);
        _this.currentTime(null !== visitedTime ? visitedTime : 1);

        // 解决播完后不能自动重播问题 0秒可能会暂停
        _this.remainingTime() < 2 ? _this.currentTime(1) : '';
    }

};

/**
 * 暂停事件
 */
var pauseEvent = function () {
    $('#'+this.id_).children('.vjs-title-bar').removeClass('vjs-hidden'); // 显示标题
};

/**
 * 播放进度变化事件
 */
var timeUpdateEvent = function() {
    var currentTime = this.currentTime();
    // 刷新页面会触发此事件，此时 currentTime 为0  会覆盖之前保存的值
    currentTime ? storage(getVideoName(this) + '-visited-time', currentTime) : '';
};

/**
 * 音量变化事件
 */
var volumeChangeEvent = function() {
    var volume = this.volume();
    var videoName = getVideoName(this);
    var volumeSelector = $("#"+this.id_).children(".vjs-control-bar").children(".vjs-volume-panel");
    var muteButton = volumeSelector.children('.vjs-mute-control');
    var volumeControl = volumeSelector.children('.vjs-volume-control');

    // 判断静音按钮是否被点击
    // 点击静音按钮    clicked = true  已经被点击
    muteButton.click(function () {
        $(this).data('clicked', true);
    });
    // 点击音量控制条  clicked = false 未被点击
    volumeControl.click({muteButton:muteButton}, function (event) {
        $(event.data.muteButton).data('clicked', false);
    });

    if (muteButton.data('clicked')) {
        // 静音按钮被点击后，clicked 需设置为 false
        // 否则 音量控制条需点击 2 次才能生效
        // >>> 第一次将 clicked 设置为 false
        // >>> 第二次才执行 else ，保存音量值
        muteButton.data('clicked',false);

        // 不管是否点击静音按钮  this.volume的值一直等于音量控制条的值
        var storageVolume = parseFloat(storage(videoName + '-volume'));
        storage(videoName + '-volume', storageVolume ? 0 : volume); // 静音

    } else {
        storage(videoName + '-volume', volume);
    }
};

/**
 * 保存播放速率
 * @param event 传递事件参数
 */
var storagePlaybackRate = function (event) {
    var player = event.data.player;
    var playbackRate = player.playbackRate();
    storage(getVideoName(player) + '-playbackRate', playbackRate);
};