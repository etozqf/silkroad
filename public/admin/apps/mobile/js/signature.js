(function(){
    $(function() {
        ct.fet(IMG_URL + 'js/lib/jquery.cookie.js', function() {
            if (!$.cookie(COOKIE_PRE + 'mobile_expired')) {
                return;
            }
            var expiredWarningElement = $('<div><p class="t_c">移动版授权已到期<br />请联系 <a href="http://www.cmstop.com/" target="_blank">CmsTop</a> 官方购买授权</p></div>');
            var expiredWarningDialog = expiredWarningElement.dialog({
                height: 100,
                resizable: false,
                modal: true,
                buttons: {
                    "确定" : function() {
                        $(this).dialog('close');
                    }
                }
            });
        });
    });
})();