$(function(){
    $(".create_filemd5").bind('click', function(){
        $.getJSON("?app=safe&controller=verify&action=create_filemd5", null, function(json){
            if (json.state) {
                $(".create_file_notice").remove();
                ct.ok(json.message);
            } else {
                ct.error(json.error);
            }
        });
    });

    $(".check-start").bind("click", function(){
        $.getJSON("?app=safe&controller=verify&action=check", null, function(json){
            if (json.state) {
                tableApp.reload();
            } else if(json.error) {
                ct.warn(json.error);
            }
        });
    });
});

var verify = {
    enable: function(id){
        if (typeof id == 'undefined') return false;
        ct.confirm(
            '此操作不可恢复，确认合并到文件校验基准库？', function() {
            $.getJSON("?app=safe&controller=verify&action=enable", {id:id}, function(json){
                if (json.state) {
                    tableApp.reload();
                    ct.ok(json.message);
                } else {
                    ct.error(json.error);
                }
            });
        });
    },
    addition: function(file, id)
    {
        if (typeof id == 'undefined' || typeof file == 'undefined') return false;
        ct.confirm(
            '此操作不可恢复，确认添加到文件校验基准库？', function() {
            $.getJSON("?app=safe&controller=verify&action=addition", {file:file, id:id}, function(json){
                if (json.state) {
                    tableApp.reload();
                    ct.ok(json.message);
                } else {
                    ct.error(json.error);
                }
            });
        });
    },
    del: function(id){
        if (typeof id == 'undefined') return false;
        ct.confirm(
            '此操作不可恢复，确认删除文件校验基准库的记录？', function() {
            $.getJSON("?app=safe&controller=verify&action=del", {id:id}, function(json){
                if (json.state) {
                    tableApp.reload();
                    ct.ok(json.message);
                } else {
                    ct.error(json.error);
                }
            });
        });
    },
    deal: function(){
        var ids = tableApp.checkedIds();
        var count = ids.length;
        if (count == 0) {
            ct.warn('请选择要操作的记录');
            return false;
        }

        ct.confirm(
            '此操作不可恢复，确认'+count+'条记录的处理，包括合并、添加和删除？', function() {
            for (var i = 0; i < count; i++) {
                id = ids[i];
                var tr = $("#row_" + id);
                var status = tr.find("input[name='status']").val();
                var file = tr.find("input[name='addition']").val();
                if (status == 0) {
                    $.ajax({
                        url: "?app=safe&controller=verify&action=enable",
                        data: {id:id},
                        dataType: 'json',
                        async: false,
                        success: function(json){
                            if (!json.state) {
                                ct.error(json.error);
                            }
                        }
                    });
                } else if(status == 1) {
                    $.ajax({
                        url: "?app=safe&controller=verify&action=addition",
                        data: {file:file, id:id},
                        dataType: 'json',
                        async: false,
                        success: function(json){
                            if (!json.state) {
                                ct.error(json.error);
                            }
                        }
                    });
                } else if(status == 2) {
                    $.ajax({
                        url: "?app=safe&controller=verify&action=del",
                        data: {id:id},
                        dataType: 'json',
                        async: false,
                        success: function(json){
                            if (!json.state) {
                                ct.error(json.error);
                            }
                        }
                    });
                }
            }

            ct.ok('完成确认');
            tableApp.reload();
        });
    },
    view: function(id)
    {
        var status = $("#row_" + id).find("input[name='status']").val();
        if (status == 2) {
            ct.warn('不能查看丢失文件');
            return;
        }
        var filename = $("#row_"+id).find('.filename').html();
        var url = "?app=safe&controller=verify&action=view";
        var title = '代码预览：' + filename;
        url += "&rowid=" +id+ "&title="+encodeURI(title);
        ct.iframe({
            url:url,
            width:1000,
            height: 525,
            title:title
        },{});
    }
}