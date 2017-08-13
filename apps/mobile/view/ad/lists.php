<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/base.css" />
<link rel="stylesheet" href="apps/mobile/css/ad.css" />
<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<div class="bk_8"></div>

<?php $this->display('ad/menu');?>

<div class="ad-content">
    <h3>列表页广告</h3>

    <div class="ui-tips-yellow" style="width:600px;">
        CmsTop移动版支持链接模型的发布，您可以在内容管理中选择发布链接模型来发布广告，广告图片填写在缩略图中，链接地址即为打开的页面地址。您可以在内容管理中将该链接设置为幻灯片，用这种方式来实现幻灯片广告的效果，同时可以针对频道来设置广告。
    </div>

    <input type="button" class="button-primary" value="发布列表页链接" onclick="ct.assoc.open('?app=mobile&controller=link&action=add', 'newtab');" />
</div>