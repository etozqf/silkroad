<form method="POST" class="validator" action="?app=page&controller=section&action=edit">
    <input type="hidden" name="sectionid" value="<?=$section['sectionid'];?>"/>

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
        <div class="f_l"><?=$section['name']?></div>
    </div>

    <div style="zoom:1;overflow:hidden;">
        <textarea style="height:300px;" id="data" name="data"><?=htmlspecialchars($section['data'])?></textarea>
    </div>
    <div style="line-height:30px;padding-top:5px;">
        <span class="f_r c_gray">
                    <?php if ($section['published']): ?>
            最近更新：<?= date('Y-m-d H:i:s', $section['published'])
            ; ?>
            <?php else: ?>
            未生成
            <?php endif;?>
                </span>

        <?php if (priv::aca('page', 'page', 'manage')):?>
        <input type="button" name="submit" value="编辑" class="button_style_2"/>
        <?php endif;?>
        <input type="button" name="b2" value="预览" onclick="page.previewSection(this.form,true); return false;" class="button_style_1"/>

        <?php if ($section['nextupdate'] < TIME): ?>
        <label><input class="checkbox_style"
                      onclick="this.checked ? $('#nextupdate').show().removeAttr('disabled') : $('#nextupdate').hide().attr('disabled','disabled')"
                      type="checkbox"/> 定时发布 </label>
        <input type="text" id="nextupdate" class="input_calendar" name="nextupdate" style="display:none"
               disabled="disabled" value="<?=date('Y-m-d H:i:s', time() + 3600);?>"
               onclick="DatePicker(this,{'format':'yyyy-MM-dd HH:mm:ss'});"/>
        <?php else: ?>
        <label><input class="checkbox_style"
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
<script type="text/javascript">
var privPriview = <?php echo priv::aca('page', 'section', 'previewhtml') ? 'true' : 'false'?>;
</script>