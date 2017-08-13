<?php $this->display('header', 'system');?>
<!--selectlist-->
<script src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>
<link rel="stylesheet" href="<?=IMG_URL?>js/lib/list/style.css">

<div class="bk_8"></div>
<form id="vote_setting" method="POST" action="?app=vote&controller=setting&action=index">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>投票设置</caption>
        <tr>
            <th width="120">省份限制：</th>
            <td>
                <select name="setting[area]" multiple="multiple">
                    <?php
                        $area_list = array('北京', '河北', '山西', '辽宁', '吉林', '江苏', '浙江', '安徽', '福建', '江西', '黑龙江',
                         '山东', '河南', '湖北', '湖南', '广东', '海南', '四川', '贵州', '云南', '陕西', '甘肃', '青海', '天津', '上海',
                         '重庆', '内蒙古', '西藏', '新疆', '宁夏', '广西', '香港', '澳门');
                        foreach($area_list as $item):
                    ?>
                    <option value="<?php echo $item;?>"<?php if(strpos($setting['area'], $item)!==false):?> selected="selected"<?php endif;?>><?php echo $item;?></option>
                    <?php endforeach;?>
                </select>
            </td>
        <tr>
            <th>&nbsp;</th>
            <td  class="t_c"><input type="submit" class="button_style_2" value="保存" /></td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    $(function(){
        $('#vote_setting').ajaxForm(function (response) {
            ct.tips('保存成功');
        });
        $('select').selectlist();
    });
</script>
<?php $this->display('footer', 'system');?>