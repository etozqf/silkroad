<style type="text/css">
    td {
        background: #fff;
    }
    #section_log img {
        margin: 0 4px;
    }
</style>
<div class="title" style="padding-right:0;">
    <div class="f_r">
        <span id="section-editors">
            <?php if ($admins): ?>
            <?php $count = 0; ?>
            <?php foreach ($admins as $admin): ?>
                <?php if ($count >= 5): break; else: $count++; endif; ?>
                <a href="javascript:url.member(<?=$admin['userid']?>);"><?=$admin['username']?></a>
                <?php endforeach; ?>
            <?php if ($count >= 5): ?> ... <?php endif; ?>
            <?php endif; ?>
        </span>
        <?php if (priv::aca('page', 'section_priv') && priv::section($section['sectionid'], $section['pageid'])): ?><input type="button" class="button_style_1" style="margin:0 0 0 10px;" onclick="page.sectionPriv(<?=$section['sectionid']?>);return false;" value="区块权限" /><?php endif; ?>
    </div>
    <div class="f_l">
        <?=$section['name']?>
        <span>
            <?php foreach ($output_types as $output_type): ?>
            <?php if (strtolower($output_type) == 'html') continue; ?>
            <a title="区块的<?=$output_type?>格式输出文件" href="<?=WWW_URL?>section/<?=$section['sectionid']?>.<?=$output_type?>" target="_blank"><span class="section-item-output <?=$output_type?>">&nbsp;</span></a>
            <?php endforeach; ?>
        </span>
    </div>
</div>

<span class="vt_m">
    <input type="button" value="添加内容" class="button_style_1" id="add-row" check="<?=$section['check']?>" />
</span>
<div style="zoom:1;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="empty-cells:show;" class="table_info mar_5">
        <thead>
        <tr>
            <th width="30">
                <div class="move_cursor"></div>
            </th>
            <th width="">内容</th>
            <th width="90">操作</th>
            <th width="120">发布时间</th>
            <th width="120">推荐时间</th>
            <th width="80">推荐人</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        <tbody>
        </tbody>
    </table>
</div>

<div class="bk_5"></div>
<form method="POST" action="?app=page&controller=section&action=edit">
    <input type="hidden" name="sectionid" value="<?=$section['sectionid'];?>"/>
    <div style="height:30px;line-height:30px;padding-left:45px;">
        <span class="f_r c_gray">
            <?php if ($section['published']): ?>
            最近更新：<?= date('Y-m-d H:i:s', $section['published'])
            ; ?>
            <?php else: ?>
            未生成
            <?php endif;?>
        </span>

        <input type="submit" value="保存" class="button_style_2"/>
        <input onclick="page.previewSection(this.form); return false;" type="button" value="预览" class="button_style_1"/>

        <?php if ($section['nextupdate'] < TIME): ?>
        <label style="cursor:pointer"><input class="checkbox_style" onclick="this.checked ? $('#nextupdate').show().removeAttr('disabled') : $('#nextupdate').hide().attr('disabled','disabled')" type="checkbox"/> 定时发布 </label>
        <input type="text" id="nextupdate" class="input_calendar" name="nextupdate" style="display:none" disabled="disabled" value="<?=date('Y-m-d H:i:s', time() + 3600);?>" onclick="DatePicker(this,{'format':'yyyy-MM-dd HH:mm:ss'});"/>
        <?php else: ?>
        <label style="cursor:pointer"><input class="checkbox_style" onclick="this.checked ? $('#nextupdate').show().removeAttr('disabled') : $('#nextupdate').hide().attr('disabled','disabled')" type="checkbox" checked="checked"/> 定时发布 </label>
        <input type="text" id="nextupdate" class="input_calendar" name="nextupdate" value="<?=date('Y-m-d H:i:s', $section['nextupdate'])?>" onclick="DatePicker(this,{'format':'yyyy-MM-dd HH:mm:ss'});"/>
        <?php endif;?>
    </div>
</form>

<?php if ($section['description']): ?>
<p class="section-tips"><b>备注：</b><?=$section['description']?></p>
<?php endif;?>

<textarea style="display:none;" id="tpl-item">
    <?=script_template('<tr{%if data.title%} filled="true"{%endif%} istop="{%istop%}" recommendid={%recommendid%}>
        <td class="t_c" width="30" style="cursor:move">{%index%}</td>
        <td><a{%if data.icon%} class="icon-{%data.icon%}"{%endif%} href="{%data.url%}" style="color:{%data.color%}" tips="{%tips%}" target="_blank">{%data.title%}</a></td>
        <td class="t_c" width="90">
            {%if data.title%}
            {%if (parseInt(istop))%}
            <img src="images/dx.gif" alt="取消置顶" title="取消置顶" height="20" style="vertical-align: baseline;" width="16" action="unStick" class="hand"/>
            {%else%}
            <img src="images/zx.gif" alt="置顶" title="置顶" height="20" style="vertical-align: baseline;" width="16" action="stick" class="hand"/>
            {%endif%}
            <img src="images/edit.gif" alt="编辑" title="编辑" height="16" width="16" action="edit" class="hand"/>
            <img src="images/del.gif" alt="删除" title="删除" height="16" width="16" action="remove" class="hand"/>
            {%endif%}
        </td>
        <td class="t_c" width="120">{%published_name%}</td>
        <td class="t_c" width="120">{%recommended_name%}</td>
        <td class="t_c" width="80"><a href="javascript:url.member({%recommendedby%});">{%recommendedby_name%}</a></td>
    </tr>')?>
</textarea>

<div class="title mar_10" style="margin-bottom:10px;">历史记录</div>
<div style="zoom:1;">
    <table width="100%" cellpadding="0" cellspacing="0" id="section_log" class="tablesorter table_list">
        <thead>
            <tr>
                <th class="t_c bdr_3">标题</th>
                <th width="85">管理</th>
                <th width="125" class="ajaxer"><div name="published">发布时间</div></th>
                <th width="125" class="ajaxer"><div name="recommended">推荐时间</div></th>
                <th width="85" class="ajaxer"><div name="recommendedby">推荐人</div></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <div class="table_foot">
        <div id="pagination" class="pagination f_r"></div>
        <input class="button_style_1" type="button" data-role="refresh-logs" value="刷新" />
    </div>
</div>
<textarea style="display:none;" id="tpl-log">
<?=script_template('<tr id="row_{recommendid}" isdeleted="{isdeleted}">
        <td><a href="{data.url}" style="color:{data.color}" tips="{tips}" target="_blank">{data.title}<span class="label-remove">已删</span></a></td>
        <td class="t_c">
            <img src="images/upon.gif" alt="重推" title="重推" height="16" width="16" action="use-log" class="hand"/>
            <img src="images/edit.gif" alt="编辑" title="编辑" height="16" width="16" action="edit-log" class="hand"/>
            <img src="images/del.gif" alt="删除" title="删除" height="16" width="16" action="remove-log" class="hand"/>
        </td>
        <td class="t_c">{published_name}</td>
        <td class="t_c">{recommended_name}</td>
        <td class="t_c"><a href="javascript:url.member(\'{recommendedby}\');">{recommendedby_name}</a></td>
    </tr>
')?>
</textarea>