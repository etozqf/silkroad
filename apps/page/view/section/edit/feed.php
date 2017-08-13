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
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tr>
            <td>
                <span class="f_r c_gray">
                    <?php if ($section['published']): ?>
                    上次抓取：<?= date('Y-m-d H:i:s', $section['published'])
                    ; ?>
                    <?php else: ?>
                    未抓取
                    <?php endif;?>
                </span>

                <input type="button" value="立即抓取" class="button_style_4"
                       onclick="page.grapSection(<?=$section['sectionid']?>); return false;"/>
                <input type="button" name="b2" value="预览" onclick="page.previewSection(this.form); return false;" class="button_style_1"/>
                <?php if ($section['nextupdate'] > TIME): ?>
                <span>下次更新时间：<?=date('Y-m-d H:i:s', $section['nextupdate'])?></span>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</form>

<?php if ($section['description']): ?>
<p class="section-tips"><b>备注：</b><?=$section['description']?></p>
<?php endif;?>

<div class="title mar_10" style="margin-bottom:10px;">历史记录</div>
<div class="calendar f_r"></div>
<div class="logtable">
    <?php $this->display('section/log', 'page');?>
</div>
