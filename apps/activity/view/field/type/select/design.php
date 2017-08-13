<div class="field-type-options">
    <div class="options-row">
        大小限制：
        <div>
            <input type="text" name="limit" value="<?=$field['options']['limit'] ? $field['options']['limit'] : 1?>" />
            <span class="c_gray">最多允许选择几项</span>
        </div>
    </div>
    <div class="options-row">
        可选值：
        <div>
            <textarea name="option" cols="28" rows="3"><?=$field['options']['option']?></textarea>
            <span class="c_gray">一行一个选项<br/>可选使用 | 将文字与值隔开<br />如：选项A|1</span>
        </div>
    </div>
</div>