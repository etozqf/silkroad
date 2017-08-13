<style type="text/css">
    .table_form th,
    .table_form td {
        line-height: 24px;
    }
</style>
<div class="bk_8"></div>
<form name="setting_classify" method="POST" action="?app=mobile&controller=setting&action=classify_add">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th width="120"><span class="c_red">*</span> 分类名称：</th>
            <td><input type="text" name="classname" value="" size="30" /></td>
        </tr>
        <tr>
            <th>系统模型绑定：</th>
            <td>
                <select name="modelid" id="modelid">
                    <?php
                        foreach($model as $modelid => $value)
                        {
                            echo '<option value="' . $value['id'] .  '">' . $value['name'] .'</option>';
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>排序ID：</th>
            <td>
                <input type="number" name="sort" id="sort" min="0" value="0"/>
            </td>
        </tr>
        <tr>
            <th>状态：</th>
            <td>
                <label>
                    <input type="radio" name="disabled" value="0" checked="checked" /> 启用
                </label>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <label>
                    <input type="radio" name="disabled" value="1" /> 禁用
                </label>
            </td>
        </tr>
    </table>
</form>
<div class="bk_5"></div>