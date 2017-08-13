<?php $this->display('header');?>
<?php $workflowid = table('category', $catid, 'workflowid');?>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<script type="text/javascript" src="apps/survey/js/question.js"></script>
<script type="text/javascript" src="apps/survey/js/option.js"></script>
<div class="bk_8"></div>
<div class="tag_1">
	<ul class="tag_list">
<?php if(priv::aca('survey', 'survey', 'view')):?>
		<li><a href="?app=survey&controller=survey&action=view&contentid=<?=$contentid?>">查看</a></li>
<?php endif;?>
		<li><a href="?app=survey&controller=question&action=index&contentid=<?=$contentid?>" class="s_3">设计表单</a></li>
<?php if(priv::aca('survey', 'report', 'index')):?>
		<li><a href="?app=survey&controller=report&action=index&contentid=<?=$contentid?>">分析报告</a></li>
<?php endif;?>
	</ul>
<?php if(priv::aca('survey', 'survey', 'edit')):?>
  <input type="button" name="edit" value="修改" class="button_style_1" onclick="content.edit(<?=$contentid?>)"/>
<?php endif;?>
<?php if($status == 6 && priv::aca('survey', 'html', 'show')):?>
  <input type="button" name="createhtml" value="生成" class="button_style_1" onclick="content.createhtml(<?=$contentid?>)"/>
<?php endif;?>
<?php if ($status < 6 && priv::aca('survey', 'survey', 'publish')):?>
  <input type="button" name="publish" value="发布" class="button_style_1" onclick="content.publish(<?=$contentid?>)"/>
<?php endif;?>
<?php if ($status == 6 && priv::aca('survey', 'survey', 'data_clear')):?>
  <input type="button" name="clear" value="清空" class="button_style_1" onclick="survey.data_clear(<?=$contentid?>)" />
<?php endif;?>
<?php if ($status > 0 && priv::aca('survey', 'survey', 'remove')):?>
  <input type="button" name="remove" value="删除" class="button_style_1" onclick="content.remove(<?=$contentid?>)"/>
<?php endif;?>
<?php if ($status == 0):?>
	<?php if (priv::aca('survey', 'survey', 'delete')):?>
  <input type="button" name="delete" value="删除" class="button_style_1" onclick="content.del(<?=$contentid?>)"/>
	<?php endif;?>
    <?php if (priv::aca('survey', 'survey', 'restore')):?>
  <input type="button" name="restore" value="还原" class="button_style_1" onclick="content.restore(<?=$contentid?>)"/>
	<?php endif;?>
<?php endif;?>
<?php if (($status == 1 || ($workflowid && $status == 2)) && priv::aca('survey', 'survey', 'approve')):?>
  <input type="button" name="approve" value="送审" class="button_style_1" onclick="content.approve(<?=$contentid?>)"/>
<?php endif;?>
<?php if ($status == 3):?>
	<?php if (priv::aca('survey', 'survey', 'pass')):?>
  <input type="button" name="pass" value="通过" class="button_style_1" onclick="content.pass(<?=$contentid?>)"/>
	<?php endif;?>
    <?php if (priv::aca('survey', 'survey', 'reject')):?>
  <input type="button" name="reject" value="退稿" class="button_style_1" onclick="content.reject(<?=$contentid?>)"/>
	<?php endif;?>
<?php endif;?>
<?php if (priv::aca('survey', 'survey', 'move')):?>
  <input type="button" name="move" value="移动" class="button_style_1" onclick="content.move(<?=$contentid?>)"/>
<?php endif;?>
<?php if (priv::aca('survey', 'survey', 'reference')):?>
  <input type="button" name="reference" value="引用" class="button_style_1" onclick="content.reference(<?=$contentid?>)"/>
<?php endif;?>	
<input type="button" name="note" value="批注" class="button_style_1" onclick="content.note(<?=$contentid?>)"/>
	<input type="button" name="version" value="版本" class="button_style_1" onclick="content.version(<?=$contentid?>)"/>
	<input type="button" name="log" value="日志" class="button_style_1" onclick="content.log(<?=$contentid?>)"/>
</div>

<div class="mar_l_8 mar_t_8">
	<div id="question-toolbox" class="question-toolbox box_10" style="width:98%; background:#fff;">
		<h3><span class="b">表单工具</span>　点击按钮建立相应表单</h3>
		<ul id="form_type_choose" class="inline list_3 pad_10 lh_24">
			<li><a href="javascript:;" onclick="question.add(<?=$contentid?>, 'radio')">单选</a></li>
			<li><a href="javascript:;" onclick="question.add(<?=$contentid?>, 'checkbox')">多选</a></li>
			<li><a href="javascript:;" onclick="question.add(<?=$contentid?>, 'text')">单行文本</a></li>
			<li><a href="javascript:;" onclick="question.add(<?=$contentid?>, 'textarea')">多行文本</a></li>
			<li><a href="javascript:;" onclick="question.add(<?=$contentid?>, 'select')">下拉菜单</a></li>
			<li><a href="javascript:;" onclick="question.add(<?=$contentid?>, 'file')">附件上传</a></li>
		</ul>
		<div class="clear"></div>
	</div>
	<form id="question_sort" name="question_sort" method="POST" action="?app=survey&controller=question&action=sort">
		<input name="contentid" type="hidden" value="<?=$contentid?>" />
		<div id="questions">
<?php foreach ($question as $k=>$r):?>
		<?php $this->assign('n', $k+1); $this->assign($r); $this->display('question/'.$r['type'].'/form');?>
<?php endforeach;?>
		</div>
		<div style="padding:10px;"><input name="sort" type="submit" value="保存并发布" class="button_style_1" style="display:none;"></div>
	</form>
</div>
<script type="text/javascript">
$("#question_sort").ajaxForm('question.sort_submit');
question.init();

$(window).scroll(function(){
	var questionWidth = ($(window).width() - 8) * 0.98;
	if ($(document).scrollTop() > 42) {
		$('#question-toolbox').css('position', 'fixed').css('top', '0').css('width', questionWidth + 'px');
		$('#questions').css('margin-top', '72px');
	} else {
		$('#question-toolbox').css('position', 'static').css('width', '98%');
		$('#questions').css('margin-top', '0');
	}
});
</script>
<?php $this->display('footer', 'system');?>