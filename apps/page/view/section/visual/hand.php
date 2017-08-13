<style type="text/css">
    .dialog-box td {background: #fff;}
    .dialog-box p {padding:0;}
    .move_cursor {width:16px; height:16px; background: url('css/images/move.png'); margin: 0 auto;}
    .f_r .button_style_1 {margin-right:0;}
</style>
<input type="text" style="visibility:hidden; position:fixed;" />
<div style="margin: 5px 10px; zoom: 1; position: relative;">
    <?php if (!$section['rows']): ?>
    <span class="vt_m">
        <input name="addrow" type="button" value="新建行" class="button_style_1"/>
    </span>
    <?php endif; ?>
    <div class="bk_5"></div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_info">
        <thead>
        <tr>
            <th width="20" class="t_c"><div class="move_cursor"></div></th>
            <th class="t_c">内容</th>
            <?php if (!$section['rows']): ?>
            <th width="50" class="t_c">操作</th>
            <?php endif; ?>
        </tr>
        </thead>
        <tbody id="sortable">
        <?php foreach($section['data'] as $key => $line):?>
        <tr>
            <td class="t_c" style="cursor: move;" width="30"><?=$key+1?></td>
            <td row="<?=$key?>">
                <ul class="inline" style="width:650px">
                    <?php foreach($line as $sort => $item):?>
                    <li col="<?=$sort ? $sort : 0?>" url="<?=$item['url']?>">
                        <a<?php if ($item['icon']): ?> class="icon-<?=$item['icon']?>" <?php endif; ?> href="<?=$item['url']?>" tips="<?=$item['tips']?>" target="_blank"><?=$item['title']?></a>
                    </li>
                    <?php endforeach;?>
                </ul>
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
</div>