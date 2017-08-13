<div class="bk_8"></div>
<form method="POST" action="?app=page&controller=section&action=add">
    <table width="94%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <tbody>
            <tr>
                <th width="72">类型：</th>
                <td>
                    <?php foreach (app_config('page', 'type') as $type_alias => $type_name): ?>
                    <label class="<?=$type_alias?>"><input<?php if ($type == $type_alias): ?> checked="checked"<?php endif; ?> type="radio" id="type_<?=$type_alias?>" name="type" class="radio_style" value="<?=$type_alias?>" /><span><?=$type_name?></span></label>
                    <?php endforeach; ?>
                </td>
            </tr>
            <tr>
                <th><span class="c_red">*</span> 名称：</th>
                <td>
                    <input type="text" name="name" value="" maxlength="30" size="20"/>
                    <input type="hidden" name="pageid" value="<?=$pageid?>"/>
                </td>
            </tr>
            <tr>
                <th><span class="c_red">*</span> 模板代码：</th>
                <td>
                    <textarea name="data" class="code" wrap="off" style="width:370px;"><?=$data?></textarea>
                </td>
            </tr>
            <tr>
                <th>行数：</th>
                <td>
                    <input type="text" name="rows" value="6" size="2"/> 行 <span class="c_gray">0表示无固定行数</span>
                </td>
            </tr>
            <tr>
                <th>输出格式：</th>
                <td>
                    <label><input type="checkbox" class="checkbox_style" checked="checked" name="output[]" value="html"/>html</label>
                    <label><input type="checkbox" class="checkbox_style" name="output[]" value="xml"/>xml</label>
                    <label><input type="checkbox" class="checkbox_style" name="output[]" value="json"/>json</label>
                </td>
            </tr>
            <tr>
                <th>字段设置：</th>
                <td>
                    <?=section_fields()?>
                </td>
            </tr>
            <tr>
                <th>备注：</th>
                <td><textarea placeholder="备注用于在编辑维护区块时提示区块所需数据的规格参数，如缩略图大小，标题长度等" name="description" style="width:370px;height:35px;padding:0;margin:0;"></textarea></td>
            </tr>
            <tr>
                <th>推送审核：</th>
                <td>
                    <label><input type="radio" class="radio_style" name="check" value="1"/>开启</label>
                    <label><input type="radio" class="radio_style" checked="checked" name="check" value="0"/>关闭</label>
                </td>
            </tr>
            <tr>
                <th>生成列表：</th>
                <td>
                    <input type="checkbox" name="list_enabled" onclick="$(this).parent().parent().nextAll('[data-role=list]').toggle();" value="1" />
                </td>
            </tr>
            <tr style="display:none;" data-role="list">
                <th>列表模板：</th>
                <td>
                    <?=element::template('list_template', 'list_template', 'section/list.html')?>
                </td>
            </tr>
            <tr style="display:none;" data-role="list">
                <th>每页条数：</th>
                <td>
                    <input type="text" name="list_pagesize" value="" size="5" /> 留空或为 0 则取 <a onclick="ct.assoc && ct.assoc.open('?app=system&controller=setting&action=optimize', '_blank');" href="javascript:;">系统设置</a> 中的值（<?=setting('system', 'pagesize')?>）
                </td>
            </tr>
            <tr style="display:none;" data-role="list">
                <th>生成页数：</th>
                <td>
                    <input type="text" name="list_pages" value="10" size="5" />
                </td>
            </tr>
        </tbody>
    </table>
</form>
<div class="bk_5"></div>