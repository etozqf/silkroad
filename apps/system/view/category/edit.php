<form name="edit" id="category_edit" method="POST" action="?app=system&controller=category&action=edit">
    <input name="catid" type="hidden" value="<?=$catid?>"/>
    <input name="parentid" type="hidden" value="<?=$parentid?>"/>
    <table border="0" cellspacing="0" cellpadding="0" class="table_form yyss">
        <tr>
            <th width="120">栏目ID：</th>
            <td><?=$catid?></td>
        </tr>
        <tr>
            <th width="120">栏目名：</th>
            <td><input type="text" name="name" id="name" value="<?=$name?>" size="30"/></td>
        </tr>
        <tr>
            <th>英文名：</th>
            <td><input type="text" name="alias" id="alias" value="<?=$alias?>" size="60"/></td>
        </tr>
        <tr>
            <th>栏目拼音：</th>
            <td><input type="text" name="pinyin" id="pinyin" value="<?=$pinyin?>" size="30"/></td>
        </tr>
        <tr>
            <th>栏目缩写：</th>
            <td><input type="text" name="abbr" id="abbr" value="<?=$abbr?>" size="30"/></td>
        </tr>
		<tr>
            <th>是否收費：</th>
            <td>
                <label><input type="radio" name="ischarge" value="1" class="radio_style" <?php if ($ischarge == 1) echo 'checked';?> /> 是</label>
                &nbsp;&nbsp;
                <label><input type="radio" name="ischarge" value="0" class="radio_style" <?php if ($ischarge == 0) echo 'checked';?>> 否</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="c_gray">设置栏目是否收费，默认：否。</span>
            </td>
        </tr>
        <tr>
            <th>工作流：</th>
            <td><?php echo element::workflow('workflowid', $workflowid);?></td>
        </tr>
        <tr>
            <th>扩展字段：</th>
            <td id="field_c"></td>
        </tr>
        <tr>
            <th class="vtop pt">内容模型：</th>
            <td>
                <table cellspacing="2" cellpadding="2" class="neinei">
                    <tr>
                        <th width="30" style="text-align:left">允许</th>
                        <th width="60" style="text-align:left">模型</th>
                        <th style="text-align:left">内容页模板</th>
                    </tr>
                    <?php
                    foreach (table('model') as $modelid=>$m) {
                        ?>
                        <tr>
                            <td><input type="checkbox" name="model[<?=$modelid?>][show]" value="1" <?=$model[$modelid]['show'] ? 'checked' : ''?> /></td>
                            <td><?=$m['name']?></td>
                            <td>
                                <?php if (!in_array($m['alias'], array('link', 'special'))) 
                                    echo element::template('template_show_'.$m['modelid'], 'model['.$m['modelid'].'][template]', $model[$modelid]['template'], 40)
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </td>
        </tr>
        <tr>
            <th>栏目首页模板：</th>
            <td>
                &nbsp;<?=element::template('template_index', 'template_index', $template_index, 50)?>
            </td>
        </tr>
        <tr>
            <th>列表页模板： </th>
            <td>
                &nbsp;<?=element::template('template_list', 'template_list', $template_list, 50)?>
            </td>
        </tr>
        <tr>
            <th>发布点：</th>
            <td>
                <?=element::psn('path', 'path', $path, 40, 'dir')?>
            </td>
        </tr>
        <tr>
            <th>生成栏目首页：</th>
            <td>
                <label><input type="radio" name="iscreateindex" value="1" class="radio_style" <?php if ($iscreateindex == 1) echo 'checked';?> /> 是</label>
                &nbsp;&nbsp;
                <label><input type="radio" name="iscreateindex" value="0" class="radio_style" <?php if ($iscreateindex == 0) echo 'checked';?>> 否</label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span class="c_gray">如果通过页面功能管理栏目首页，则应选择否。</span>
            </td>
        </tr>
        <tr>
            <th>栏目首页URL规则：</th>
            <td>&nbsp;<input type="text" name="urlrule_index" value="<?=$urlrule_index?>" size="62"/></td>
        </tr>
        <tr>
            <th>列表页URL规则：</th>
            <td>&nbsp;<input type="text" name="urlrule_list" value="<?=$urlrule_list?>" size="62"/></td>
        </tr>
        <tr>
            <th>内容页URL规则：</th>
            <td>&nbsp;<input type="text" name="urlrule_show" value="<?=$urlrule_show?>" size="62"/></td>
        </tr>
        <tr>
            <th>列表页每页信息数：</th>
            <td>
                &nbsp;<input type="text" name="pagesize" value="<?=$pagesize;?>" size="5"/> 
                <span class="c_gray"> 每个栏目可独立设置，留空或为 0 则取 
                    <a href="javascript:;" onclick="ct.assoc && ct.assoc.open('?app=system&controller=setting&action=optimize', '_blank');">系统设置</a>
                 中的值</span>
            </td>
        </tr>
        <tr>
            <th>允许用户投稿：</th>
            <td><label><input type="radio" name="enablecontribute" value="1" class="radio_style" <?php if ($enablecontribute == 1) echo 'checked';?> /> 是</label>&nbsp;&nbsp;
                <label><input type="radio" name="enablecontribute" value="0" class="radio_style" <?php if ($enablecontribute == 0) echo 'checked';?>> 否</label>
            </td>
        </tr>
        <tr>
            <th>允许评论：</th>
            <td>
                <label><input type="radio" name="allowcomment" value="1" class="radio_style" <?php if ($allowcomment == 1) echo 'checked';?> /> 是</label>&nbsp;&nbsp;
                <label><input type="radio" name="allowcomment" value="0" class="radio_style" <?php if ($allowcomment == 0) echo 'checked';?>> 否</label>
            </td>
        </tr>
        <tr>
            <th>标题：</th>
            <td>
                &nbsp;<input type="text" name="title" value="<?php echo $title;?>" size="60" maxlength="255"/>
            </td>
        </tr>
        <tr>
            <th>关键词：</th>
            <td>
                &nbsp;<input type="text" name="keywords" value="<?=$keywords?>" size="60" maxlength="255"/>
            </td>
        </tr>
        <tr>
            <th>描述：</th>
            <td>
                &nbsp;<textarea name="description" cols="60" rows="3"><?=$description?></textarea>
            </td>
        </tr>
        <tr>
            <th>排序：</th>
            <td>&nbsp;<input type="text" name="sort" value="<?=$sort?>" size="3" maxlength="2"/> 值越大排序越靠后</td>
        </tr>
        <tr>
            <th>默认水印：</th>
            <td>
                <select name="watermark">
                    <option value=""<?php if(empty($watermark)):?> selected="selected"<?php endif;?>>默认</option>
                    <?php foreach(table('watermark') as $item):?>
                    <?php if ($item['disable']) continue;?>
                    <option value="<?php echo $item['watermarkid'];?>"<?php if($item['watermarkid'] == $watermark):?> selected="selected"<?php endif;?>>
                        <?php echo $item['name'];?>
                    </option>
                    <?php endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <th></th>
            <td><input type="submit" value="保存" class="button_style_2"/></td>
        </tr>
    </table>
</form>

<dialog style="display:none;">
    <h2 class="title">子栏目继承</h2>
    <div class="suggest mar_l_8 mar_r_8" style="padding:4px;">
        勾选以下属性对应的复选框后，所有子栏目会继承父栏目的该项属性设置。
    </div>
    <div class="bk_10"></div>
    <form id="extend">
        <table cellpadding="0" cellspacing="0" border="0">
            <?php
                foreach(array(
                    'workflowid' => '工作流',
                    'model' => '内容模型',
                    'template_index' => '栏目首页模板',
                    'template_list' => '列表页模板',
                    'path' => '发布点',
                    'iscreateindex' => '生成栏目首页',
                    'pagesize' => '列表页每页信息数',
                    'enablecontribute' => '允许用户投稿',
                    'allowcomment' => '允许评论',
                    'keywords' => '关键词',
                    'description' => '描述',
                    'watermark' => '默认水印'
                ) as $key => $value):
            ?>
            <tr>
                <th width="120"><?php echo $value;?>：</th>
                <td width="100" class="t_c">
                    <input type="checkbox" name="extend[]" value="<?php echo $key;?>"<?php if(in_array($key, $extend)):?> checked="checked"<?php endif;?> />
                </td>
            </tr>
            <?php endforeach;?>
        </table>
    </form>
    <div class="bk_10"></div>
</dialog>

<script type="text/javascript">
    $('#category_edit').ajaxForm('category.edit_submit');

    var field = {
        get: function()
        {
            var _this = this;
            var html = '<input type="checkbox" id="fieldextend" /><label for="fieldextend">子栏目继承</label>';
            $.get('?app=field&controller=project&action=get_project_api&catid=<?=$catid?>', function(response){
                if(response) {
                    $('#field_c').html(response+html);
                }
            });
        },
        set: function(pid, parentid, extend)
        {
            $.get('?app=field&controller=project&action=set_project_api', {pid:pid, parentid:parentid, extend:extend});
        }
    };
    field.get();

    // 关联字段
    $(function() {
        $("#category_edit").submit(function() {
            var extend = $("#fieldextend").attr("checked") ? 1 : 0;
            field.set($("#project").val(), <?=$catid?>, extend);
        });
    });
</script>