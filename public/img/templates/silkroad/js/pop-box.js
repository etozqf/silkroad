//弹出框效果
function popbox(obj) {
    if (!(0 <= navigator.userAgent.indexOf("Firefox"))) {
        var a = navigator.appName,
            b = navigator.appVersion.split(";")[1].replace(/[ ]/g, "");
        if ("Microsoft Internet Explorer" == a && "MSIE6.0" == b || "Microsoft Internet Explorer" == a && "MSIE7.0" == b) {
            height_e = $(document.body).height();
        }
    }
    $(obj).fadeIn({
        height: 'toggle'
    });
}
function popclose(obj) {
    $(obj).hide();
}