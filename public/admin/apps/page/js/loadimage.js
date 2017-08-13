/**
 * 一个动态加载图片并绑定回调事件的脚本
 *
 * 感谢 http://www.52ladybug.com/index.php/archives/364 的点子，
 * 让获取图片高宽信息得以更迅速。
 *
 * 用法如下：
 * <code lang="javascript">
 * loadImage('http://micate.me/1.jpg', {
 *     ready: function(image, width, height) {
 *         console.info(image, width, height);
 *     },
 *     success: function(image, result) {
 *         console.info(image, result);
 *     },
 *     error: function(image, error, errno) {
 *         console.info(error, errno);
 *     },
 *     complete: function(image, status, error) {
 *         console.info('complete ' + status);
 *     }
 * });
 * </code>
 *
 * @author micate<micate@qq.com>
 */
var loadImage = function() {
    var toString = Object.prototype.toString,
        isPlainObject = function(val) {
            return val && toString.call(val) === '[object Object]' && 'isPrototypeOf' in val;
        },
        isFunction = function(val) {
            return val && toString.call(val) === '[object Function]';
        },
        callback = function(src, image, result, config) {
            switch (result.status) {
                case 'success':
                    isFunction(config.success) && config.success.call(null, src, result);
                    break;
                case 'error':
                default:
                    isFunction(config.error) && config.error.call(null, src, result.error);
                    break;
            }
            isFunction(config.complete) && config.complete.call(null, src, result.status, result.error);
            if (image) {
                image = image.onload = image.onerror = null;
            }
        };
    return function(src, config) {
        var image, width, height, interval, onReady, readyDetect, endDetect;

        // 图片地址未定义
        if (! src) {
            return false;
        }

        config = isPlainObject(config) ? config : {};
        image = new Image();
        image.src = src;

        // 得到了图片的大小信息
        if (isFunction(config.ready)) {
            onReady = function(width, height) {
                endDetect();
                config.ready.call(null, src, width, height);
            };
            readyDetect = function() {
                var newWidth = image.width,
                    newHeight = image.height;
                if (newWidth !== width || newHeight !== height || (newWidth * newHeight > 1)) {
                    onReady(newWidth, newHeight);
                }
            };
            endDetect = function() {
                interval && clearInterval(interval);
                readyDetect = null;
            };
        }

        // 已缓存
        if (image.complete) {
            readyDetect && onReady(image.width, image.height);
            return callback(src, image, {
                status: 'success',
                width: image.width,
                height: image.height
            }, config);
        }

        width = image.width;
        height = image.height;

        readyDetect && readyDetect();

        // 加载错误
        image.onerror = function(error) {
            callback(src, image, {
                status: 'error',
                error: error
            }, config);
        };

        // 加载成功
        image.onload = function() {
            readyDetect && onReady(image.width, image.height);
            callback(src, image, {
                status: 'success',
                width: image.width,
                height: image.height
            }, config);
        };

        // 定时检测图片大小信息是否获取成功
        readyDetect && (interval = setInterval(readyDetect, 50));
    };
}();
