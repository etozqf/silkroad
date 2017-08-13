<div class="field-type-options">
    <!--<div class="options-row">
        可上传文件数：
        <div>
            <input type="text" name="limit" value="<?/*=$field['options']['limit'] ? $field['options']['limit'] : 1*/?>" />
            <span class="c_gray">最多允许选择几项</span>
        </div>
    </div>-->
    <div class="options-row">
        文件大小限制为：
        <div>
            <input type="text" name="sizelimit" value="<?=$field['options']['sizelimit'] ? $field['options']['sizelimit'] : 5?>" />
            <span class="c_gray">MB</span>
        </div>
    </div>
    <!--<div class="options-row">
        可上传的文件后缀：
        <div>
            <input type="text" name="fileext" value="<?/*=$field['options']['fileext'] ? $field['options']['fileext'] : ''*/?>" />
            <span class="c_gray" title="当前系统设置中的值为 <?/*=setting('system', 'attachexts')*/?>">为空则取系统设置中的值</span>
        </div>
    </div>-->
</div>