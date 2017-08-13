<?php $this->display('header');?>
<script>
    var repeatcheck = <?=$repeatcheck?> + 0;
</script>
<style type="text/css">
.check-repeat-panel .icon {background: url(<?=IMG_URL?>js/lib/dropdown/bg.gif) no-repeat scroll 0 -50px transparent;  margin-right: 8px;  width: 16px;height: 20px;float: left;}
</style>
<form name="item_edit" id="item_edit" method="POST" action="?app=<?=$app?>&controller=<?=$app?>&action=edit">
  <input type="hidden" name="modelid" id="modelid" value="<?=$modelid?>">
  <input type="hidden" name="contentid" id="contentid" value="<?=$contentid?>">
  <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
    <caption>项目基本情况(以下带*全部为必填项)</caption>
    <tr>
      <th width="120"><span class="c_red">*</span> 栏目：</th>
      <td>  
        <?=element::category('catid', 'catid', $catid)?>
      </td>
    </tr>
    <tr>
      <th width="120"><span class="c_red">*</span> <?=element::tips('请填写项目名称,参考格式:地名+项目主要内容,示例:宁夏石景山高新科技开发区热处理加工中心')?>项目名称：</th>
      <td><?=element::title('title', $title, $color, 80)?>
            </td>
    </tr>
    <tr>
      <th width="120"><span class="c_red">*</span> <?=element::tips('选择项目归属的国家或地区,当项目属于多国合作,或跨多个国家或省份时,值填写最主要的国家和地区')?>所属国家或地区：
            </th>
      <td>
          <input type="text" id="countryid" name="country" value="<?=$countryname?>" size="100" readonly/><button type="button" onclick="selectCountry()" class="button_style_1">选择国家或地区</button>
          <?php foreach ($country as $k => $v): ?>
            <input class="item-country" type="hidden" name="countryid[]" value="<?=$v?>">
          <?php endforeach ?>
      </td>
    </tr>
        <tr>
            <th width="120">
              <span class="c_red">*</span>
              <?=element::tips('选择所属的行业信息')?>
              所属行业：
            </th>
            <td>
                <?php foreach ($itemfields['trade'] as $k => $v): ?>
                    <label style="margin-left:10px;"><input type="checkbox" class="checkbox_style" value="<?=$k;?>" id="tradeid" name="tradeid[]" <?=in_array($k,$trade)? 'checked':'';?>><?=$v['zh']?></label>
                <?php endforeach ?>
            </td>
        </tr>
        <tr>
            <th width="120">
              <span class="c_red">*</span>
              <?=element::tips('多选,请选择投资方式')?>
              投资方式：
            </th>
            <td>
                <?php foreach ($itemfields['investmenttype'] as $k => $v): ?>
                    <label style="margin-left:10px;"><input type="checkbox" class="checkbox_style" value="<?=$k;?>" id="investmenttypeid" name="investmenttypeid[]" <?=in_array($k,$investmenttype)? 'checked':'';?>><?=$v['zh']?></label>
                <?php endforeach ?>
            </td>
        </tr>
        <tr>
            <th width="120">
              <?=element::tips('项目性质是指项目的支持情况,不清楚时选"其他"')?>
              项目性质：
            </th>
            <td>
                <select id="SelectProjectStatus" name="itemnatureid" style="height:26px;width:150px;border: solid 1px #e2e2e2;">
                <option>--------请选择--------</option>
                <?php foreach ($itemfields['itemnature'] as $k => $v): ?>
                          <option value="<?=$k;?>" <?=$k==$itemnatureid? 'selected':'';?>><?=$v['zh']; ?></option>
                <?php endforeach ?>
                </select>
            </td>
        </tr>
        <tr>
            <th width="120">
                <?=element::tips('多选,请项目类型(主要指融资方式)')?>
                项目类型：
            </th>
            <td>
                <?php foreach ($itemfields['itemtype'] as $k => $v): ?>
                    <label style="margin-left:10px;"><input type="checkbox" class="checkbox_style" value="<?=$k;?>" id="itemtypeid" name="itemtypeid[]" <?=in_array($k,$itemtype)? 'checked':'';?>><?=$v['zh']?></label>
                <?php endforeach ?>
            </td>
        </tr>
        <tr>
            <th width="120"><span class="c_red">*</span><?=element::tips('输入该项目发布的时间')?> 项目发布时间：</th>
            <td width="170"><input type="text" name="starttime" id="starttime" class="input_calendar" value="<?=$starttime?>" size="20"/></td>
        </tr>
        <tr>
            <th width="120"><span class="c_red">*</span><?=element::tips('项目发布消息的有效截止日期,如果已经完成或者结束招标或者不清楚的项目填写当前时间')?>发布有效期：</th>
            <td><input type="text" name="stoptime" id="stoptime" class="input_calendar" value="<?=$stoptime?>" size="20"/></td>
        </tr>
  </table>
  <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8" >
    <caption>项目详情</caption>
    <tr>
      <th width="120">&nbsp;&nbsp;</th>
      <td width="540" style="padding-left:9px;position:relative">
        <span class="c_red">*项目介绍</span><textarea name="description" id="description" style="visibility:hidden;height:450px;width:630px;"><?=$description?></textarea>
      </td>
    </tr>
        <tr>
            <th width="120"><?=element::tips('请输入原始资料中项目吸引投资的金额,单位币种一起填写,例:2000万美元')?>项目金额：</th>
            <td><input type="text" name="itemsum" id="itemsum" value="<?=$itemsum?>" size="100" maxlength="120" /></td>
        </tr>
        <tr>
            <th width="120"><?=element::tips('请输入原始资料中项目相关数额,单位币种一起填写,不清楚可留空')?>年收入：</th>
            <td><input type="text" name="income" id="income" value="<?=$income?>" size="100" maxlength="120" /></td>
        </tr>
        <tr>
            <th width="120"><?=element::tips('标准化投资回收期,请填写数字,请原始值转化为月,可以有小数点')?>投资回收期：</th>
            <td><input type="text" name="paybacktime" id="paybacktime" value="<?=$paybacktime?>" size="100" maxlength="120" /></td>
        </tr>
  </table>
  <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
     <caption>联系方式</caption>
        <tr>
            <th width="120"><span class="c_red">*</span><?=element::tips('原始项目信息的发布机构主要指项目一手信息来源方,可以是某政府商务厅,也可是企业本身')?>发布机构：</th>
            <td><input type="text" name="publishorganization" id="publishorganization" value="<?=$publishorganization?>" size="100" maxlength="120" /></td>
        </tr>
        <tr>
            <th width="120"><?=element::tips('项目业主单位或发起单位,多个发起方使用"/"分割')?>项目单位：</th>
            <td><input type="text" name="itemunit" id="itemunit" value="<?=$itemunit?>" size="100" maxlength="120" /></td>
        </tr>
        <tr>
            <th width="120"><span class="c_red">*</span><?=element::tips('填写项目联系人或单位名称,多个人/隔开,示例:董**/韩**')?>项目联系人：</th>
            <td><input type="text" name="itemcontacts" id="itemcontacts" value="<?=$itemcontacts?>" size="100" maxlength="120" /></td>
        </tr>
        <tr>
            <th width="120"><span class="c_red">*</span><?=element::tips('填写电话号码,注意添加国家号码,多个电话用/分开,示例:010-8988****/0952-8978****')?>电话：</th>
            <td><input type="text" name="phone" id="phone" value="<?=$phone?>" size="100" maxlength="120" /></td>
        </tr>
        <tr>
            <th width="120"><?=element::tips('填写电子邮箱地址,多个电子邮箱用/分开,示例:189********@126.com/ssy***@163.com')?>传真：</th>
            <td><input type="text" name="faxes" id="faxes" value="<?=$faxes?>" size="100" maxlength="120" /></td>
        </tr>
        <tr>
            <th width="120"><?=element::tips('填写传真号码,注意添加国家号码,多个电话用/分开,示例:010-8988****/0952-8978****')?>电子邮件：</th>
            <td><input type="text" name="email" id="email" value="<?=$email?>" size="100" maxlength="120" /></td>
        </tr>
        <tr>
            <th width="120"><?=element::tips('填写地址,多个地址使用/隔开,示例:北京海淀****/上海浦东*****')?>地址：</th>
            <td><input type="text" name="address" id="address" value="<?=$address?>" size="100" maxlength="120" /></td>
        </tr>
        <tr>
            <th width="120"><?=element::tips('填写邮编,与地址对应,多个邮编使用/隔开,示例:100000/')?>邮编：</th>
            <td><input type="text" name="postcode" id="postcode" value="<?=$postcode?>" size="100" maxlength="120" /></td>
        </tr>
        <tr>
            <th><?=element::tips('输入词组后以回车确认增加')?>编辑：</th>
            <td>
                <input type="text" name="editor" id="editor" value="<?=$editor?>" size="9" class="source_input" paramVal="editor" paramTxt="editor" anytext="3" />
            </td>
        </tr>
        <tr>
          <th><?=element::tips('权重将决定文章在哪里显示和排序')?> 权重：</th>
          <td>
          <?=element::weight($weight, $myweight);?>
          </td>
        </tr>
        <tr>
            <th class="vtop">相关项目图集：</th>
            <td colspan="3"><?=element::related($contentid)?></td>
        </tr>
  </table>

    <?php
    $catid && $allowcomment = table('category', $catid, 'allowcomment');
    ?>
  <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th width="120">状态：</th>
            <td colspan="3">
            <?php $is_draft = true; ?>
            <?php $workflowid = table('category', $catid, 'workflowid'); ?>
            <?php if (priv::aca($app, $app, 'publish')): ?>
                <?php $is_draft = false; ?>
                <label><input type="radio" name="status" id="status" value="6" checked="checked"/> 发布</label> &nbsp;
            <?php elseif ($workflowid && priv::aca($app, $app, 'approve')): ?>
                <?php $is_draft = false; ?>
                <label><input type="radio" name="status" id="status" value="3" checked="checked"/> 送审</label> &nbsp;
            <?php endif; ?>
                <label><input type="radio" name="status" id="status" value="1"<?php if ($is_draft): ?> checked="checked"<?php endif; ?>/> 草稿</label>
            </td>
        </tr>
  </table>

  <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
    <tr>
      <th width="120"></th>
      <td width="60">
          <!-- 项目发布时间 -->
        <input type="hidden" name="oldpublished" value="<?=$published?>" />
        <input type="hidden" name="published" value="<?=$published?>" />
        <input type="submit" value="保存" class="button_style_2" style="float:left"/>
      </td>
      <td style="color:#444;text-align:left">按Ctrl+S键保存</td>
    </tr>
  </table>
</form>

<div class="ct_tips warning success-msg" id="repeat-tips">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <th colspan="2" style="text-align:center"><strong>检测到相似数据:</strong></th>
        </tr>
        <tr>
            <td colspan="2" class="t_c" data-role="buttons">
                <button id="goonwhenrepeat" class="button_style_4" type="button">继续保存</button>
                <button id="cancelwhenrepeat" class="button_style_2" type="button">取消</button>
            </td>
        </tr>
    </table>
</div>

<?php $this->display('content/success', 'system');?>

<link href="<?=IMG_URL?>js/lib/autocomplete/style.css" rel="stylesheet" type="text/css" />
<link href="<?=IMG_URL?>js/lib/colorInput/style.css" rel="stylesheet" type="text/css" />
<script src="<?=IMG_URL?>js/lib/cmstop.autocomplete.js" type="text/javascript"></script>
<script src="<?=IMG_URL?>js/lib/cmstop.colorInput.js" type="text/javascript"></script>

<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<script src="tiny_mce/tiny_mce.js" type="text/javascript"></script>
<script src="tiny_mce/editor.js" type="text/javascript"></script>
<script src="js/related.js" type="text/javascript"></script>
<script src="apps/system/js/field.js" type="text/javascript" ></script>
<?php
foreach(explode('|', UPLOAD_FILE_EXTS) as $exts) {
  $allow .=  '*.'.$exts.';';
}
?>
<script type="text/javascript">
var get_tag = <?php echo $get_tag;?> + 0;
$(function(){
  $("#multiUp").uploader({
      script : '?app=editor&controller=filesup&action=upload',
      fileDataName : 'multiUp',
      fileExt : '<?=$allow?>',
      buttonImg : 'images/multiup.gif',
      complete:function(response, data){
        response =(new Function("","return "+response))();
        if(response.state) {
          tinyMCE.activeEditor.execCommand('mceInsertContent', false, response.code);
          ct.ok(response.msg);
        } else {
          ct.error(response.msg);
        }
      }
  });
    $.ajax({type: 'GET', url: 'http://o2h.cmstop.com/cmstop.o2h.js', success: function() {
        var wordUp = document.getElementById('wordUp');
        if (! wordUp) return false;
        wordUp.style.visibility = 'visible';
        new O2H(wordUp, {
            uploadComplete:function(html){
                tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
            },
            uploadError:function(err){
                ct.error(err);
            }
        });
    }, dataType: 'script', ifModified: false, cache: true});
});

$('#description').editor();

//选择国家或地区时将选中的国家或地区添加到表单中
var selectCountry=function(){
  var data = $('#countryid').val();
  ct.form('选择国家或地区','?app=item&controller=item&action=selectcountry&data='+data,360,400,
      function(json){
        $('#countryid').val('');
        $('.item-country').remove();
          $.each(json,function(i,v)
          {
            var countryid = $('#countryid').val();
            if(!countryid){
              $('#countryid').val(v);
            }else{
              $('#countryid').val(countryid+','+v);
            }
            $('#countryid').after('<input class="item-country" type="hidden" name="countryid[]" value="'+i+'">');
          });
          return true;
      },
      function(){return true});
};
</script>
<?php $this->display('footer');