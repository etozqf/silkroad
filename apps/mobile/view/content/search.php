<div class="bk_8"></div>
<form action="?app=mobile&controller=content&action=search" method="POST">
    <input type="hidden" name="catid" value="<?=$catid?>">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <th> 模型：</th>
            <td>
                <select name="modelid">
                    <option<?php if (!$modelid): ?> selected="selected"<?php endif; ?> value="">全部模型</option>
                    <?php $mobile_models = mobile_model(); ?>
                    <?php foreach ($mobile_models as $_modelid => $_model): ?>
                    <option<?php if ($_modelid == $modelid): ?> selected="selected"<?php endif; ?> value="<?=$_modelid?>"><?=$_model['name']?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th> 标题：</th>
            <td>
                <input type="text" name="keyword" value="" size="40" />
            </td>
        </tr>
        <tr>
            <th>状态：</th>
            <td>
                <select name="status">
                    <?php $mobile_status = mobile_status(); ?>
                    <?php foreach ($mobile_status as $_status => $_status_name): ?>
                        <option<?php if ($_status == $status): ?> selected="selected"<?php endif; ?> value="<?=$_status?>"><?=$_status_name?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>创建人：</th>
            <td><input type="text" name="createdby_name" value="" size="15"/></td>
        </tr>
        <tr>
            <th>创建时间：</th>
            <td>
                <input type="text" name="created_min" value="<?=$created_min?>" size="18" class="input_calendar"/> ~
                <input type="text" name="created_max" value="<?=$created_max?>" size="18" class="input_calendar"/>
            </td>
        </tr>
        <tr>
            <th>发布人：</th>
            <td><input type="text" name="publishedby_name" value="" size="15"/></td>
        </tr>
        <tr>
            <th>发布时间：</th>
            <td>
                <input type="text" name="published_min" value="<?=$published_min?>" size="18" class="input_calendar"/> ~
                <input type="text" name="published_max" value="<?=$published_max?>" size="18" class="input_calendar"/>
            </td>
        </tr>
        <tr>
            <th>排序方式：</th>
            <td>
                <select name="orderby">
                    <option value="">默认排序</option>
                    <option value="created">创建时间</option>
                    <option value="published">发布时间</option>
                    <option value="pv">访问量</option>
                    <option value="comments">评论量</option>
                </select>
            </td>
        </tr>
    </table>
</form>
<div class="bk_5"></div>