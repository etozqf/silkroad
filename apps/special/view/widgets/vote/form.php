<style type="text/css">
.lh_24 {line-height:24px;}
.table_form tr td {padding:2px 0;}
th.bdr_3 {border-left: 1px solid #D0E6EC !important;}
</style>
<form>
	<input type="hidden" name="select_type" value="1" />
	<input type="hidden" name="contentid" value="<?php echo $data['contentid'];?>"/>
	<input type="hidden" name="created" value="<?php echo $vote['created'];?>" />
	<input type="hidden" name="url" value="<?php echo $vote['url'];?>" />

	<input type="hidden" name="vote[modelid]" value="8">
	<input type="hidden" name="vote[status]" value="<?php echo $vote['mininterval'];?>">
	<input type="hidden" name="vote[mininterval]" value="24" />
	<input type="hidden" name="vote[catid]" value="<?php echo $vote['catid'];?>" />
	<input type="hidden" name="vote[title]" value="<?php echo $vote['title'];?>" />
	<input type="hidden" name="vote[color]" value="<?php echo $vote['color'];?>" />
	<input type="hidden" name="vote[type]" value="<?php echo $vote['type'];?>" />
	<input type="hidden" name="vote[maxoptions]" value="<?php echo $vote['maxoptions'];?>" />
	<input type="hidden" name="vote[display]" value="<?php echo $vote['display'];?>" />
	<input type="hidden" name="vote[thumb_width]" value="<?php echo $vote['thumb_width'];?>" />
	<input type="hidden" name="vote[thumb_height]" value="<?php echo $vote['thumb_height'];?>" />
	<input type="hidden" name="vote[thumb]" value="<?php echo $vote['thumb'];?>" />


	<div class="tabs">
	<ul target="tbody.fortabs">
		<li class="active">选择</li>
		<li>创建</li>
	</ul>
	</div>
	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_list tab">
		<tr><td><button type="button">选择投票</button></td></tr>
	</table>
	<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_add tab">
		<tr><td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_form">
				<tr>
					<th width="60"><span class="c_red">*</span> 栏目：</th>
					<td>
                        <input data-role="catid" name="catid" width="150"
                               initUrl="?app=system&controller=category&action=name&catid=%s"
                               url="?app=system&controller=category&action=cate&dsnid=&catid=%s"
                               paramVal="catid"
                               paramTxt="name"
                               ending="1"
                               value=""
                               alt="选择栏目"
                                />
					</td>
				</tr>
				<tr>
					<th><span class="c_red">*</span> 标题：</th>
					<td><?=element::title('title')?></td>
				</tr>
				<tr>
					<th>类型：</th>
					<td class="lh_24">
                        <label><input onclick="$(this).parent().siblings('span').css('visibility', 'hidden');" name="type" type="radio" value="radio" class="checkbox_style" onclick="$('#maxoptions_span').hide();" checked="checked" /> 单选</label>
                        <label><input onclick="$(this).parent().siblings('span').css('visibility', 'visible');" name="type" type="radio" class="checkbox_style" value="checkbox" onclick="$('#maxoptions_span').show();" /> 多选</label>
						<span id="maxoptions_span" style="visibility:hidden;">最多可选  <input id="maxoptions" name="maxoptions" value="" type="text" size="2"/> 项 </span>
					</td>
				</tr>
			</table>
		</td></tr>
		<tr><td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" >
				<tr>
					<th width="60" style="color:#077AC7;font-weight:normal;" class="t_r"><span class="c_red">*</span> 选项：</th>
					<td>
						<table id="vote_options" width="652" border="0" cellspacing="0" cellpadding="0" class="table_info">
							<thead>
								<tr>
									<th width="30" class="bdr_3">排序</th>
									<th width="250">选项</th>
									<th width="106">链接</th>
									<th width="106">图片</th>
									<th width="60">初始票数</th>
									<th width="30">删</th>
								</tr>
							</thead>
							<tbody id="options"></tbody>
						</table>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
                        <div class="mar_l_8 mar_5" style="margin-top:10px; margin-bottom:10px;">
						    <button class="button_style_1" onclick="option.add()" name="add_option_btn" type="button">增加选项</button>
					    </div>
                    </td>
				</tr>
                <tr>
                    <th>模式：</th>
                    <td>
                        <label><input onclick="$(this).parent().siblings('span').css('visibility', 'hidden');" name="display" type="radio" value="list" class="checkbox_style" checked="checked" /> 普通模式</label>
                        <label><input onclick="$(this).parent().siblings('span').css('visibility', 'visible');" name="display" type="radio" value="thumb" class="checkbox_style" /> 评选模式</label>
                        <span style="visibility:hidden;">
                            图片大小：<input type="text" placeholder="宽" name="thumb_width" size="3" value="" /> x <input type="text" placeholder="高" name="thumb_height" size="3" value="" /> px
                        </span>

                        <div class="bk_5"></div>
                    </td>
                </tr>
			</table>
		</td></tr>
	</table>
</form>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.tablesorter.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.pagination.js"></script>
<!--vote-->
<script type="text/javascript" src="<?=ADMIN_URL?>apps/vote/js/option.js"></script>
<!--vote-->
</script>
