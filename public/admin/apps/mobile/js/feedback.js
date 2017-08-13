;(function() {
window.MobileFeedback = {
    view:function(id) {
        ct.ajaxDialog({
            title: '查看反馈',
            width: 500
        }, '?app=mobile&controller=feedback&action=view&feedbackid=' + id, null, function() {
            return true;
        });
        return false;
    },
    del:function(id) {
        var length = 1;
        if (!id) {
            id = tableApp.checkedIds();
            length = id.length;
            id = id.join(',');
        }
        if (!id) {
            ct.error('请选择要删除的记录');
            return false;
        }
        ct.confirm('操作不可撤销，确定要删除选择的 <span style="color:red;">' + length + '</span> 条记录吗？', function() {
            $.getJSON('?app=mobile&controller=feedback&action=delete&feedbackid=' + id, function(json) {
                if (json && json.state) {
                    ct.ok('删除成功');
                    tableApp.reload();
                } else {
                    ct.error(json && json.error || '删除失败');
                }
            });
        });
        return false;
    }
};
})();