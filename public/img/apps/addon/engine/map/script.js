(function(){
var map, marker,
    mapContainer, mapCity,
    mapSearchInput, mapSearchSubmit,
    mapSearchLoading, mapSearchResult,
    cityField, centerField, markerField, keywordField, zoomField;
function createPoint(point) {
    markerField.val(JSON.stringify({
        lng: point.lng,
        lat: point.lat
    }));
    return new BMap.Point(point.lng, point.lat);
}
function createMarker(point, title) {
    marker && map.removeOverlay(marker);
    marker = new BMap.Marker(point, {
        enableDragging: true,
        title: title || ''
    });
    marker.addEventListener('dragend', function(e) {
        markerField.val(JSON.stringify({
            lng: e.point.lng,
            lat: e.point.lat
        }));
    });
    return marker;
}
function renderEmptyResult() {
    mapSearchResult.empty().html('未找到搜索结果').show();
    mapSearchResult.unbind().click(function() {
        mapSearchResult.hide();
    });
}
function renderResult(result, callback) {
    mapSearchResult.empty().append('<ul></ul>');
    var ul = mapSearchResult.find('ul');
    $.each(result, function(index, item) {
        var li = $('<li>' + item.title + '</li>').appendTo(ul).click(function(ev) {
            callback(item);
            ev.stopPropagation();
            mapSearchResult.hide();
        });
        index % 2 == 0 && li.addClass('odd');
    });
    mapSearchResult.show().unbind().click(function() {
        mapSearchResult.hide();
    });
}
function panToPosition(pos) {
    var point = createPoint(pos.point);
    map.panTo(point);
    map.addOverlay(createMarker(point, pos.title));
    mapSearchInput.val(pos.title);
}
function init(dialog, city, center, point, title, level) {
    // 地图和控件
    var ctrlNav = new BMap.NavigationControl({
        anchor: BMAP_ANCHOR_TOP_LEFT,
        type: BMAP_NAVIGATION_CONTROL_LARGE
    });

    mapContainer = dialog.find('#map-container');
    mapCity = dialog.find('#map-city');
    map = new BMap.Map('map-container');
    center = createPoint(center || city.center);
    map.centerAndZoom(center, parseInt(level || city.level));
    map.enableScrollWheelZoom();
    map.enableKeyboard();
    map.enableInertialDragging();
    map.enableContinuousZoom();
    map.addControl(ctrlNav);
    marker = createMarker(createPoint(point || city.center), title || city.name);
    map.addOverlay(marker);
    map.addEventListener('click', function(e) {
        panToPosition(e);
    });

    // 城市
    mapCity.text(city.name);
    cityField.val(JSON.stringify({
        center: city.center,
        level: city.level,
        name: city.name
    }));
    var cityListContainer = dialog.find('#map-city-list'),
        cityListChanger = dialog.find('#map-change-city');
    cityListContainer.hide().css({
        width: mapContainer.width(),
        height: mapContainer.height(),
        left: mapContainer.position().left,
        top: mapContainer.position().top
    });
    var cityList = new BMapLib.CityList({
        container:"map-city-list",
        map:map
    });
    cityList.addEventListener("cityclick", function(e) {
        mapCity.text(e.name);
        cityListContainer.hide();
        marker = createMarker(createPoint(e.center), e.name);
        map.addOverlay(marker);
        cityField.val(JSON.stringify({
            center: e.center,
            level: e.zoom,
            name: e.name
        }));
    });
    cityListChanger.click(function() {
        cityListContainer.toggle();
    });
    // TODO Hack 解决百度城市列表二次渲染不正确的问题
    !ct.IE && $('#_script_bmaplib_citylist_').remove();

    // 搜索
    var localSearch = new BMap.LocalSearch(map),
        searchLock =  false;
    localSearch.enableAutoViewport();
    localSearch.enableFirstResultSelection();
    localSearch.setSearchCompleteCallback(function(localResult) {
        var resultCount = localResult && localResult.getCurrentNumPois(),
            i, points;
        searchLock = false;
        mapSearchLoading.stop(false, true).fadeOut();
        if (! resultCount) {
            renderEmptyResult();
            return;
        }
        keywordField.val(mapSearchInput.val());
        if (resultCount == 1) {
            panToPosition(localResult.getPoi(0));
            return;
        }
        points = [];
        for (i = 0; i < resultCount; i++) {
            points.push(localResult.getPoi(i));
        }
        renderResult(points, function(pos) {
            panToPosition(pos);
        });
    });
    mapSearchSubmit.click(function() {
        if (searchLock) return false;
        searchLock = true;
        mapSearchResult.hide();
        mapSearchLoading.stop(false, true).fadeIn();
        localSearch.search(mapSearchInput.val());
        return false;
    });
    mapSearchInput.keyup(function(ev) {
        var value = $.trim(this.value);
        if (value && ev.keyCode == 13) {
            mapSearchSubmit.trigger('click');
            return false;
        }
    });
}

Addon.registerEngine('map', {
    dialogWidth: 550,
    afterRender: function(dialog) {
        dialog.find('.mod-tabs').tabs();

        mapSearchInput = dialog.find('#map-search-input');
        mapSearchSubmit = dialog.find('#map-search-submit');
        mapSearchLoading = dialog.find('#map-search-loading').hide();
        mapSearchResult = dialog.find('#map-search-result').hide().css({
            width: mapSearchInput.outerWidth() - 2,
            left: mapSearchInput.position().left,
            top: mapSearchInput.position().top + mapSearchInput.outerHeight(true) - 1
        });

        cityField = dialog.find('[name=city]');
        markerField = dialog.find('[name=marker]');
        keywordField = dialog.find('[name=keyword]');
        zoomField = dialog.find('[name=zoom]');
        centerField = dialog.find('[name=center]');
    },
    addFormReady: function(form, dialog) {
        fet('net.BMap net.BCityList', function() {
            var localCity = new BMap.LocalCity();
            localCity.get(function(city) {
                init(dialog, city);
            });
        });
    },
    editFormReady: function(form, dialog) {
        var addon = Addon.readAddon('map');
        fet('net.BMap net.BCityList', function() {
            var city = JSON.parse(addon.data.city),
                center = JSON.parse(addon.data.center),
                point = JSON.parse(addon.data.marker);
            cityField.val(addon.data.city);
            centerField.val(addon.data.center);
            markerField.val(addon.data.marker);
            keywordField.val(addon.data.keyword || '');
            zoomField.val(addon.data.zoom || city.level);
            form.find('[name=width]').val(addon.data.width);
            form.find('[name=height]').val(addon.data.height);
            init(dialog, city, center, point, addon.data.keyword, addon.data.zoom);
        });
    },
    beforeSubmit: function(form, dialog) {
        zoomField.val(map.getZoom());
        var center = map.getCenter();
        centerField.val(JSON.stringify({
            lng: center.lng,
            lat: center.lat
        }));
    },
    afterSubmit: function(form, dialog) {

    }
});
})();