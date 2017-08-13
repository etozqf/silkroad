(function() {
var sectionList, tpl;

function open(callback) {
    if (!content.contentid) {
        ct.error('内容不存在');
        return;
    }
    var dialog = ct.iframe({
        url:'?app=page&controller=section&action=recommend&contentid='+content.contentid,
        width:850
    }, {
        picked:function(titles) {
            callback(titles);
            dialog.dialog('close');
        }
    });
}
function isEmptyObject(obj) {
    var name;
    for (name in obj) {
        return false;
    }
    return true;
}
function renderTitles(titles) {
    sectionList.empty();
    if (titles && !isEmptyObject(titles)) {
        $.each(titles, function(i, title) {
            sectionList.append(renderTitle(title));
        });
    }
}
function renderTitle(title) {
    var row = $($.trim(ct.renderTemplate(tpl, title)));
    row.find('a.remove').click(function() {
        ct.confirm('确定要将该信息从区块中移除吗？', function() {
            remove(title, row);
        });
        return false;
    });
    return row;
}
function remove(title, row) {
    $.getJSON('?app=page&controller=section&action=removeRecommend', {
        contentid:content.contentid,
        sectionid:title.sectionid
    }, function(json) {
        if (json.state) {
            row && row.remove();
        } else {
            ct.error(json.error);
        }
    });
}
window.pushToSection = function(titles) {
    var btn = $('#section-select');
    if (!btn.length) return;
    sectionList = $('#section-list');
    tpl = $('#tpl-section').val();
    btn.click(function(){
        open(function(titles) {
            renderTitles(titles);
        });
        return false;
    });
    renderTitles(titles);
};
})();