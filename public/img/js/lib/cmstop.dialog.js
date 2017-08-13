window.dialog = function(o) {
    var option = o || {};
    var self = this;
    var container,overlay,panel = null;
    var isIE6 = (/opera/.test(navigator.userAgent.toLowerCase()) ? 0 : parseInt((/.+msie[\/: ]([\d.]+)/.exec(navigator.userAgent.toLowerCase()) || {1:0})[1]) == 6);
    var isCenter = false;
    var winResizeBackup;
    var divMaker = function(attribute, style, parentObj) {
        attribute = attribute || {};
        style = style || {};
        var obj = document.createElement('div');
        for (var key in attribute) {
            if (key == 'class') {
                obj.setAttribute('class', attribute[key]);
                obj.setAttribute('className', attribute[key]);  // IE
            } else {
                obj.setAttribute(key, attribute[key]);
            }
        }
        for (var key in style) {
            obj.style[key] = style[key];
        }
        if (!parentObj) {
            parentObj = document.body;
        }
        parentObj.appendChild(obj);
        return obj;
    }
    var getWidth    = function() {
        var width   = window.innerWidth;
        if (width == undefined) { // IE
            width   = document.documentElement.clientWidth;
        }
        return width;
    }
    var getHeight = function() {
        var height  = window.innerHeight;
        if (height == undefined) { //IE
            height  = document.documentElement.clientHeight;
            height  = ((window.screen.height - 100) < height) ? window.screen.height - 100 : height;
        }
        return height;
    }
    var getWebHeight = function() {
        return Math.max(
            document.documentElement["clientHeight"],
            document.body["scrollHeight"], document.documentElement["scrollHeight"],
            document.body["offsetHeight"], document.documentElement["offsetHeight"]
        );
    }
    var bind = function bind(obj, action, func) {
        if (window.addEventListener) {
            obj.addEventListener( action, function(event) {
                func(obj, event);
            }, false);
        } else if (window.attachEvent) { //IE
            obj.attachEvent('on' +action, function(event) {
                func(obj, event);
            });
        }
    }
    var unbind = function(obj, action, func) {
        if (window.removeEventListener) {
            obj.removeEventListener(action, func , false);
        } else if (window.detachEvent) { //IE
            obj.detachEvent(action, func);
        }
    }
    self.isOpen = false;
    self.miniWin = false;
    self.open = function(o) {
        var left, top, close, shadow;
        if (typeof (o) == 'object') {
            for (var i in o) {
                option[i] = o[i];
            }
        }
        if (self.isOpen) return;
        self.isOpen = true;
        isCenter = (!option.top && !option.left);
        option.shadow = (option.shadow==undefined) ? 16 : option.shadow;
        option.width = (option.width + option.shadow) || 400;
        option.height = (option.height + option.shadow) || (option.confirm ? 180 : 280);
        option.title = option.title || '';
        left = isCenter && !option.left ? (getWidth() - option.width) / 2 : option.left;
        if (left < 0) left = 0;
        left += 'px';
        top = isCenter && !option.top ? (getHeight() - option.height) / 2 : option.top;
        if (top < 0) top = 0;
        if (isIE6) {
            top += document.documentElement.scrollTop;
        }
        top += 'px';
        if (option.hasOverlay) {
            overlay = divMaker({'class':'dialog-overlay'}, {'height':getWebHeight()+'px'});
        }
        container = divMaker({'class':'dialog-container'}, {'width':(option.width+'px'), 'height':(option.height+'px'), 'left':left, 'top':top});
        shadow  = divMaker({'class':'dialog-shadow'},{},container);
        panel   = divMaker({'class':'dialog-panel'}, {'width':(option.width-option.shadow+'px'), 'height':(option.height-option.shadow+'px'), 'left':option.shadow/2+'px', 'top':option.shadow/2+'px'}, container);
        if (option.hasCloseIco) {
            close   = divMaker({'class':'dialog-close ie6Opacity'},{},container);
            bind(close, 'click', self.close);
        }
        if (option.url) {
            var ifm = self.iframe = document.createElement('iframe');
            ifm.src = option.url;
            ifm.style.width     = '100%';
            ifm.style.height    = '100%';
            ifm.frameBorder     = 0;
            panel.appendChild(ifm);
        } else if (option.html) {
            // 特殊处理script标签
            var s = new Array();
            option.html = option.html.replace(/<script(?:[^>]+?type="([^"]*)")?[^>]*>([^<][\s\S^(?:<\/script>)]+?)<\/script>/ig,function($1, $2, $3){
                if ($2 && $2 != 'text/javascript') {
                    return $1;
                }
                s.push($3);
                return '';
            });
            panel.innerHTML = option.html;
            for (var i=0,l=s.length; i<l; i++) {
                window[ "eval" ].call( window, s[i] );
            }
        } else if (typeof(option.confirm) == 'function') {
            var infoPanel = divMaker({'class':'dialog-confirm-panel'}, {}, panel);
            divMaker({'class':'dialog-info-ico'}, {'marginRight':'12px'}, infoPanel);
            var infoDiv = divMaker({'class':'dialog-confirm-info'}, {'display':'inline-block'}, infoPanel);
            infoDiv.innerHTML = option.text || '';
            var confirmDiv = divMaker({'class':'dialog-confirm-select'}, {}, panel);
            var okBtn = divMaker({'class':'dialog-btn'}, {'margin':'0 6px'}, confirmDiv);
            okBtn.innerHTML = '确定';
            var canelBtn = divMaker({'class':'dialog-btn'}, {'margin':'0 6px'}, confirmDiv);
            canelBtn.innerHTML = '取消';
            bind(okBtn, 'click',  option.confirm);
            bind(okBtn, 'click',  self.close);
            bind(canelBtn, 'click', self.close);
        } else if(option.text) {
            panel.innerHTML = '<p style="line-height: '+(option.height-option.shadow)+'px; text-align:center;">'+option.text+'</p>';
        }
        if (option.closeDelay) {
            setTimeout(self.close, option.closeDelay);
        }
        winResizeBackup = window.onresize;
        if (isCenter) {
            window.onresize = function() {
                if (typeof (winResizeBackup) == 'function') winResizeBackup();
                var left = (getWidth() - option.width) / 2;
                if (left < 0) left = 0;
                left += 'px';
                var top = (getHeight() - option.height) / 2;
                if (top < 0) top = 0;
                if (isIE6) {
                    top += document.documentElement.scrollTop;
                }
                top += 'px';
                container.style.top = top;
                container.style.left = left;
            }
        }
    } 
    self.close = function() {
        if (!self.isOpen) return;
        self.isOpen = false;
        if (overlay) {
            overlay.parentNode.removeChild(overlay);
        }
        if (container) {
            container.parentNode.removeChild(container);
        }
        window.onresize = winResizeBackup;
    }
    self.resize = function(newWidth, newHeight) {
        if (newWidth && newWidth > 0) {
            container.style.width = newWidth + option.shadow + 'px';
            panel.style.width = newWidth + 'px';
        }
        if (newHeight && newHeight > 0) {
            container.style.height = newHeight + option.shadow + 'px';
            panel.style.height = newHeight + 'px';
        }
    }
}