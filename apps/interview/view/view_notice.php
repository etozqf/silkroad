<?php $this->display('header');?>
<?php $workflowid = table('category', $catid, 'workflowid');?>
<script type="text/javascript" src="tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="tiny_mce/editor.js"></script>
<script type="text/javascript" src="js/related.js"></script>
<div class="bk_8"></div>
<div class="tag_1">
    <?php $this->display('view_header');?>
</div>

<div class="bk_10"></div>
<div class="f_l">
    <form id="notice_add" action="?app=interview&controller=interview&action=notice" method="POST">
        <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
            <input type="hidden" name="contentid" id="contentid" value="<?=$contentid?>">
            <tr>
                <td>
                    <textarea name="notice" id="notice" style="width:350px;height:200px"><?=$notice?></textarea>
                </td>
            </tr>
        </table>
        <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
            <tr>
                <th width="30"></th>
                <td width="60">
                    <input type="submit" value="保存" class="button_style_2" style="float:left"/>
                </td>
                <td style="color:#444;text-align:left">&nbsp;</td>
            </tr>
        </table>
    </form>
</div>
<div class="clear"></div>
<script language="JavaScript">
    $(function(){
        $('#notice').editor('mini',{forced_root_block : ''});
        $('#notice_add').ajaxForm(function(json){
            if (json.state) ct.tips("保存成功");
            else ct.error(json.error);
        });
    });
</script>
<?php $this->display('footer');?>