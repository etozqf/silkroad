<div class="bk_8" xmlns="http://www.w3.org/1999/html"></div>
<form>
    <table width="95%" border="0" cellspacing="0" cellpadding="0">
        <caption>内容</caption>
        <tbody>
            <tr>
                <td width="60">当前组图：</td>
                <td data-role="item">
                    <?php if ($picture): ?>
                    <a href="<?php echo $picture['url']; ?>" title="<?php echo $picture['title']; ?>" target="_blank"><?php echo str_cut($picture['title'], 40); ?></a>
                    <?php else: ?>未选择<?php endif; ?>
                </td>
                <td width="75">
                    <button data-role="select" type="button"><?php if ($picture): ?>重新选择<?php else: ?>选取组图<?php endif; ?></button>
                </td>
            </tr>
        </tbody>
    </table>
    <table width="95%" border="0" cellspacing="0" cellpadding="0">
        <caption>设置</caption>
        <tbody>
            <tr><td width="90">组图最大宽度：</td><td><input name="data[max_width]" type="text" size="4" value="<?php echo $data['max_width'] ? intval($data['max_width']) : ''; ?>" /></td></tr>
            <tr><td>组图最小高度：</td><td><input name="data[min_height]" type="text" size="4" value="<?php echo $data['min_height'] ? intval($data['min_height']) : 500; ?>" /></tr>
            <tr><td>缩略图尺寸：</td><td><input name="data[thumb_width]" type="text" size="4" value="<?php echo $data['thumb_width'] ? intval($data['thumb_width']) : 100; ?>" /> &#x00D7; <input name="data[thumb_height]" size="4" value="<?php echo $data['thumb_height'] ? intval($data['thumb_height']) : 75; ?>" type="text" /></td></tr>
            <!--列表图尺寸：<input name="data[small_width]" type="text" size="4" value="<?php echo $data['small_width'] ? intval($data['small_width']) : 100; ?>" /> &#x00D7; <input name="data[small_height]" size="4" value="<?php echo $data['small_height'] ? intval($data['small_height']) : 75; ?>" type="text" />-->
            <tr><td colspan="2"><a id="template" style="cursor:pointer">编辑模板</a></td></tr>
        </tbody>
    </table>
    <input type="hidden" name="data[contentid]" data-role="contentid" value="<?php echo intval($data['contentid']) ? intval($data['contentid']) : ''; ?>" />
    <input type="hidden" name="method" value="<?=$method?>" />
    <input type="hidden" name="widgetid" value="<?=$_REQUEST['widgetid']?>" />
</form>
<div class="bk_5"></div>