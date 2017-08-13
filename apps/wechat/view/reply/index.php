<?php $this->display('head');?>
<div id="submenu" class="wechat-reply-submenu wechat-submenu f_l"></div>
<div id="reply">
	<div id="RoutePanel" style="display:none;">
		<div id="player"></div>
		<div class="wechat-reply f_l">
			<!-- search form -->
			<form id="routeSearch" class="wechat-search wechat-search-route" onsubmit="return false;">
				<table>
					<tr>
						<td>
							<input type="text" name="starttime" class="input_calendar" style="width:120px;" />
						</td>
						<td> ~ </td>
						<td>
							<input type="text" name="endtime" class="input_calendar" style="width:120px;" />
						</td>
						<td>
							<div class="search">
								<input type="text" name="keyword" value="" placeholder="规则名" />
								<input type="text" name="tag" value="" placeholder="关键词" />
								<a href="javascript:;">搜索</a>
							</div>
						</td>
					</tr>
				</table>
				<button></button>
			</form>

			<div class="bk_10"></div>
			<button  id="route-add" class="button_style_2 mar_l_8">添加</button>
			<div class="bk_10"></div>
			<!-- list -->
			<div id="routeList" class="wechat-route-list mar_l_8">
				<table class="table_list" border="0" cellspacing="0" cellpadding="0" width="80%">
					<thead>
						<tr>
							<th width="35" class="bdr_3"><input type="checkbox" id="route-list-selectall" /></th>
							<th width="50">ID</th>
							<th width="30%">规则名称</th>
							<th width="40%">关键词</th>
							<th width="90">回复类型</th>
							<th width="100">管理操作</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
				<div class="wechat-route-table-footer">
					<button id="route-table-refresh" class="button_style_1">刷新</button>
					<button id="route-table-delete" class="button_style_1 mar_l_8">删除选中</button>
					<div id="pagination" class="pagination f_r"></div>
				</div>
			</div>
		</div>
		<!-- post form -->
		<form id="routePost" class="wechat-route-post" style="display:none;">
			<div id="routePostFormName" class="wechat-route-post-title">新规则</div>
				<div class="wechat-route-post-panel">
					<table>
						<tr>
							<td><div class="wechat-route-post-label">规则名</div></td>
							<td><input type="text" name="ruleName" /></td>
						</tr>
						<tr>
							<td></td>
							<td><div class="wechat-route-post-info">规则名最多60个字</div></td>
						</tr>
					</table>
				</div>
				<div class="wechat-route-post-panel">
					<div class="wechat-route-post-label">关键词<a id="addTag" class="f_r mar_r_8" href="javascript:;">添加关键字</a></div>
					<div id="addTagForm" class="wechat-route-post-tag" style="display:none;">
						<input type="text" name="keyword" placeholder="添加关键字" />
					</div>
					<ul class="wechat-route-tag"></ul>
				</div>
				<div class="wechat-route-post-panel">
					<div class="wechat-route-post-label f_l">回复</div>
					<label class="f_r mar_r_8"><input type="checkbox" name="replyAll" value="1" class="mar_r_8" />回复全部</label>
					<div class="clear"></div>
				</div>
				<div class="wechat-route-post-panel">
					<div class="wechat-route-post-button">
						<a id="reply-text" class="wechat-route-button-text" href="javascript:;" title="文字"></a>
						<a id="reply-picture" data-service-only class="wechat-route-button-picture" href="javascript:;" title="图片"></a>
						<a id="reply-voice" data-service-only class="wechat-route-button-voice" href="javascript:;" title="语音"></a>
						<a id="reply-video" data-service-only class="wechat-route-button-video" href="javascript:;" title="视频"></a>
						<a id="reply-list" class="wechat-route-button-list" href="javascript:;" title="图文"></a>
					</div>
					<div id="routePostContent" class="wechat-route-post-reply">
						<input type="hidden" name="content" value="" />
					</div>
				</div>
				<div class="wechat-route-post-footer">
					<a class="wechat-route-button-canel f_r mar_l_8" href="javascript:;"></a>
					<a class="wechat-route-button-ok f_r" href="javascript:;"></a>
				</div>
			</table>
		</form>
	</div>
	<div id="IndexPanel" style="display:none;">
		<div class="bk_30" style="clear:none;"></div>
		<form id="contextEnginForm" method="post" action=".">
			<table class="table_form" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<th width="100"><span class="c_red">*</span>状态：</th>
					<td width="250">
						<label><input type="radio" name="state" value="1"<?php if($context['state']==1):?> checked="checked"<?php endif;?> /> 开启</label>
						<label><input type="radio" name="state" value="0"<?php if($context['state']==0):?> checked="checked"<?php endif;?> /> 关闭</label>
					</td>
				</tr>
				<tr>
					<th><span class="c_red">*</span>模型开启：</th>
					<td>
						<?php foreach (loader::model('admin/model', 'system')->select('alias IN ("article", "picture", "video")', 'modelid, name') as $item):?>
						<label><input type="radio" name="modelid" value="<?php echo $item['modelid'];?>"<?php if(in_array($item['modelid'], $context['modelid'])):?> checked="checked"<?php endif;?> /> <?php echo $item['name'];?></label>
						<?php endforeach;?>
					</td>
				</tr>
				<tr>
					<th><span class="c_red">*</span>栏目开启：</th>
					<td>
						<input type="hidden" id="category" name="category" value="<?php echo $context['category'];?>"
						class="placetree"
						url="?app=system&controller=category&action=cate&catid=%s"
						initUrl="?app=system&controller=category&action=name&catid=%s"
						paramVal="catid"
						paramTxt="name"
						multiple="multiple" />
					</td>
				</tr>
				<!-- <tr>
					<th><span class="c_red">*</span>缩略图尺寸：</th>
					<td>
						<label>宽<input type="text" name="width" value="<?php echo $context['width'];?>" style="width:56px;" /> px</label>
						<label class="mar_l_8">高<input type="text" name="height" value="<?php echo $context['height'];?>" style="width:56px;" /> px </label>
					</td>
				</tr>
				<tr>
					<th><span class="c_red">*</span>生成模型：</th>
					<td>
						<label><input type="radio" name="" value="" /> 单图文</label>
						<label><input type="radio" name="" value="" /> 多图文</label>
					</td>
				</tr> -->
				<tr>
					<th></th>
					<td><button class="button_style_2">保存</button></td>
				</tr>
			</table>
		</form>
	</div>
</div>
<script id="route-list-template" type="text/template">
	<tr id="row_{id}">
		<td class="t_c"><input type="checkbox" /></td>
		<td class="t_c">{id}</td>
		<td><a href="javascript:;" class="edit">{name}</a></td>
		<td data-role="tags">{tags}</td>
		<td class="t_c" data-role="content"></td>
		<td class="t_c">
			<a href="javascript:;" class="edit"><img src="images/edit.gif" /></a>
            <a href="javascript:;" class="delete"><img src="images/delete.gif" /></a>
		</td>
	</tr>
</script>