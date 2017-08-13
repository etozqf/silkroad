<?php $this->display('header','system');?>

<link rel="stylesheet" href="apps/system/css/qrcode.css" />

<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL;?>js/lib/placeholder/style.css" />
<script type="text/javascript" src="<?php echo IMG_URL;?>js/lib/jquery.placeholder.js"></script>

<link rel="stylesheet" href="<?=IMG_URL?>js/lib/ui.slider/style.css" />
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.ui.slider.js"></script>

<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="js/cmstop.filemanager.js"></script>

<div class="bk_10"></div>
<div class="tag_1">
    <ul class="tag_list">
        <li><a href="javascript:void(0);" class="s_3">二维码生成</a></li>
        <li><a href="?app=system&controller=qrcode&action=stat">生成历史及访问统计</a></li>
    </ul>
</div>

<form id="qrcode-add" action="?app=system&controller=qrcode&action=generate" method="post">
    <div class="container">
        <div class="infomation">
            <div id="pick-container">
                <?php if (empty($type) || $type == CMSTOP_QRCODE_TYPE_CONTENT): ?>
                <input class="button_style_1" type="button" id="pick-content" value="选择内容" />
                <?php endif; ?>
                <?php if (!empty($type) && $type == CMSTOP_QRCODE_TYPE_MOBILE): ?>
                <input class="button_style_1" type="button" id="pick-mobile" value="选择内容" />
                <?php endif; ?>
            </div>

            <div class="infomation-row">
                <label for="str"><em>*</em>链接地址</label>
                <div class="ui-related-title">
                    <textarea placeholder="请输入标准Url访问地址，可以通过“选取内容”选择已有内容来自动填写" name="str" id="str" cols="30" rows="10"><?=$str?></textarea>
                    <div id="reference" class="related-item"<?php if ($str): ?> style="display:block;"<?php endif; ?>>
                        <input type="hidden" name="type" value="<?=$type?>" />
                        <input type="hidden" name="contentid" value="<?=$contentid?>" />
                        <input type="hidden" name="modelid" value="<?=$modelid?>" />
                        关联内容：<a href="<?php if ($str): ?><?=htmlspecialchars($str)?><?php else: ?>javascript:void(0);<?php endif; ?>" target="_blank"><?php if ($note): ?><?=htmlspecialchars($note)?><?php endif; ?></a>
                        <a class="hand" href="javascript:void(0);">取消关联</a>
                    </div>
                </div>
            </div>
            <div class="infomation-row">
                <label for="note"><em>*</em>备注</label>
                <input placeholder="输入备注信息可以帮助您记录和查看、识别历史生成信息" type="text" name="note" value="<?=$note?>" id="note" />
                <div><a id="btn-add-category">#添加投放类型</a></div>
            </div>
            <div class="infomation-row">
                <div class="qrcode-image">
                    <label><input type="checkbox" name="image_fill"<?=form_element::checked($image_fill)?> value="1" /> 缩略图</label>
                    <a href="javascript:void(0);" id="btn-image-change">修改</a>
                    <div>
                        <input type="text" size="15" id="image-holder" value="<?=$image?>" />
                        <input type="hidden" name="image" id="image" value="<?=$image?>" />
                        <span class="button" id="btn-image-upload">上传</span>
                        <input type="button" id="btn-image-save" class="button_style_2" value="确定" />
                    </div>
                </div>
            </div>
            <div class="infomation-row">
                <input type="button" id="btn-preview" class="f_r button-primary" value="&nbsp;&nbsp;&nbsp;预览&nbsp;&nbsp;&nbsp;" />
                <input type="submit" class="f_r button-primary" value="生成并保存" style="padding:0 10px;margin-right:10px;" />
            </div>
        </div>
        <div class="options">
            <div class="preview">
                <a title="点击下载" target="_blank"><img id="preview-image" alt=""/></a>
            </div>
            <div id="download-panel"></div>
        </div>
    </div>
</form>

<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.text-selection.js"></script>
<script type="text/javascript" src="apps/system/js/datapicker.js"></script>
<script type="text/javascript" src="apps/system/js/qrcode.js"></script>
<script type="text/javascript">
    $(function() {
        QRCode.initForm($('#qrcode-add'));
    });
</script>