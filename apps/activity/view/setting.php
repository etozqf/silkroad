<?php $this->display('header', 'system');?>
<div class="bk_8"></div>
<form id="activity_setting" method="POST" action="?app=activity&controller=setting&action=index">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>活动设置</caption>
        <tr>
            <th width="120">验证码：</th>
            <td><input type="radio" name="setting[seccode]" value="1" class="radio" <?php if ($setting['seccode'] == 1) echo 'checked';?>/> 开启 &nbsp; <input type="radio" name="setting[seccode]" value="0" class="radio" <?php if ($setting['seccode'] == 0) echo 'checked';?>/> 关闭</td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td  class="t_c"><input type="submit" class="button_style_2" value="保存" /></td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    $(function(){
        $('#activity_setting').ajaxForm(function (response) {
            ct.tips('保存成功');
        });
    });
</script>
<?php $this->display('footer', 'system');?>