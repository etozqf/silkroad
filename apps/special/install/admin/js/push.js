(function() {
var storedPlaceids, placeList, tpl;

function open(callback) {
    var dialog = ct.iframe({
        url:'?app=special&controller=special&action=recommend&placeid='+storedPlaceids,
        width:800
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
    placeList.empty();
    if (titles && !isEmptyObject(titles)) {
        $.each(titles, function(i, title) {
            placeList.append(renderTitle(title));
        });
    }
}
function renderTitle(title) {
    var row = $($.trim(ct.renderTemplate(tpl, title)));
    row.find('a.remove').click(function() {
        ct.confirm('确定要将该信息从推送区块中移除吗？', function() {
            remove(title, row);
        });
        return false;
    });
    return row;
}
function genPlaceIds(titles) {
    var result = [];
    $.each(titles || {}, function(i, n) {
        result.push(n.placeid);
    });
    return result.join(',');
}
function removeId(id) {
    if (!storedPlaceids) return;
    storedPlaceids = storedPlaceids.split(',');
    id = id + '';
    var index = storedPlaceids.indexOf(id);
    if (index > -1) {
        storedPlaceids.splice(index, 1);
    }
    storedPlaceids = storedPlaceids.join(',');
}
function remove(title, row) {
    $.getJSON('?app=special&controller=special&action=removeRecommend', {
        contentid:content.contentid,
        placeid:title.placeid
    }, function(json) {
        if (json.state) {
            removeId(title.placeid);
            row && row.remove();
        } else {
            ct.error(json.error);
        }
    });
}
function save(titles, success) {
    var placeids = genPlaceIds(titles);
    $.getJSON('?app=special&controller=special&action=saveRecommend', {
        contentid:content.contentid,
        placeid:placeids
    }, function(json) {
        if (json && json.state) {
            storedPlaceids = placeids;
            success && success();
        } else {
            ct.error(json && json.error || '保存失败');
        }
    });
    success && success();
}
window.pushToPlace = function(titles) {
    var btn = $('#place-select');
    if (!btn.length) return;
    storedPlaceids = genPlaceIds(titles);
    placeList = $('#place-list');
    tpl = $('#tpl-place').html();
    btn.click(function(){
        open(function(titles) {
            save(titles, function() {
                renderTitles(titles);
            });
        });
        return false;
    });
    renderTitles(titles);
};
})();