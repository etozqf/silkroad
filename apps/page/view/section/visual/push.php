<style type="text/css">
    td {
        background: #fff;
    }
    #section_log img {
        margin: 0 4px;
    }
</style>

<div style="margin: 5px 10px; zoom: 1;">
    <span class="vt_m">
        <input type="button" value="添加内容" class="button_style_1" id="add-row" check="<?=$section['check']?>" />
    </span>
    <div class="bk_5"></div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="empty-cells:show;" class="table_info mar_5">
        <thead>
        <tr>
            <th width="30">
                <div class="move_cursor"></div>
            </th>
            <th class="t_c" width="">内容</th>
            <th class="t_c" width="70">操作</th>
            <th class="t_c" width="115">发布时间</th>
            <th class="t_c" width="115">推荐时间</th>
            <th class="t_c" width="70">推荐人</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
        <tbody>
        </tbody>
    </table>
    <div class="bk_5"></div>

    <p class="f_l vt_m" style="height:30px;line-height:30px;">
        <?php if($section['nextupdate'] < TIME):?>
        <label><input name="commit_publish" class="checkbox_style" onclick="this.checked ? $('#nextupdate').show() : $('#nextupdate').hide()" type="checkbox" /> 定时发布</label>
        <input type="text" id="nextupdate" class="input_calendar" name="nextupdate" style="display:none" value="<?=date('Y-m-d H:i:s', time()+3600);?>" onclick="DatePicker(this,{'format':'yyyy-MM-dd HH:mm:ss'});" />
        <?php else:?>
        <label><input name="commit_publish" class="checkbox_style" onclick="this.checked ? $('#nextupdate').show() : $('#nextupdate').hide()" type="checkbox" checked="checked" /> 定时发布</label>
        <input type="text" id="nextupdate" class="input_calendar" name="nextupdate" value="<?=date('Y-m-d H:i:s', $section['nextupdate'])?>" onclick="DatePicker(this,{'format':'yyyy-MM-dd HH:mm:ss'});" />
        <?php endif;?>
    </p>
    <div class="clear"></div>

    <?php if ($section['description']): ?>
    <p class="section-tips"><b>备注：</b><?=$section['description']?></p>
    <?php endif;?>

    <textarea style="display:none;" id="tpl-item">
        <?=script_template('<tr{%if data.title%} filled="true"{%endif%} istop="{%istop%}" recommendid={%recommendid%}>
        <td class="t_c" width="30" style="cursor:move">{%index%}</td>
        <td><a{%if data.icon%} class="icon-{%data.icon%}"{%endif%} href="{%data.url%}" style="color:{%data.color%}" tips="{%tips%}" target="_blank">{%data.title%}</a></td>
        <td class="t_c" width="70">
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
        <td class="t_c" width="115">{%published_name%}</td>
        <td class="t_c" width="115">{%recommended_name%}</td>
        <td class="t_c" width="70"><a href="javascript:url.member({%recommendedby%});">{%recommendedby_name%}</a></td>
    </tr>')?>
    </textarea>
</div>