<div class="field-type-options">
    <div class="options-row">
        大小限制：
        <div>
            <input type="text" name="limit" value="<?=$field['options']['limit']?>" />
            <span class="c_gray">最多允许输入多少字</span>
        </div>
    </div>
    <div class="options-row">
        <?php if ($rules): ?>
        选择验证规则：
        <div>
            <select name="rule">
                <option value=""<?php if (!$rule_name): ?> selected="selected"<?php endif; ?>>不使用</option>
                <?php foreach ($rules as $rule_name => $rule): ?>
                <option value="<?=$rule_name?>"<?php if ($field['options']['rule'] == $rule_name): ?> selected<?php endif; ?>><?=$rule['label']?></option>
                <?php endforeach; ?>
                <option value="__advanced__">自定义正则</option>
            </select>
        </div>
        <?php endif; ?>
        <div data-rule="advanced" style="display: none;">输入正则表达式：</div>
        <div data-rule="advanced" style="display: none;">
            <input type="text" name="regex" value="<?=$field['options']['regex']?>" />
            <span class="c_gray">请使用兼容PHP和JS的正则语法</span>
        </div>
    </div>
</div>