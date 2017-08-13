var TEMPLATE = {
PANEL:'<div id="diy-panel">'+
	'<div class="diy-panel-header"></div>'+
	'<div class="diy-panel-body">'+
		'<div class="diy-panel-history">'+
			'<span class="diy-panel-dock" name="back" tip="撤销:right+30:middle"></span>'+
			'<span class="diy-panel-dock" name="forward" tip="重做:right+6:middle"></span>'+
		'</div>'+
		'<div class="diy-panel-dock" name="command" tip="保存:right+6:middle"></div>'+
		'<div class="diy-panel-dock" name="preview" tip="预览:right+6:middle"></div>'+
		'<div class="diy-panel-split"></div>'+
		'<div class="diy-panel-dock" name="theme" tip="风格:right+6:middle"></div>'+
		'<div class="diy-panel-dock" name="layout" tip="布局:right+6:middle"></div>'+
		'<div class="diy-panel-dock" name="widget" tip="模块:right+6:middle"></div>'+
		'<div class="diy-panel-dock" name="setting" tip="设置:right+6:middle"></div>'+
		'<div class="diy-panel-split"></div>'+
		'<div class="diy-panel-dock" name="page" tip="页面:right+6:middle"></div>'+
	'</div>'+
	'<div class="diy-panel-footer"></div>'+
'</div>',
PLACEMENT_PANEL:
'<div id="diy-placement-panel">'+
	'<i class="diy-icon diy-icon-edit" action="editWidget" title="编辑"></i>'+
	'<i class="diy-icon diy-icon-style" action="setStyle" title="样式"></i>'+
	'<i class="diy-icon diy-icon-title" action="setTitle" title="标题"></i>'+
	'<i class="diy-icon diy-icon-share" action="shareWidget" title="共享"></i>'+
	'<i class="diy-icon diy-icon-publish" action="pubWidget" title="发布"></i>'+
    '<i class="diy-icon diy-icon-visible" action="setVisible" title="隐藏/显示"></i>'+
	'<i class="diy-icon diy-icon-remove" action="remove" title="移除"></i>'+
'</div>',
NEW_FRAME:
'<form><div style="text-align:center;padding:10px;">'+
	'列数：<input class="diy-size-7" type="text" name="column" value="2" />'+
'</div></form>',
SAVE_AS:
'<form><div style="padding: 10px 15px;line-height:28px">'+
	'名称：<input value="" type="text" name="name" /><br/>'+
	'缩略图：<input value="" type="text" name="thumb" class="diy-size-15" />'+
'</div></form>',
SET_UI:
'<div class="head"><span></span></div>'+
'<form><div class="set-ui"><ul></ul></div></form>',
SET_PAGE:
'<form>'+
	'<table width="95%" border="0" cellspacing="0" cellpadding="0">'+
		'<tbody>'+
			'<tr>'+
				'<th width="80">文本：</th>'+
				'<td>'+
                    '<p>'+
                        '<label>字体：<input name="font-family" type="text" class="diy-size-20 wfamily" /></label>'+
                    '</p><p>'+
                        '<label>大小：<input name="font-size" type="text" class="diy-size-5 wfontsize" /></label>'+
                        '<label>颜色：<input name="color" class="diy-size-7 color-input" /></label>'+
                        '<label>间距：<input name="letter-spacing" class="diy-size-5" /></label>'+
					'</p><p>' +
                        '<span>样式：</span>'+
                        '<label><input name="font-bold" type="checkbox" value="font-weight:bold" /> 加粗</label>'+
                        '<label><input name="font-italic" type="checkbox" value="font-style:italic" /> 斜体</label>'+
                        '<label><input name="font-underline" type="checkbox" value="text-decoration:underline" /> 下划线</label>' +
                    '</p>'+
				'</td>'+
			'</tr>'+
			'<tr>'+
				'<th>链接：</th>'+
				'<td>'+
                    '<p>'+
                        '<label>字体：<input name="a-font-family" type="text" class="diy-size-20 afamily" /></label>'+
                    '</p><p>'+
                        '<label>大小：<input name="a-font-size" type="text" class="diy-size-5 afontsize" /></label>'+
                        '<label>颜色：<input name="a-color" class="diy-size-7 color-input" /></label>'+
                        '<label>间距：<input name="a-letter-spacing" class="diy-size-5" /></label>' +
                    '</p><p>' +
                        '<span>样式：</span>'+
                        '<label><input name="a-font-bold" type="checkbox" value="font-weight:bold" /> 加粗</label>'+
                        '<label><input name="a-font-italic" type="checkbox" value="font-style:italic" /> 斜体</label>'+
                        '<label><input name="a-font-underline" type="checkbox" value="text-decoration:underline" /> 下划线</label>' +
                    '</p>'+
				'</td>'+
			'</tr>'+
			'<tr>'+
				'<th>背景：</th>'+
				'<td>'+
					'颜色：<input name="background-color" class="color-input diy-size-7" value="" /><br />'+
					'图像：<input name="background-image" type="text" class="image-input" value="" /><br />'+
					'重复：<select name="background-repeat">'+
						'<option></option>'+
						'<option value="repeat">平铺</option>'+
						'<option value="no-repeat">不平铺</option>'+
						'<option value="repeat-x">横向平铺</option>'+
						'<option value="repeat-y">纵向平铺</option>'+
					'</select><br />'+
					'位置：<input name="background-position-x" type="text" class="diy-size-5" value="" /> - <input name="background-position-y" type="text" class="diy-size-5" value="" />'+
				'</td>'+
			'</tr>'+
		'</tbody>'+
	'</table>'+
'</form>',
SET_FRAME:
'<div class="tabs">'+
	'<ul target=".item-option">'+
		'<li>外框样式</li>'+
		'<li>标题样式</li>'+
	'</ul>'+
'</div>'+
'<form>'+
'<div class="set-style">'+
	'<div class="item-option">'+
		'<input type="hidden" class="theme-input" name="frame-theme" />'+
		'<div class="item-detail"><b>+</b>自定义</div>'+
		'<table width="470" border="0" cellspacing="0" cellpadding="0">'+
			'<tbody>'+
				'<tr>'+
					'<th width="80">高度：</th>'+
					'<td>'+
						'<input name="frame-height" type="text" class="diy-size-5" />'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>宽度：</th>'+
					'<td>'+
						'<input name="frame-width" type="text" class="diy-size-5" />'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>居中：</th>'+
					'<td><input name="frame-center" type="checkbox" value="1" /></td>'+
				'</tr>'+
				'<tr>'+
					'<th>边框：</th>'+
					'<td>'+
						'<p>厚度：<span style="display:inline-block">'+
							'<label style="float:right"><input type="checkbox" name="frame-border-all-width" /> 分别设置</label>'+
							'<label><span>上：</span><input name="frame-border-top-width" type="text" class="diy-size-3" value=""></label>'+
							'<label><span>右：</span><input name="frame-border-right-width" type="text" class="diy-size-3" value=""></label>'+
							'<br />'+
							'<label><span>下：</span><input name="frame-border-bottom-width" type="text" class="diy-size-3" value=""></label>'+
							'<label><span>左：</span><input name="frame-border-left-width" type="text" class="diy-size-3" value=""></label>'+
						'</span></p>'+
						'<p>样式：<span style="display:inline-block">'+
							'<label style="float:right"><input type="checkbox" name="frame-border-all-style" /> 分别设置</label>'+
							'<label><span>上：</span>'+
								'<select name="frame-border-top-style">'+
									'<option></option>'+
									'<option value="none">无样式</option>'+
									'<option value="solid">实线</option>'+
									'<option value="dotted">点线</option>'+
									'<option value="dashed">虚线</option>'+
								'</select>'+
							'</label>'+
							'<label><span>右：</span>'+
								'<select name="frame-border-right-style">'+
									'<option></option>'+
									'<option value="none">无样式</option>'+
									'<option value="solid">实线</option>'+
									'<option value="dotted">点线</option>'+
									'<option value="dashed">虚线</option>'+
								'</select>'+
							'</label>'+
							'<br />'+
							'<label><span>下：</span>'+
								'<select name="frame-border-bottom-style">'+
									'<option></option>'+
									'<option value="none">无样式</option>'+
									'<option value="solid">实线</option>'+
									'<option value="dotted">点线</option>'+
									'<option value="dashed">虚线</option>'+
								'</select>'+
							'</label>'+
							'<label><span>左：</span>'+
								'<select name="frame-border-left-style">'+
									'<option></option>'+
									'<option value="none">无样式</option>'+
									'<option value="solid">实线</option>'+
									'<option value="dotted">点线</option>'+
									'<option value="dashed">虚线</option>'+
								'</select>'+
							'</label>'+
						'</span></p>'+
						'<p>颜色：<span style="display:inline-block">'+
							'<label style="float:right"><input type="checkbox" name="frame-border-all-color" /> 分别设置</label>'+
							'<label><span>上：</span><input name="frame-border-top-color" class="color-input diy-size-7" value="" /></label>'+
							'<label><span>右：</span><input name="frame-border-right-color" class="color-input diy-size-7" value="" /></label>'+
							'<br />'+
							'<label><span>下：</span><input name="frame-border-bottom-color" class="color-input diy-size-7" value="" /></label>'+
							'<label><span>左：</span><input name="frame-border-left-color" class="color-input diy-size-7" value="" /></label>'+
						'</span></p>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>边距：</th>'+
					'<td>'+
						'<label style="float:right"><input type="checkbox" name="frame-margin-all" /> 分别设置</label>'+
						'<label><span>上：</span><input name="frame-margin-top" type="text" class="diy-size-3" value=""></label>'+
						'<label><span>右：</span><input name="frame-margin-right" type="text" class="diy-size-3" value=""></label>'+
						'<br />'+
						'<label><span>下：</span><input name="frame-margin-bottom" type="text" class="diy-size-3" value=""></label>'+
						'<label><span>左：</span><input name="frame-margin-left" type="text" class="diy-size-3" value=""></label>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>背景：</th>'+
					'<td>'+
						'颜色：<input name="frame-background-color" class="color-input diy-size-7" value="" /><br />'+
						'图像：<input name="frame-background-image" type="text" class="image-input diy-size-20" value="" /><br />'+
						'重复：<select name="frame-background-repeat">'+
							'<option></option>'+
							'<option value="repeat">平铺</option>'+
							'<option value="no-repeat">不平铺</option>'+
							'<option value="repeat-x">横向平铺</option>'+
							'<option value="repeat-y">纵向平铺</option>'+
						'</select><br />'+
						'位置：<input name="frame-background-position-x" type="text" class="diy-size-5" value="" /> - <input name="frame-background-position-y" type="text" class="diy-size-5" value="" />'+
					'</td>'+
				'</tr>'+
			'</tbody>'+
		'</table>'+
	'</div>'+
	'<div class="item-option">'+
		'<input type="hidden" class="theme-input" name="title-theme" />'+
		'<div class="item-detail"><b>+</b>自定义</div>'+
		'<table width="470" border="0" cellspacing="0" cellpadding="0">'+
			'<tbody>'+
				'<tr>'+
					'<th width="80">文本：</th>'+
					'<td>'+
                        '<p>'+
                            '<label>字体：<input name="title-w-font-family" type="text" class="diy-size-20 wfamily" /></label>'+
                        '</p><p>'+
                            '<label>大小：<input name="title-w-font-size" type="text" class="diy-size-5 wfontsize" /></label>'+
                            '<label>颜色：<input name="title-w-color" class="diy-size-7 color-input" /></label>'+
                            '<label>间距：<input name="title-w-letter-spacing" class="diy-size-5" /></label>'+
                        '</p><p>' +
                            '<span>样式：</span>'+
                            '<label><input name="title-w-font-bold" type="checkbox" value="font-weight:bold" /> 加粗</label>'+
                            '<label><input name="title-w-font-italic" type="checkbox" value="font-style:italic" /> 斜体</label>'+
                            '<label><input name="title-w-font-underline" type="checkbox" value="text-decoration:underline" /> 下划线</label>' +
                        '</p>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>链接：</th>'+
					'<td>'+
                        '<p>'+
                            '<label>字体：<input name="title-a-font-family" type="text" class="diy-size-20 afamily" /></label>'+
                        '</p><p>'+
                            '<label>大小：<input name="title-a-font-size" type="text" class="diy-size-5 afontsize" /></label>'+
                            '<label>颜色：<input name="title-a-color" class="diy-size-7 color-input" /></label>'+
                            '<label>间距：<input name="title-a-letter-spacing" class="diy-size-5" /></label>'+
                        '</p><p>' +
                            '<span>样式：</span>'+
                            '<label><input name="title-a-font-bold" type="checkbox" value="font-weight:bold" /> 加粗</label>'+
                            '<label><input name="title-a-font-italic" type="checkbox" value="font-style:italic" /> 斜体</label>'+
                            '<label><input name="title-a-font-underline" type="checkbox" value="text-decoration:underline" /> 下划线</label>' +
                        '</p>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>边距：</th>'+
					'<td>'+
						'<label style="float:right"><input type="checkbox" name="title-padding-all"  /> 分别设置</label>'+
						'<label><span>上：</span><input name="title-padding-top" type="text" class="diy-size-3" value=""></label>'+
						'<label><span>右：</span><input name="title-padding-right" type="text" class="diy-size-3" value=""></label>'+
						'<br />'+
						'<label><span>下：</span><input name="title-padding-bottom" type="text" class="diy-size-3" value=""></label>'+
						'<label><span>左：</span><input name="title-padding-left" type="text" class="diy-size-3" value=""></label>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>背景：</th>'+
					'<td>'+
						'颜色：<input name="title-background-color" class="color-input diy-size-7" value="" /><br />'+
						'图像：<input name="title-background-image" type="text" class="image-input diy-size-20" value="" /><br />'+
						'重复：<select name="title-background-repeat">'+
							'<option></option>'+
							'<option value="repeat">平铺</option>'+
							'<option value="no-repeat">不平铺</option>'+
							'<option value="repeat-x">横向平铺</option>'+
							'<option value="repeat-y">纵向平铺</option>'+
						'</select><br />'+
						'位置：<input name="title-background-position-x" type="text" class="diy-size-5" value="" /> - <input name="title-background-position-y" type="text" class="diy-size-5" value="" />'+
					'</td>'+
				'</tr>'+
			'</tbody>'+
		'</table>'+
	'</div>'+
'</div></form>',
SET_WIDGET:
'<div class="tabs">'+
	'<ul target=".item-option">'+
		'<li>外框样式</li>'+
		'<li>标题样式</li>'+
		'<li>内容样式</li>'+
	'</ul>'+
'</div>'+
'<form>'+
'<div class="set-style">'+
	'<div class="item-option">'+
		'<input type="hidden" class="theme-input" name="widget-theme" />'+
		'<div class="item-detail"><b>+</b>自定义</div>'+
		'<table width="470" border="0" cellspacing="0" cellpadding="0">'+
			'<tbody>'+
				'<tr>'+
					'<th width="80">文本：</th>'+
					'<td>'+
                        '<p>'+
                            '<label>字体：<input name="inner-w-font-family" type="text" class="diy-size-20 wfamily" /></label>'+
                        '</p><p>'+
                            '<label>大小：<input name="inner-w-font-size" type="text" class="diy-size-5 wfontsize" /></label>'+
                            '<label>颜色：<input name="inner-w-color" class="diy-size-7 color-input" /></label>'+
                            '<label>间距：<input name="inner-w-letter-spacing" class="diy-size-5" /></label>'+
                        '</p><p>' +
                            '<span>样式：</span>'+
                            '<label><input name="inner-w-font-bold" type="checkbox" value="font-weight:bold" /> 加粗</label>'+
                            '<label><input name="inner-w-font-italic" type="checkbox" value="font-style:italic" /> 斜体</label>'+
                            '<label><input name="inner-w-font-underline" type="checkbox" value="text-decoration:underline" /> 下划线</label>' +
                        '</p>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>链接：</th>'+
					'<td>'+
                        '<p>'+
                            '<label>字体：<input name="inner-a-font-family" type="text" class="diy-size-20 afamily" /></label>'+
                        '</p><p>'+
                            '<label>大小：<input name="inner-a-font-size" type="text" class="diy-size-5 afontsize" /></label>'+
                            '<label>颜色：<input name="inner-a-color" class="diy-size-7 color-input" /></label>'+
                            '<label>间距：<input name="inner-a-letter-spacing" class="diy-size-5" /></label>'+
                        '</p><p>' +
                            '<span>样式：</span>'+
                            '<label><input name="inner-a-font-bold" type="checkbox" value="font-weight:bold" /> 加粗</label>'+
                            '<label><input name="inner-a-font-italic" type="checkbox" value="font-style:italic" /> 斜体</label>'+
                            '<label><input name="inner-a-font-underline" type="checkbox" value="text-decoration:underline" /> 下划线</label>' +
                        '</p>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>高度：</th>'+
					'<td>'+
						'<input name="widget-height" type="text" class="diy-size-5" />'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>宽度：</th>'+
					'<td>'+
						'<input name="widget-width" type="text" class="diy-size-5" />'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>边框：</th>'+
					'<td>'+
						'<p>厚度：<span style="display:inline-block">'+
							'<label style="float:right"><input type="checkbox" name="inner-border-all-width" /> 分别设置</label>'+
							'<label><span>上：</span><input name="inner-border-top-width" type="text" class="diy-size-3" value=""></label>'+
							'<label><span>右：</span><input name="inner-border-right-width" type="text" class="diy-size-3" value=""></label>'+
							'<br />'+
							'<label><span>下：</span><input name="inner-border-bottom-width" type="text" class="diy-size-3" value=""></label>'+
							'<label><span>左：</span><input name="inner-border-left-width" type="text" class="diy-size-3" value=""></label>'+
						'</span></p>'+
						'<p>样式：<span style="display:inline-block">'+
							'<label style="float:right"><input type="checkbox" name="inner-border-all-style" /> 分别设置</label>'+
							'<label><span>上：</span>'+
								'<select name="inner-border-top-style">'+
									'<option></option>'+
									'<option value="none">无样式</option>'+
									'<option value="solid">实线</option>'+
									'<option value="dotted">点线</option>'+
									'<option value="dashed">虚线</option>'+
								'</select>'+
							'</label>'+
							'<label><span>右：</span>'+
								'<select name="inner-border-right-style">'+
									'<option></option>'+
									'<option value="none">无样式</option>'+
									'<option value="solid">实线</option>'+
									'<option value="dotted">点线</option>'+
									'<option value="dashed">虚线</option>'+
								'</select>'+
							'</label>'+
							'<br />'+
							'<label><span>下：</span>'+
								'<select name="inner-border-bottom-style">'+
									'<option></option>'+
									'<option value="none">无样式</option>'+
									'<option value="solid">实线</option>'+
									'<option value="dotted">点线</option>'+
									'<option value="dashed">虚线</option>'+
								'</select>'+
							'</label>'+
							'<label><span>左：</span>'+
								'<select name="inner-border-left-style">'+
									'<option></option>'+
									'<option value="none">无样式</option>'+
									'<option value="solid">实线</option>'+
									'<option value="dotted">点线</option>'+
									'<option value="dashed">虚线</option>'+
								'</select>'+
							'</label>'+
						'</span></p>'+
						'<p>颜色：<span style="display:inline-block">'+
							'<label style="float:right"><input type="checkbox" name="inner-border-all-color" /> 分别设置</label>'+
							'<label><span>上：</span><input name="inner-border-top-color" class="color-input diy-size-7" value="" /></label>'+
							'<label><span>右：</span><input name="inner-border-right-color" class="color-input diy-size-7" value="" /></label>'+
							'<br />'+
							'<label><span>下：</span><input name="inner-border-bottom-color" class="color-input diy-size-7" value="" /></label>'+
							'<label><span>左：</span><input name="inner-border-left-color" class="color-input diy-size-7" value="" /></label>'+
						'</span></p>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>外边距：</th>'+
					'<td>'+
						'<label style="float:right"><input type="checkbox" name="inner-margin-all" /> 分别设置</label>'+
						'<label><span>上：</span><input name="inner-margin-top" type="text" class="diy-size-3" value=""></label>'+
						'<label><span>右：</span><input name="inner-margin-right" type="text" class="diy-size-3" value=""></label>'+
						'<br />'+
						'<label><span>下：</span><input name="inner-margin-bottom" type="text" class="diy-size-3" value=""></label>'+
						'<label><span>左：</span><input name="inner-margin-left" type="text" class="diy-size-3" value=""></label>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>内边距：</th>'+
					'<td>'+
						'<label style="float:right"><input type="checkbox" name="inner-padding-all"  /> 分别设置</label>'+
						'<label><span>上：</span><input name="inner-padding-top" type="text" class="diy-size-3" value=""></label>'+
						'<label><span>右：</span><input name="inner-padding-right" type="text" class="diy-size-3" value=""></label>'+
						'<br />'+
						'<label><span>下：</span><input name="inner-padding-bottom" type="text" class="diy-size-3" value=""></label>'+
						'<label><span>左：</span><input name="inner-padding-left" type="text" class="diy-size-3" value=""></label>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>居中：</th>'+
					'<td><input name="widget-center" type="checkbox" value="1" /></td>'+
				'</tr>'+
				'<tr>'+
					'<th>背景：</th>'+
					'<td>'+
						'颜色：<input name="inner-background-color" class="diy-size-7 color-input" value="" /><br />'+
						'图像：<input name="inner-background-image" type="text" class="diy-size-25 image-input" value="" /><br />'+
						'重复：<select name="inner-background-repeat">'+
							'<option></option>'+
							'<option value="repeat">平铺</option>'+
							'<option value="no-repeat">不平铺</option>'+
							'<option value="repeat-x">横向平铺</option>'+
							'<option value="repeat-y">纵向平铺</option>'+
						'</select><br />'+
						'位置：<input name="inner-background-position-x" type="text" class="diy-size-5" value="" /> - <input name="inner-background-position-y" type="text" class="diy-size-5" value="" />'+
					'</td>'+
				'</tr>'+
			'</tbody>'+
		'</table>'+
	'</div>'+
	'<div class="item-option">'+
		'<input type="hidden" class="theme-input" name="title-theme" />'+
		'<div class="item-detail"><b>+</b>自定义</div>'+
		'<table width="470" border="0" cellspacing="0" cellpadding="0">'+
			'<tbody>'+
				'<tr>'+
					'<th width="80">文本：</th>'+
					'<td>'+
                        '<p>'+
                            '<label>字体：<input name="title-w-font-family" type="text" class="diy-size-20 wfamily" /></label>'+
                        '</p><p>'+
                            '<label>大小：<input name="title-w-font-size" type="text" class="diy-size-5 wfontsize" /></label>'+
                            '<label>颜色：<input name="title-w-color" class="diy-size-7 color-input" /></label>'+
                            '<label>间距：<input name="title-w-letter-spacing" class="diy-size-5" /></label>'+
                        '</p><p>' +
                            '<span>样式：</span>'+
                            '<label><input name="title-w-font-bold" type="checkbox" value="font-weight:bold" /> 加粗</label>'+
                            '<label><input name="title-w-font-italic" type="checkbox" value="font-style:italic" /> 斜体</label>'+
                            '<label><input name="title-w-font-underline" type="checkbox" value="text-decoration:underline" /> 下划线</label>' +
                        '</p>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>链接：</th>'+
					'<td>'+
                        '<p>'+
                            '<label>字体：<input name="title-a-font-family" type="text" class="diy-size-20 afamily" /></label>'+
                        '</p><p>'+
                            '<label>大小：<input name="title-a-font-size" type="text" class="diy-size-5 afontsize" /></label>'+
                            '<label>颜色：<input name="title-a-color" class="diy-size-7 color-input" /></label>'+
                            '<label>间距：<input name="title-a-letter-spacing" class="diy-size-5" /></label>'+
                        '</p><p>' +
                            '<span>样式：</span>'+
                            '<label><input name="title-a-font-bold" type="checkbox" value="font-weight:bold" /> 加粗</label>'+
                            '<label><input name="title-a-font-italic" type="checkbox" value="font-style:italic" /> 斜体</label>'+
                            '<label><input name="title-a-font-underline" type="checkbox" value="text-decoration:underline" /> 下划线</label>' +
                        '</p>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>边距：</th>'+
					'<td>'+
						'<label style="float:right"><input type="checkbox" name="title-padding-all"  /> 分别设置</label>'+
						'<label><span>上：</span><input name="title-padding-top" type="text" class="diy-size-3" value=""></label>'+
						'<label><span>右：</span><input name="title-padding-right" type="text" class="diy-size-3" value=""></label>'+
						'<br />'+
						'<label><span>下：</span><input name="title-padding-bottom" type="text" class="diy-size-3" value=""></label>'+
						'<label><span>左：</span><input name="title-padding-left" type="text" class="diy-size-3" value=""></label>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>背景：</th>'+
					'<td>'+
						'颜色：<input name="title-background-color" class="color-input diy-size-7" value="" /><br />'+
						'图像：<input name="title-background-image" type="text" class="image-input diy-size-20" value="" /><br />'+
						'重复：<select name="title-background-repeat">'+
							'<option></option>'+
							'<option value="repeat">平铺</option>'+
							'<option value="no-repeat">不平铺</option>'+
							'<option value="repeat-x">横向平铺</option>'+
							'<option value="repeat-y">纵向平铺</option>'+
						'</select><br />'+
						'位置：<input name="title-background-position-x" type="text" class="diy-size-5" value="" /> - <input name="title-background-position-y" type="text" class="diy-size-5" value="" />'+
					'</td>'+
				'</tr>'+
			'</tbody>'+
		'</table>'+
	'</div>'+
	'<div class="item-option">'+
		'<input type="hidden" class="theme-input" name="content-theme" />'+
		'<div class="item-detail"><b>+</b>自定义</div>'+
		'<table width="470" border="0" cellspacing="0" cellpadding="0">'+
			'<tbody>'+
				'<tr>'+
					'<th width="80">文本：</th>'+
					'<td>'+
                        '<p>'+
                            '<label>字体：<input name="content-w-font-family" type="text" class="diy-size-20 wfamily" /></label>'+
                        '</p><p>'+
                            '<label>大小：<input name="content-w-font-size" type="text" class="diy-size-5 wfontsize" /></label>'+
                            '<label>颜色：<input name="content-w-color" class="diy-size-7 color-input" /></label>'+
                            '<label>间距：<input name="content-w-letter-spacing" class="diy-size-5" /></label>'+
                        '</p><p>' +
                            '<span>样式：</span>'+
                            '<label><input name="content-w-font-bold" type="checkbox" value="font-weight:bold" /> 加粗</label>'+
                            '<label><input name="content-w-font-italic" type="checkbox" value="font-style:italic" /> 斜体</label>'+
                            '<label><input name="content-w-font-underline" type="checkbox" value="text-decoration:underline" /> 下划线</label>' +
                        '</p>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>链接：</th>'+
					'<td>'+
                        '<p>'+
                            '<label>字体：<input name="content-a-font-family" type="text" class="diy-size-20 afamily" /></label>'+
                        '</p><p>'+
                            '<label>大小：<input name="content-a-font-size" type="text" class="diy-size-5 afontsize" /></label>'+
                            '<label>颜色：<input name="content-a-color" class="diy-size-7 color-input" /></label>'+
                            '<label>间距：<input name="content-a-letter-spacing" class="diy-size-5" /></label>'+
                        '</p><p>'+
                            '<span>样式：</span>'+
                            '<label><input name="content-a-font-bold" type="checkbox" value="font-weight:bold" /> 加粗</label>'+
                            '<label><input name="content-a-font-italic" type="checkbox" value="font-style:italic" /> 斜体</label>'+
                            '<label><input name="content-a-font-underline" type="checkbox" value="text-decoration:underline" /> 下划线</label>' +
                        '</p>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>行高：</th>'+
					'<td>'+
						'<input name="content-line-height" type="text" class="diy-size-5" />'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>高度：</th>'+
					'<td>'+
						'<input name="content-height" type="text" class="diy-size-5" />'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>文字居中：</th>'+
					'<td><input name="content-text-center" type="checkbox" value="1" /></td>'+
				'</tr>'+
				'<tr>'+
					'<th>边距：</th>'+
					'<td>'+
						'<label style="float:right"><input type="checkbox" name="content-padding-all"  /> 分别设置</label>'+
						'<label><span>上：</span><input name="content-padding-top" type="text" class="diy-size-3" value=""></label>'+
						'<label><span>右：</span><input name="content-padding-right" type="text" class="diy-size-3" value=""></label>'+
						'<br />'+
						'<label><span>下：</span><input name="content-padding-bottom" type="text" class="diy-size-3" value=""></label>'+
						'<label><span>左：</span><input name="content-padding-left" type="text" class="diy-size-3" value=""></label>'+
					'</td>'+
				'</tr>'+
				'<tr>'+
					'<th>背景：</th>'+
					'<td>'+
						'颜色：<input name="content-background-color" class="color-input diy-size-7" value="" /><br />'+
						'图像：<input name="content-background-image" type="text" class="image-input diy-size-20" value="" /><br />'+
						'重复：<select name="content-background-repeat">'+
							'<option></option>'+
							'<option value="repeat">平铺</option>'+
							'<option value="no-repeat">不平铺</option>'+
							'<option value="repeat-x">横向平铺</option>'+
							'<option value="repeat-y">纵向平铺</option>'+
						'</select><br />'+
						'位置：<input name="content-background-position-x" type="text" class="diy-size-5" value="" /> - <input name="content-background-position-y" type="text" class="diy-size-5" value="" />'+
					'</td>'+
				'</tr>'+
			'</tbody>'+
		'</table>'+
	'</div>'+
'</div></form>',
SET_TITLE: 
'<div class="bk_8"></div>'+
'<form>'+
'<table width="95%" border="0" cellspacing="0" cellpadding="0">'+
	'<tbody>'+
		'<tr>'+
			'<th width="100">标题：</th>'+
			'<td><input name="text" type="text" class="diy-size-35" value="" /></td>'+
		'</tr>'+
		'<tr>'+
			'<th>链接地址：</th>'+
			'<td>'+
                '<input name="href" type="text" class="diy-size-35" value="" />'+
                ' <a class="fn-widget-morelist" href="javascript:;">使用更多链接</a>'+
            '</td>'+
		'</tr>'+
		'<tr>'+
			'<th>新窗口中打开：</th>'+
			'<td>'+
				'<input type="checkbox" name="newtab" value="1" />'+
			'</td>'+
		'</tr>'+
		'<tr>'+
			'<th>图标：</th>'+
			'<td><input name="img" type="text" class="image-input diy-size-15" value="" /></td>'+
		'</tr>'+
		'<tr>'+
			'<th>位置：</th>'+
			'<td>'+
				'<label><input type="radio" name="float" value="left" />浮左</label> '+
				'<label><input type="radio" name="float" value="center" />居中</label> '+
				'<label><input type="radio" name="float" value="right" />浮右</label> '+
				'<label>偏移：<input name="offset" type="text" class="diy-size-3" value="" /> px</label>'+
			'</td>'+
		'</tr>'+
		'<tr>'+
			'<th>字体：</th>'+
			'<td>'+
				'<label>大小：<input name="font-size" type="text" class="diy-size-3 wfontsize" value="" /></label>'+
				'<label>颜色：<input name="color" class="color-input diy-size-7" value="" /></label>'+
			'</td>'+
		'</tr>'+
        '<tr class="fn-widget-morelist">'+
            '<th>更多链接：</th>'+
            '<td>'+
                '<label><input type="checkbox" name="morelist.add" />添加</label>  '+
                '<label><input type="checkbox" name="morelist.titleurl" />使用标题链接</label>'+
            '</td>'+
        '</tr>'+
	'</tbody>'+
'</table></form>'
};