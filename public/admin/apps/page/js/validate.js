(function() {
    function randomSrc(src) {
        return src + ((src.indexOf('?') > -1) ? '&' : '?') + '_=' + Math.random();
    }
    $.validate.setRules({
        natmin:function(elem, args){
            return $.natureLength(elem.val()) >= parseInt(args);
        },
        natmax:function(elem, args){
            return $.natureLength(elem.val()) <= parseInt(args);
        },
        img_ratio:function(elem, args){
            var src = elem.val(), args = args.split('x');
            if (!src) return true;
            if (!/^https?:\/\//.test(src)) src = UPLOAD_URL + src;
            loadImage(randomSrc(src), {
                ready:function(image, width, height) {
                    var passed = false;
                    if (width && height && (width/height).toFixed(1) == (parseInt(args[0])/parseInt(args[1])).toFixed(1)) {
                        passed = true;
                    }
                    elem.trigger('validateComplete', [passed]);
                }
            });
        },
        img_width:function(elem, args){
            var src = elem.val();
            if (!src) return true;
            if (!/^https?:\/\//.test(src)) src = UPLOAD_URL + src;
            loadImage(randomSrc(src), {
                ready:function(image, width, height) {
                    var passed = false;
                    if (width == parseInt(args)) {
                        passed = true;
                    }
                    elem.trigger('validateComplete', [passed]);
                }
            });
        },
        img_height:function(elem, args){
            var src = elem.val();
            if (!src) return true;
            if (!/^https?:\/\//.test(src)) src = UPLOAD_URL + src;
            loadImage(randomSrc(src), {
                ready:function(image, width, height) {
                    var passed = false;
                    if (height == parseInt(args)) {
                        passed = true;
                    }
                    elem.trigger('validateComplete', [passed]);
                }
            });
        }
    });

    window.page || (page = {});
    page.validate = function(form) {
        var rules = form.find('[data-role=validator-rules]').val();
        if (!rules || !(rules = ct.parseToJSON(rules))) return;
        var formName = form.attr('name');
        $.data(window, formName, rules);
    };
})();