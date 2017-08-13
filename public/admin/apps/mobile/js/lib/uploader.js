;(function($) {
var DEFAULTS = {
    fileExt: '*.png;*.jpg;*.jpeg;*.gif',
    fileDesc: 'png|jpg|gif',
    multi:false,
    script:'?app=system&controller=upload&action=image',
    jsonType : 1
};
$.fn.mobileUploader = function(options, success) {
    if ($.isFunction(options)) {
        success = options;
        options = null;
    }
    options = $.extend({}, DEFAULTS, options);
    return this.each(function() {
        var btn = $(this);
        btn.uploader({
            fileDesc:options.fileDesc,
            fileExt:options.fileExt,
            multi:options.multi,
            script:options.script,
            jsonType: options.jsonType,
            complete:function(json, data){
                if (json && json.state) {
                    $.isFunction(success) && success(json);
                } else {
                    ct.error('上传失败');
                }
            },
            error:function(error){
                ct.warn(error.file.name+'：上传失败，'+error.type+':'+error.info);
            }
        });
    });
};
})(jQuery);