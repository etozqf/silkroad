<form action="">
    <input type="hidden" name="tabs" value="0" />
    <input type="hidden" name="center" value="" />
    <input type="hidden" name="marker" value="" />
    <input type="hidden" name="zoom" value="" />
    <div class="mod-tabs">
        <div class="tabs-trigger" data-tabs="triggers">
            <ul>
                <li class="tabs-trigger-item" data-tabs="trigger-item">标记地图位置</li>
            </ul>
        </div>
        <div class="tabs-content" data-tabs="contents">
            <div class="tabs-content-item" data-tabs="content-item">
                <div class="map-toolbar">
                    <div id="map-search">
                        <input id="map-search-input" name="keyword" type="text" size="30" />
                        <button id="map-search-submit" class="button_style_1">搜索</button>
                        <span id="map-search-loading"></span>
                        <div id="map-search-result"></div>
                    </div>
                    <input type="hidden" name="city" value="" />
                    <span id="map-city">载入中</span>
                    <a href="javascript:void(0);" id="map-change-city">切换城市</a>
                    <div id="map-city-list"></div>
                </div>
                <div id="map-container">

                </div>
                <div class="bk_5"></div>
                <div>
                    <label>宽度：<input type="text" name="width" value="630" size="5" /></label>&nbsp;&nbsp;
                    <label>高度：<input type="text" name="height" value="420" size="5" /></label>
                </div>
            </div>
        </div>
    </div>
</form>