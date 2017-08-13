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
    <form id="picture_add" action="?app=interview&controller=interview&action=picture" method="POST">
        <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
            <input type="hidden" name="contentid" id="contentid" value="<?=$contentid?>">
            <tr>
                <th width="80">组图ID：</th>
                <td width="150">
                    <input id="picture" type="text" size="15" class="c_gray" value="<?php if(isset($picture) && $picture){echo $picture;}else{echo '请输入组图ID';}?>" name="picture" onfocus="if(this.value=='请输入组图ID'){this.value='';$(this).addClass('c_gray');}else{$(this).removeClass('c_gray');}" onblur="if(this.value==''){this.value='请输入组图ID';$(this).addClass('c_gray');}else{$(this).removeClass('c_gray');if(isNaN(this.value)){ct.warn('请输入数字ID号！');this.value='请输入组图ID';}}" onchange="interview.picture_load(this.value);" />
                    <a href="javascript:;" onclick="select_picture(3, '')" title="选择组图"><img src="images/add_1.gif" title="选择组图"/></a></td>
                <td><input type="submit"  id="submit"   name="publish" id="publish" value="保存"  class="button_style_2"/></td>
            </tr>
        </table>
    </form>
</div>
<div class="clear"></div>

<div id="picture_group" style="margin:10px;width:560px;">
    <?php if($picture) $this->display('picture');?>
</div>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.jcarousellite.js"></script>
<script language="JavaScript">
    $(function(){
        $('#picture_add').ajaxForm(function(json){
            if (json.state) ct.tips("保存成功");
            else ct.error(json.error);
        });
    });
    function select_picture(apiid, keywords)
    {
        relateddata = related_data(apiid);
        var result = showModalDialog('?app=system&controller=related&action=picture', window, "dialogWidth:450px;dialogHeight:465px;help:0;status:0;center:yes;scroll:no");
        if(result != null)
        {
            $("#picture").val(result);
            interview.picture_load(result);
        }
    }

    window.setTimeout(function(){
        $("#pictures").jCarouselLite({
            btnNext: ".nextimg",
            btnPrev: ".previmg",
            circular: true,
            auto: 2000,
            speed: 1000,
            scroll: 1,
            visible: 4,
            start: 0
        });
    },500);
</script>
<?php $this->display('footer');?>