<form method="POST" action="?app=system&controller=qrcode&action=edit">
    <input type="hidden" name="type" value="<?=$qrcode['type']?>" />
    <input type="hidden" name="contentid" value="<?=$qrcode['contentid']?>" />
    <input type="hidden" name="modelid" value="<?=$qrcode['modelid']?>" />
    <div id="qrcode-detail">
        <input type="hidden" name="qrcodeid" value="<?=$qrcode['qrcodeid']?>" />
        <div id="pick-container">
            <?php if (empty($qrcode['type']) || $qrcode['type'] == CMSTOP_QRCODE_TYPE_CONTENT): ?>
                <input class="button_style_1" type="button" id="pick-content" value="<?php if ($qrcode['contentid']): ?>重新选取<?php else: ?>选择内容<?php endif; ?>" />
            <?php endif; ?>
            <?php if (!empty($qrcode['type']) && $qrcode['type'] == CMSTOP_QRCODE_TYPE_MOBILE): ?>
                <input class="button_style_1" type="button" id="pick-mobile" value="<?php if ($qrcode['contentid']): ?>重新选取<?php else: ?>选择内容<?php endif; ?>" />
            <?php endif; ?>
        </div>

        <div class="infomation-row">
            <label for="str"><em>*</em>链接地址</label>
            <textarea placeholder="请输入标准Url访问地址，可以通过“选取内容”选择已有内容来自动填写" name="str" id="str" cols="30" rows="10"><?=$qrcode['str']?></textarea>
        </div>
        <div class="infomation-row">
            <label for="note"><em>*</em>备注</label>
            <input placeholder="输入备注信息可以帮助您记录和查看、识别历史生成信息" type="text" name="note" value="<?=$qrcode['note']?>" id="note" />
            <div><a id="btn-add-category">#添加投放类型</a></div>
        </div>
    </div>
</form>