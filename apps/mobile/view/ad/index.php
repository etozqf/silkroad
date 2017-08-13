<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/base.css" />
<link rel="stylesheet" href="apps/mobile/css/ad.css" />

<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>
<script type="text/javascript" src="apps/mobile/js/lib/uploader.js"></script>
<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<div class="bk_8"></div>

<?php $this->display('ad/menu');?>

<div class="ad-content">
    <h3>启动画面</h3>
    <form id="form" action="?app=mobile&controller=ad&action=index" method="POST" class="validator">
        <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
            <tr>
                <td>
                    <div class="ui-bootstrap-logo">
                        <?php foreach (app_config('mobile', 'bootstrap.logo') as $logo): ?>
                        <?php $logo_value = $setting['bootstrap']['logo'][$logo]; ?>
                        <div class="ui-bootstrap-logo-item">
                            <input type="hidden" name="config[bootstrap][logo][<?=$logo?>]" value="<?=$logo_value?>" />
                            <div class="ui-bootstrap-logo-item-img">
                                <?php if ($logo_value): ?>
                                    <img src="<?=abs_uploadurl($logo_value)?>" />
                                <?php else: ?>
                                    <div class="ui-bootstrap-logo-item-img-empty"></div>
                                <?php endif; ?>
                            </div>
                            <div class="ui-bootstrap-logo-item-action">
                                <span class="f_l"><?=$logo?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>

<script type="text/javascript" src="apps/mobile/js/adm/index.js"></script>
