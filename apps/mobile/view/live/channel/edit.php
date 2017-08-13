<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" type="text/css" href="apps/mobile/css/model.css">

<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>
<script type="text/javascript">
    var action = 'edit';
</script>

<form id="model-live-channel" name="model_live_channel" action="?app=mobile&controller=live&action=channel_edit" method="post">
    <input type="hidden" name="id" value="<?php echo $id;?>" />
    <div class="model-savebar">
        <input type="submit" class="btn-primary" data-role="save-publish" value="保存并发布">
    </div>
    <div class="model-form">
        <div class="model-form-main">
            <div class="form-row">
                <input type="text" name="name" data-maxlength="30" placeholder="标题" size="80" value="<?php echo $name;?>" />
            </div>
            <div class="form-row">
                <dl>
                    <dt>列表缩略图<?php echo element::tips(mobile_element::width_height('缩略图规格：', $config['channel']['ipad']['width'], $config['channel']['ipad']['height']))?></dt>
                    <dd><?php echo element::image('thumb', $thumb, 80, 1);?></dd>
                </dl>
            </div>
            <div class="form-row">
                <dl>
                    <dt>摘要</dt>
                    <dd>
                        <textarea cols="80" rows="4" name="description"><?php echo $description;?></textarea>
                    </dd>
            </div>
            <div class="form-row">
                 <dl>
                    <dt>排序</dt>
                    <dd><input type="text" class="t_r" name="sorttime" size="10" value="<?php echo $sorttime;?>"></dd>
                </dl>
            </div>
        </div>
    </div>
</form>

<script id="success-template" type="text/template">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <th colspan="2" style="text-align:center;"><strong data-role="message">恭喜，内容修改成功</strong></th>
            </tr>
            <tr>
                <td colspan="2">
                    <button id="success-and-edit" class="button_style_4" type="button">继续修改</button>
                    <button id="success-and-close" class="button_style_4" type="button">关闭</button>
                </td>
            </tr>
        </tbody>
    </table>
</script>
<script type="text/javascript" src="apps/mobile/js/live/channel.js"></script>