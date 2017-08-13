<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" type="text/css" href="apps/mobile/css/model.css">
<link rel="stylesheet" type="text/css" href="apps/mobile/css/live.css">

<!-- autocomplete -->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/autocomplete/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.autocomplete.js"></script>

<!-- datepicker -->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/datepicker/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.datepicker.js"></script>

<!-- selectlist -->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/list/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/cmstop.list.js"></script>

<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<script type="text/javascript">
    var action = 'add';
</script>

<form id="model-live-program" name="model_live_program" action="?app=mobile&controller=live&action=program_add" method="post">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <tr>
            <th width="80"><span class="c_red">*</span> 频道：</th>
            <td>
                <select class="selectlist" name="channel_id">
                <?php foreach (table('mobile_live_channel') as $item):?>
                <option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
                <?php endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 标题：</th>
            <td>
                <input type="text" name="title" id="title" size="60" maxlength="80" class="bdr inputtit_focus" autocomplete="off" />
            </td>
        </tr>
        <tr>
            <th>Tags：</th>
            <td><?php echo element::tag('tags');?></td>
        </tr>
        <tr>
            <th><span class="c_red">*</span> 流地址：</th>
            <td>
                <input type="text" name="url" size="60" autocomplete="off" />
            </td>
        </tr>
        <tr>
            <th>来源：</th>
            <td>
                <input type="text" name="source" size="20" autocomplete="1" url="?app=system&controller=source&action=suggest&q=%s" class="source_input" />
                &nbsp;&nbsp;
                <label for="editor">编辑： </label>
                <input id="editor" type="text" name="editor" value="" size="20" class="source_input" />
            </td>
        </tr>
        <tr>
            <th>简介：</th>
            <td><textarea name="description" id="description" maxLength="255" style="width:627px;height:120px;" class="bdr"></textarea></td>
        </tr>
        <tr>
            <th><?php echo element::tips(mobile_element::width_height('缩略图规格：', $config['program']['mobile']['width'], $config['program']['mobile']['height']))?> 缩略图：</th>
            <td><?php echo element::image('thumb', '', 60);?></td>
        </tr>
        <tr>
            <th><label for="online">上线：</label></th>
            <td>
                <input type="text" name="online" id="online" class="input_calendar" size="20"/>
                &nbsp;&nbsp;
                <label for="offline" class="mar_l_8">下线</label>
                <input type="text" name="offline" id="offline" class="input_calendar" size="20"/>
            </td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td>
                <input type="submit" value="保存" class="button_style_2"/>
                <span style="color:#444;text-align:left">按Ctrl+S键保存</span>
            </td>
        </tr>
    </table>
</form>

<script id="success-template" type="text/template">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <th colspan="2" style="text-align:center;"><strong data-role="message">恭喜，内容发布成功</strong></th>
            </tr>
            <tr>
                <td colspan="2">
                    <button id="success-and-edit" class="button_style_4" type="button">修改</button>
                    <button id="success-and-close" class="button_style_4" type="button">关闭</button>
                </td>
            </tr>
        </tbody>
    </table>
</script>
<script type="text/javascript" src="apps/mobile/js/live/program.js"></script>