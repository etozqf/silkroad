<div class="bk_8"></div>
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
			<tr>
				<td>
                    <div>
                        <label>宽度：<input type="text" name="width" value="520" size="5" /></label>&nbsp;&nbsp;
                        <label>高度：<input type="text" name="height" value="340" size="5" /></label>
                    </div>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<div class="bk_5"></div>