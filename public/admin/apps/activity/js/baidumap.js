(function($){
    var map, myGeo, marker,
        mapContainer, mapCity,
        mapSearchInput, mapSearchSubmit,
        mapSearchLoading, mapSearchResult,
        cityField, centerField, markerField, keywordField, zoomField;
    var dialog;

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
        map.enableScrollWheelZoom();
        map.enableKeyboard();
        map.enableInertialDragging();
        map.enableContinuousZoom();
        map.addControl(ctrlNav);
        center = createPoint(center || city.center);
        marker = createMarker(createPoint(point || city.center), title || city.name);
        map.addEventListener('click', function(e) {
            panToPosition(e);
        });
        setTimeout(function() {
            map.centerAndZoom(center, parseInt(level || city.level));
            map.addOverlay(marker);

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
                container : "map-city-list",
                map : map
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
            if(!ct.IE) $('#_script_bmaplib_citylist_').remove();

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
                    if(localResult.city){
                        mapCity.text(localResult.city);
                    }
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
            mapSearchResult.css({
                width: mapSearchInput.outerWidth() - 2,
                left: mapSearchInput.position().left,
                top: mapSearchInput.position().top + mapSearchInput.outerHeight(true) - 1
            });
        }, 200);
    }

    function loadPoint(point, zoom){
        if(point){
            myGeo.getLocation(new BMap.Point(point.lng, point.lat), function(result){
                if (result){
                    city = {
                        center: {lng: point.lng, lat: point.lat},
                        level: zoom,
                        name: result.addressComponents.city
                    };
                    init(dialog, city);
                }else{
                    var localCity = new BMap.LocalCity();
                    localCity.get(function(city) {
                        init(dialog, city);
                    });
                }
            });
        }else{
            var localCity = new BMap.LocalCity();
            localCity.get(function(city) {
                init(dialog, city);
            });
        }
        return true;
    }

    var baiduMap = {
        load: function() {
            var point = $('#point').val();
            var address = $('#address').val();
            var zoom = 12;
            dialog = $('#mapBox');
            mapSearchInput = dialog.find('#map-search-input');
            mapSearchSubmit = dialog.find('#map-search-submit');
            mapSearchLoading = dialog.find('#map-search-loading').hide();
            mapSearchResult = dialog.find('#map-search-result').hide();

            cityField = dialog.find('[name=city]');
            markerField = dialog.find('[name=marker]');
            keywordField = dialog.find('[name=keyword]');
            zoomField = dialog.find('[name=zoom]');
            centerField = dialog.find('[name=center]');

            myGeo = new BMap.Geocoder();

            if(address){
                mapSearchInput.val(address);
                if(!point){
                    myGeo.getPoint(address, function(result){
                        if (result){
                            point = {lng: result.lng, lat: result.lat};
                        }else{
                            point = null;
                        }
                        loadPoint(point, zoom);
                    });
                    return false;
                }
            }
            if(point){
                var pointArr = point.split(',');
                if(pointArr.length == 3){
                    point = {lng: pointArr[0], lat: pointArr[1]};
                    zoom = pointArr[2];
                }else if(pointArr.length == 2){
                    point = {lng: pointArr[0], lat: pointArr[1]};
                }else{
                    point = null;
                }
            }
            loadPoint(point, zoom);
        },
        beforeSubmit: function() {
            zoomField.val(map.getZoom());
            var center = map.getCenter();
            centerField.val(JSON.stringify({
                lng: center.lng,
                lat: center.lat
            }));
        }
    }
    window.baiduMap = baiduMap;
})(jQuery);