<style type="text/css">
    td {
        background: #fff;
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
<?php if (!$section['rows']): ?>
<p class="vt_m" style="height:25px;line-height:25px">
    <input name="addrow" type="button" value="新建行" style="margin-right: 0;" class="button_style_1"/>
</p>
<?php endif; ?>
<div style="zoom: 1; position: relative;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="empty-cells:show;" class="table_info">
        <thead>
        <tr>
            <th width="30">
                <div class="move_cursor"></div>
            </th>
            <th width="">内容</th>
            <?php if (!$section['rows']): ?>
            <th width="30">操作</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody id="sortable">
        <?php foreach ($section['data'] as $key => $line): ?>
        <tr>
            <td class="t_c" width="30" style="cursor:move"><?=$key + 1?></td>
            <td row="<?=$key?>">
                <ul class="inline w_120">
                    <?php foreach ($line as $sort => $item): ?>
                    <li col="<?=$sort ? $sort : 0?>" url="<?=$item['url']?>">
                        <a<?php if ($item['icon']): ?> class="icon-<?=$item['icon']?>" <?php endif; ?> href="<?=$item['url']?>" style="color:<?=$item['color']?>" tips="<?=$item['tips']?>" target="_blank"><?=$item['title']?></a></li>
                    <?php endforeach;?>
                </ul>
                &nbsp;
            </td>
            <?php if (!$section['rows']): ?>
            <td class="t_c" width="30">
                <img src="images/del.gif" height="16" width="16" alt="删除" title="删除" action="delrow" class="hand"/>
            </td>
            <?php endif; ?>
        </tr>
            <?php endforeach;?>
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
        <label style="cursor:pointer"><input class="checkbox_style"
                                             onclick="this.checked ? $('#nextupdate').show().removeAttr('disabled') : $('#nextupdate').hide().attr('disabled','disabled')"
                                             type="checkbox"/> 定时发布 </label>
        <input type="text" id="nextupdate" class="input_calendar" name="nextupdate" style="display:none"
               disabled="disabled" value="<?=date('Y-m-d H:i:s', time() + 3600);?>"
               onclick="DatePicker(this,{'format':'yyyy-MM-dd HH:mm:ss'});"/>
        <?php else: ?>
        <label style="cursor:pointer"><input class="checkbox_style"
                                             onclick="this.checked ? $('#nextupdate').show().removeAttr('disabled') : $('#nextupdate').hide().attr('disabled','disabled')"
                                             type="checkbox" checked="checked"/> 定时发布 </label>
        <input type="text" id="nextupdate" class="input_calendar" name="nextupdate"
               value="<?=date('Y-m-d H:i:s', $section['nextupdate'])?>"
               onclick="DatePicker(this,{'format':'yyyy-MM-dd HH:mm:ss'});"/>
        <?php endif;?>
    </div>
</form>

<?php if ($section['description']): ?>
<p class="section-tips"><b>备注：</b><?=$section['description']?></p>
<?php endif;?>

<div class="title mar_10" style="margin-bottom:10px;">历史记录</div>
<div class="calendar f_r"></div>
<div class="logtable">
    <?php $this->display('section/log', 'page');?>
</div>