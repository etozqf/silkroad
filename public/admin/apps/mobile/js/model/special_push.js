(function() {
var storedCategoryids, categoryList, tpl;

function open(callback) {
    var _dialog = ct.iframe({
        url:'?app=mobile&controller=special&action=recommend&contentid='+MobileContent.contentid,
        width:800,
        height:445
    }, {
        ok:function(titles) {
            callback(titles);
            _dialog.dialog('close');
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
    categoryList.empty();
    if (titles && !isEmptyObject(titles)) {
        $.each(titles, function(i, title) {
            categoryList.append(renderTitle(title));
        });
    }
}
function renderTitle(title) {
    var row = $($.trim(ct.renderTemplate(tpl, title)));
    row.find('a.remove').click(function() {
        ct.confirm('确定要将该内容从专题中移除吗？', function() {
            remove(title, row);
        });
        return false;
    });
    return row;
}
function genCategoryIds(titles) {
    var result = [];
    $.each(titles || {}, function(i, n) {
        result.push(n.catid);
    });
    return result.join(',');
}
function removeId(id) {
    if (!storedCategoryids) return;
    storedCategoryids = storedCategoryids.split(',');
    id = id + '';
    var index = storedCategoryids.indexOf(id);
    if (index > -1) {
        storedCategoryids.splice(index, 1);
    }
    storedCategoryids = storedCategoryids.join(',');
}
function remove(title, row) {
    $.getJSON('?app=mobile&controller=special&action=removeRecommend', {
        contentid:MobileContent.contentid,
        catid:title.catid
    }, function(json) {
        if (json.state) {
            removeId(title.catid);
            row && row.remove();
        } else {
            ct.error(json.error);
        }
    });
}
function save(titles, success) {
    var catids = genCategoryIds(titles);
    $.getJSON('?app=mobile&controller=special&action=saveRecommend', {
        contentid:MobileContent.contentid,
        catid:catids
    }, function(json) {
        if (json && json.state) {
            storedCategoryids = catids;
            success && success();
        } else {
            ct.error(json && json.error || '保存失败');
        }
    });
    success && success();
}
window.MobilePushToSpecialCategory = function(titles) {
    var btn = $('#special-category-select');
    if (!btn.length) return;
    storedCategoryids = genCategoryIds(titles);
    categoryList = $('#special-category-list');
    tpl = $('#tpl-special-category').html();
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