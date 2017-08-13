<style>
    .map-toolbar {
        height: 22px;
        line-height: 22px;
    }
    .map-toolbar a,
    .map-toolbar span {
        vertical-align: middle;
    }

    #map-container {
        margin-top: 10px;
        height: 300px;
        border: solid 1px #CCC;
    }

    #map-city-list {
        margin-top: 10px;
        position: absolute;
        z-index: 99999;
        border: solid 1px #CCC;
        background: #FFF;
        overflow-x: hidden;
        overflow-y: scroll;
    }

    #map-search {
        width: 355px;
        float: right;
        text-align: left;
        position: relative;
    }
    #map-search-input {
        float: left;
        margin-right: 5px;
        width: 297px;
    }
    #map-search-submit {
        float: left;
        margin-right: 0;
    }

    #map-search-loading {
        position: absolute;
        left: 285px;
        top: 3px;
        display: block;
        width: 16px;
        height: 16px;
        background: url(images/loading.gif) no-repeat center center;
    }

    #map-search-result {
        position: absolute;
        z-index: 99999;
        background: #FFFFFF;
        border: 1px solid #999999;
        max-height: 100px;
        overflow-x: hidden;
        overflow-y: auto;
        display: none;
        text-align: center;
    }
    #map-search-result ul {

    }
    #map-search-result li {
        text-align: left;
        height: 22px;
        line-height: 22px;
        padding: 0 4px;
        background: #FFF;
        cursor: default;
    }
    #map-search-result li.odd {
        background: #D2E9FA;
    }
</style>

<div class="bk_8"></div>
<div id="mapBox">
    <form>
        <table width="95%" border="0" cellspacing="0" cellpadding="0">
            <tbody>
            <tr>
                <td>
                    <textarea style="display: none" data-role="data"><?=$data?></textarea>
                    <input type="hidden" name="center" value="" />
                    <input type="hidden" name="marker" value="" />
                    <input type="hidden" name="zoom" value="" />
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
                    <div id="map-container"></div>
                    <div class="bk_5"></div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
</div>
<div class="bk_5"></div>

<script type="text/javascript" src="apps/activity/js/baidumap.js" ></script>