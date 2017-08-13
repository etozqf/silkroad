<style type="text/css">
.weibo-content-empty td {
    line-height: 335px;
    text-align: center;
}
.weibo-content-action td {
    padding: 10px 10px 0 10px;
}
.weibo-content-action td .button_style_4 {
    margin: 0;
}
.weibo-content-area td {
    padding: 0;
}
.weibo-item-list {
    height: 335px;
    overflow-y: scroll;
    margin-top: 10px;
    padding: 10px;
    border: solid #CCC;
    border-width: 1px 0 1px;
}
.mod-weibo .weibo-btn-remove {
    position: absolute;
    right: 5px;
    top: 50%;
    margin-top: -8px;
    display: block;
    width: 16px;
    height: 16px;
    cursor: pointer;
}
.table_args {}
.table_args tr{line-height:24px;}
.table_args tr td{}

.qq_style {
    width: 36px;
    height: 36px;
    float: left;
    margin-right: 10px;
    border: 3px solid #FFFFFF;
}
.hide {display: none;}
.qq_style_cur {
    border: 3px solid #A8DE86;
}
.weibo_color_input {
    width: 60px;
}
</style>
<div class="tabs">
    <ul target="tbody.method">
        <li>微博内容</li>
        <li>微博直播</li>
        <li>微博秀</li>
    </ul>
</div>
<form>
    <input type="hidden" name="method" value="<?=$data['method']?>" />
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
        <!-- 微博内容 -->
        <tbody class="method">
            <tr class="weibo-content-action">
                <td>
                    <button class="button_style_4" data-role="pick">选取微博内容</button>
                </td>
            </tr>
            <tr class="weibo-content-area">
                <td>
                    <div class="weibo-item-list">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="weibo-content">
                            <tr>
                                <td>
                                    <textarea style="display: none;"><?=json_encode(isset($data['items']) ? $data['items'] : array())?></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </tbody>

        <!-- 微博直播 -->
		<tbody class="method">
			<tr>
				<th width="80">微博服务商：</th>
				<td>
					<select data-role="live-provider" name="data[provider]">
                        <option value="qq" <?php if($data['provider'] == 'qq') echo 'selected'; ?>>腾讯</option>
						<option value="sina" <?php if($data['provider'] == 'sina') echo 'selected'; ?>>新浪</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>代码生成器：</th>
                <td data-role="generator" style="padding-top:10px; padding-bottom: 10px;"></td>
			</tr>
			<tr>
				<th>调用代码：</th>
				<td>
					<textarea name="data[code]" cols="45" rows="10"><?php echo $data['code'];?></textarea>
				</td>
			</tr>
		</tbody>

        <!-- 微博秀 -->
        <tbody class="method">
            <tr>
                <th width="80">微博服务商：</th>
                <td>
                    <select data-role="show-provider" name="data[provider]">
                        <option value="qq" <?php if($data['provider'] == 'qq') echo 'selected'; ?>>腾讯</option>
                        <option value="sina" <?php if($data['provider'] == 'sina') echo 'selected'; ?>>新浪</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>代码生成器：</th>
                <td data-role="generator" style="padding-top:10px; padding-bottom: 10px;"></td>
            </tr>
            <tr>
                <th>调用代码：</th>
                <td>
                    <textarea name="data[code]" cols="45" rows="10"><?php echo $data['code'];?></textarea>
                </td>
            </tr>
        </tbody>
	</table>
</form>

<div class="bk_5"></div>
<script type="text/template" id="tpl-weibo-live-sina">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <th width="60">关键词：</th>
        <td><input type="text" name="sina[tags]" value="<?php echo $data['sina']['tags']?$data['sina']['tags']:'cmstop';?>"/></td>
        </tr>
        <tr>
        <th>样式：</th>
        <td><input type="text" name="sina[skin]" value="<?php echo $data['sina']['skin']?$data['sina']['skin']:1;?>" size="4"/> 值为1到4</td>
        </tr>
        <tr>
        <th>显示图片：</th>
        <td><input type="text" name="sina[isShowPic]" value="<?php echo $data['sina']['isShowPic']?$data['sina']['isShowPic']:1;?>" size="4"/> 1为显示，2为不显示</td>
        </tr>
        <tr>
        <th>宽度：</th>
        <td><input type="text" name="sina[width]" value="<?php echo $data['sina']['width']?$data['sina']['width']:300;?>" size="4"/> 200~1024</td>
        </tr>
        <tr>
        <th>高度：</th>
        <td><input type="text" name="sina[height]" value="<?php echo $data['sina']['height']?$data['sina']['height']:500;?>" size="4"/> 75 ~ 800</td>
        </tr>
        </table>
</script>
<script type="text/template" id="tpl-weibo-live-qq">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <input type="hidden" name="qq[appkey]" value="<?php echo $qq_key;?>" />
        <tr>
            <th width="60">关键词：</th>
            <td><input type="text" name="qq[tags]" value="<?php echo $data['qq']['tags']?$data['qq']['tags']:'cmstop';?>"/></td>
        </tr>
        <tr>
            <th>宽度：</th>
            <td><input type="text" name="qq[width]" value="<?php echo $data['qq']['width']?$data['qq']['width']:'300';?>" size="4"<?php if($data['qq']['autowidth']):?> disabled="disabled"<?php endif;?> /> 255-1024像素</td>
        </tr>
        <tr>
            <th>高度：</th>
            <td><input type="text" name="qq[height]" value="<?php echo $data['qq']['height']?$data['qq']['height']:550;?>" size="4"/> 300-800像素</td>
        </tr>
        <tr>
            <th></th>
            <td><input type="checkbox" name="qq[autowidth]" value="1"<?php if($data['qq']['autowidth']):?> chekced="checked"<?php endif;?> />宽度自适应网页</td>
        </tr>
        <tr>
            <th style="vertical-align: top;">颜色：</th>
            <td>
                <table>
                    <tr>
                        <td><input class="is_custom" type="radio" name="qq[is_custom]" value="0"<?php if($data['qq']['is_custom']!=1):?> checked="checked"<?php endif;?> />默认颜色</td>
                    </tr>
                    <tr class="custom0"<?php if($data['qq']['is_custom']==1):?> style="display:none"<?php endif;?>>
                        <td>
                            <div class="qq_style<?php if($data['qq']['style']==0):?> qq_style_cur<?php endif;?>" style="background:#BCE7ED;">
                            </div>
                            <input type="radio" class="hide" name="qq[style]" value="0"<?php if($data['qq']['style']==0):?>checked="checked"<?php endif;?> />
                            <div class="qq_style<?php if($data['qq']['style']==1):?> qq_style_cur<?php endif;?>" style="background:#363636;">
                            </div>
                            <input type="radio" class="hide" name="qq[style]" value="1"<?php if($data['qq']['style']==1):?>checked="checked"<?php endif;?> />
                            <div class="qq_style<?php if($data['qq']['style']==2):?> qq_style_cur<?php endif;?>" style="background:#BDE493;">
                            </div>
                            <input type="radio" class="hide" name="qq[style]" value="2"<?php if($data['qq']['style']==2):?>checked="checked"<?php endif;?> />
                            <div class="qq_style<?php if($data['qq']['style']==3):?> qq_style_cur<?php endif;?>" style="background:#FFFAD7;">
                            </div>
                            <input type="radio" class="hide" name="qq[style]" value="3"<?php if($data['qq']['style']==3):?>checked="checked"<?php endif;?> />
                        <td>
                    </tr>
                    <tr>
                        <td><input class="is_custom" type="radio" name="qq[is_custom]" value="1"<?php if($data['qq']['is_custom']==1):?> checked="checked"<?php endif;?> />自定义颜色</td>
                    </tr>
                    <tr class="custom1"<?php if($data['qq']['is_custom']!=1):?> style="display:none"<?php endif;?>>
                        <td>
                            <table>
                                <tr><td>标题栏色</td><td><input type="text" class="weibo_color_input" name="qq[custom_color0]" value="<?php echo empty($data['qq']['custom_color0']) ? "#FFFFFF" : $data['qq']['custom_color0'];?>" /></td><td>背景色</td><td><input type="text" class="weibo_color_input" name="qq[custom_color1]" value="<?php echo empty($data['qq']['custom_color1']) ? "#FFFFFF" : $data['qq']['custom_color1'];?>" /></td></tr>
                                <tr><td>分割线色</td><td><input type="text" class="weibo_color_input" name="qq[custom_color2]" value="<?php echo empty($data['qq']['custom_color2']) ? "#FFFFFF" : $data['qq']['custom_color2'];?>" /></td><td>边框色</td><td><input type="text" class="weibo_color_input" name="qq[custom_color3]" value="<?php echo empty($data['qq']['custom_color3']) ? "#FFFFFF" : $data['qq']['custom_color3'];?>" /></td></tr>
                                <tr><td>超链接色</td><td><input type="text" class="weibo_color_input" name="qq[custom_color4]" value="<?php echo empty($data['qq']['custom_color4']) ? "#FFFFFF" : $data['qq']['custom_color4'];?>" /></td><td>主字色</td><td><input type="text" class="weibo_color_input" name="qq[custom_color5]" value="<?php echo empty($data['qq']['custom_color5']) ? "#FFFFFF" : $data['qq']['custom_color5'];?>" /></td></tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th>显示图片：</th>
            <td><input type="radio" name="qq[thumb]" value="2"<?php if($data['qq[thumb]']==2 || empty($data['qq[thumb]'])):?> checked="checked"<?php endif;?> />显示缩略图 <input type="radio" name="qq[thumb]" value="0"<?php if($data['qq[thumb]']==2):?> checked="checked"<?php endif;?> />显示为图标</td>
        </tr>
        <tr>
            <th>发表框：</th>
            <td><input type="radio" name="qq[post]" value="5"<?php if($data['qq[post']==5 || empty($data['qq[post]'])):?> checked="checked"<?php endif;?> />居上 <input type="radio" name="qq[post]"<?php if($data['qq[post]']==13):?> value="13"<?php endif;?> />居下 <input type="radio" name="qq[post]" value="1"<?php if($data['qq[post]']==1):?> checked="checked"<?php endif;?> />不显示</td>
        </tr>
    </table>
</script>
<script type="text/template" id="tpl-weibo-empty">
    <tr class="weibo-content-empty">
        <td>
            暂无微博
        </td>
    </tr>
</script>
<script type="text/template" id="tpl-weibo-item">
    <tr>
        <td>
            <div class="mod-weibo">
                <input type="hidden" name="items[{%index%}][index]" value="{%index%}" />
                <input type="hidden" name="items[{%index%}][platform]" value="{%platform%}" />
                <input type="hidden" name="items[{%index%}][profile]" value="{%profile%}" />
                <input type="hidden" name="items[{%index%}][avatar]" value="{%avatar%}" />
                <input type="hidden" name="items[{%index%}][username]" value="{%username%}" />
                <input type="hidden" name="items[{%index%}][vtype]" value="{%vtype%}" />
                <textarea name="items[{%index%}][text]" style="display:none;">{%text%}</textarea>
                <textarea name="items[{%index%}][image]" style="display:none;">{%JSON.stringify(image)%}</textarea>
                <input type="hidden" name="items[{%index%}][url]" value="{%url%}" />
                <input type="hidden" name="items[{%index%}][time]" value="{%time%}" />
                <span title="删除" class="weibo-btn-remove" data-role="remove"><img src="<?=ADMIN_URL?>images/del.gif" alt=""></span>
                <div class="weibo-avatar">
                    <a class="weibo-icon weibo-icon-{%platform%}" href="{%profile%}" target="_blank"></a>
                    <a class="weibo-head" href="{%profile%}" target="_blank">
                        <img src="{%avatar%}" />
                    </a>
                </div>
                <div class="weibo-message">
                        <span class="weibo-user">
                            <a href="{%profile%}" target="_blank">{%username%}</a>
                            {%if vtype%}<a href="{%profile%}" target="_blank" class="weibo-icon weibo-icon-{%vtype%}"></a>{%endif%}
                            :
                        </span>
                    <span class="weibo-content">{%text%}</span>
                    {%if image%}
                    <div class="weibo-media">
                        <div class="weibo-image">
                            <a href="{%image[1]%}" target="_blank"><img src="{%image[0]%}" alt=""></a>
                        </div>
                    </div>
                    {%endif%}
                    <span class="weibo-info">
                        <a class="weibo-time"{%if url%} href="{%url%}" target="_blank"{%else%} href="javascript:void(0);"{%endif%}>{%time%}</a>
                    </span>
                </div>
            </div>
        </td>
    </tr>
</script>