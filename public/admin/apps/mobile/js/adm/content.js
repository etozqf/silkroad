$(function() {
    /* 表单相关 */
    $('#form').ajaxForm(function(json) {
        if (json && json.state) {
            ct.tips('保存成功');
        } else {
            ct.error(json && json.error || '保存失败');
        }
    });
});
