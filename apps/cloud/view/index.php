<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$CONFIG['charset']?>" />
<title><?=$head['title']?></title>
<link rel="stylesheet" type="text/css" href="css/admin.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.switch.js"></script>

<!--dialog-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/jquery-ui/dialog.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.dialog.js"></script>

<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.table.js"></script>

<script type="text/javascript" src="imageEditor/cmstop.imageEditor.js"></script>
<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css"/>
<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>

<!--validator-->
<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/validator/style.css"/>
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.validator.js"></script>
<script type="text/javascript">
$.validate.setConfigs({
    xmlPath:'apps/<?=$app?>/validators/'
});
</script>

<!--sort-->
<link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/jquery-ui/dialog.css" />
<script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>

<script type="text/javascript" src="apps/cloud/js/cloud.js"></script>
<script type="text/javascript" src="apps/cloud/js/bshare.js"></script>
<link rel="stylesheet" type="text/css" href="apps/cloud/css/style.css"/>
</head>
<body>
    <div class="bk_10"></div>
    <div class="title">CmsTop 新云服务鉴权</div>
    <div class="bk_10"></div>
    <div class="cloud-box cloud-hide">
        <div class="cloud-banner">
            <div class="cloud-banner-ico"></div>
            <div class="cloud-banner-setting" event="status"></div>
            <div class="cloud-switch"><input class="switch" type="checkbox" name="cloud_status" value="1"<?php if($cloud_status):?> checked="checked"<?php endif;?> /></div>
            <h3>CmsTop App Key</h3>
        </div>
        <div class="cloud-panel">
            <div class="cloud-panel-arrow"></div>
            <div class="cloud-panel-context">
                <div class="bk_10"></div>
                <span class="mar_r_8"></span>
            </div>
        </div>
    </div>

	<div class="bk_10"></div>
	<div class="title">CmsTop官方提供的云服务</div>
	<div class="bk_10"></div>
	<!-- template
	<div class="cloud-box cloud-hide">
		<div class="cloud-banner">
			<div class="cloud-banner-ico"></div>
			<div class="cloud-banner-setting"></div>
			<div class="cloud-switch"><input class="switch" type="checkbox" name="" value="1" /></div>
			<h3></h3>
		</div>
		<div class="cloud-panel"></div>
	</div>
	-->
	<div class="cloud-box cloud-hide">
		<div class="cloud-banner">
			<div class="cloud-banner-ico"></div>
			<div class="cloud-banner-setting"></div>
			<div class="cloud-switch"><input class="switch" type="checkbox" name="o2h_allowed" value="1"<?php if($o2h_allowed):?> checked="checked"<?php endif;?> /></div>
			<h3>OFFICE文档转换</h3>
		</div>
		<div class="cloud-panel">
			<div class="cloud-panel-arrow"></div>
			<div class="cloud-panel-context">
				<div class="bk_10"></div>
				<span class="mar_r_8">通讯密钥</span>
				<form action="?app=cloud&controller=cloud&action=set_setting" method="get" onsubmit="form_submit(this);return false;" style="display:inline;">
					<input class="cloud-input-text mar_r_8" type="text" name="o2h_secret" value="<?php echo $o2h_secret;?>" />
					<input class="button_style_2" type="submit" value="保存">
				</form>
			</div>
		</div>
	</div>
	<div class="cloud-box cloud-hide">
		<div class="cloud-banner">
			<div class="cloud-banner-ico"></div>
			<div class="cloud-banner-setting"></div>
			<div class="cloud-switch"><input class="switch" type="checkbox" name="spider_allowed" value="1"<?php if($spider_allowed):?> checked="checked"<?php endif;?> /></div>
			<h3>云采集</h3>
		</div>
		<div class="cloud-panel">
			<div class="cloud-panel-arrow"></div>
			<div class="cloud-panel-context">
				<div class="suggest w_650 mar_l_8">
					<h2>云采集接口说明</h2>
					<p>
				        云采集接口是CmsTop针对主流新闻、视频网站开发的数据采集平台。<br />
				        配置此接口后，可实现组图、视频的一键采集。
				    </p>
				</div>
			</div>
		</div>
	</div>
	<div class="cloud-box cloud-hide rule" style="display:none;">
		<div class="cloud-banner">
			<div class="cloud-banner-ico"></div>
			<div class="cloud-banner-setting"></div>
			<div class="cloud-switch"><input class="switch" type="checkbox" name="rule_allowed" value="1"<?php if($rule_allowed):?> checked="checked"<?php endif;?> /></div>
			<h3>云规则同步</h3>
		</div>
		<div class="cloud-panel">
			<div class="cloud-panel-arrow"></div>
			<div class="cloud-panel-context">
				<div class="suggest w_650 mar_l_8">
					<h2>云规则同步说明</h2>
					<p>
				        云规则同步接口可用于从CmsTop云端获得一些网站最新的数据接口。
				    </p>
				</div>
				<div class="bk_10"></div>
				<span class="mar_r_8">云规则同步</span>
				<form method="post" style="display:inline;">
					<input class="cloud-input-text mar_r_8" type="text" name="rule_address" value="<?php echo $rule_address;?>" />
					<input class="button_style_4" type="submit" value="保存并同步">
					<div class="bk_10"></div>
					<div>
						<?php if (!empty($rule_info['lastupdatetime'])):?>
						<span class="mar_r_8">同步时间: <i class="ruleupdatetime"><?php echo date('Y-m-d H:i:s', $rule_info['lastupdatetime']);?></i></span>
						<?php endif;?>
						<?php if (!empty($rule_info['version'])):?>
						<span class="mar_r_8">目前版本: <i class="ruleversion"><?php echo $rule_info['version'];?></i></span>
						<?php endif;?>
						<span class="mar_r_8">
							<label>
								<input type="checkbox" id="rule_auto_update" <?php if($rule_auto_update):?>checked="checked"<?php endif;?>/> 
								<strong>启用自动同步</strong>
							</label>
						</span>
					</div>

				</form>
			</div>
		</div>
	</div>
	<div class="bk_10"></div>
	<div class="title">第三方提供的云服务</div>
	<div class="bk_10"></div>
	<div class="cloud-box cloud-hide">
		<div class="cloud-banner">
			<div class="cloud-banner-ico"></div>
			<div class="cloud-banner-setting"></div>
			<div class="cloud-switch"><input class="switch" type="checkbox" name="share" value="1"<?php if($share):?> checked="checked"<?php endif;?> /></div>
			<h3>社会化内容分享组件</h3>
		</div>
		<div class="cloud-panel cloud-bg-white">
			<div class="cloud-panel-arrow"></div>
			<div class="bk_10"></div>
			<div class="cloud-share-textarea"><textarea id="share-textarea"><?php echo $share_data;?></textarea></div>
			<?php if(strpos($share_data, 'bshare') !== false):?>
			<div class="bk_10"></div>
			<div id="bshareLogining" class="cloud-share-line">
				<div style="float:left; padding-right:12px;"><img src="apps/cloud/images/bshare-logo.png" alt="bshare" /></div>
				<button class="cloud-btn-red-106" style="float:left;" onclick="bshare.reg();">自动注册并使用</button>
				<span class="cloud-empty"></span>
				<a href="javascript:;" onclick="bshare.bind();">已有账号,设置绑定</a>
				<span class="cloud-empty"></span>
				<a href="javascript:ct.assoc.open('http://one.bshare.cn/register?service=http%3A%2F%2Fwww.bshare.cn%2Fauthentication&targetUrl=http%3A%2F%2Fwww.bshare.cn%2FregisterAction', 'newtab');">全新注册</a>
				<input class="button_style_1" type="button" value="保存" onclick="shareSave();" style="float:right;" />
			</div>
			<div id="bshareLogined" class="cloud-share-line" style="display:none;">
				<div style="float:left; padding-right:12px;"><img src="apps/cloud/images/bshare-logo.png" alt="bshare" /></div>
				<div id="bshareUuid" class="cloud-bshare-uuidtext"></div>
				<span class="cloud-empty"></span>
				<div id="bshareSetting" class="cloud-setting">
					<ul class="bshare-setting" style="display:none;">
						<li onclick="bshare.bind();">修改</li>
						<li onclick="bshare.logout();">登出</li>
					</ul>
				</div>
				<span class="cloud-empty"></span>
				<button class="cloud-btn-red-60" onclick="ct.assoc.open('http://www.bshare.cn/moreStylesEmbed?uuid='+bshare.uuid, 'newtab')">获取代码</button>
				<span class="cloud-empty"></span>
				<button class="cloud-btn-red-60" onclick="ct.assoc.open('?app=cloud&controller=bshare&action=dashboard', 'newtab')">查看统计</button>
				<input class="button_style_1" type="button" value="保存" onclick="shareSave();" style="float:right;" />
			</div>
			<?php else:?>
			<div class="cloud-share-line">
				<div class="bk_10"></div>
				<input class="button_style_2" type="button" value="保存" onclick="shareSave();" style="float:right;" />
			</div>
			<?php endif;?>
		</div>
	</div>
	<div class="cloud-box cloud-hide">
		<div class="cloud-banner">
			<div class="cloud-banner-ico"></div>
			<div class="cloud-banner-setting"></div>
			<div class="cloud-switch"><input class="switch" type="checkbox" name="thirdlogin" value="1"<?php if($thirdlogin):?> checked="checked"<?php endif;?> /></div>
			<h3>合作网站账号绑定</h3>
		</div>
		<div class="cloud-panel cloud-bg-white">
			<div class="cloud-panel-arrow"></div>
			<table id="thirdlogin-table" width="96%" cellspacing="0">
				<thead style="display:none;">
					<tr><td width="100">排序</td><td width="380">合作网站名称</td><td width="120">状态</td><td width="160">管理</td></tr>
				</thead>
				<tbody id="thirdlogin-item"></tbody>
			</table>
			<div class="bk_10"></div>
			<div class="table_head"><input class="button_style_2" type="button" value="新增" onclick="thirdLoginAdd();"></div>
		</div>
	</div>
	<div class="cloud-box cloud-hide">
		<div class="cloud-banner">
			<div class="cloud-banner-ico"></div>
			<div class="cloud-banner-setting"></div>
			<div class="cloud-switch"><input class="switch" type="checkbox" name="onekeyfollow" value="1"<?php if($onekeyfollow):?> checked="checked"<?php endif;?> /></div>
			<h3>一键关注</h3>
		</div>
		<div class="cloud-panel cloud-bg-white">
			<div class="cloud-panel-arrow"></div>
			<div class="bk_10"></div>
			<div class="cloud-onekeyfollow-textarea">
				<textarea id="onekeyfollow"><?php echo $follow_data;?></textarea>
			</div>
			<div class="bk_10"></div>
			<div class="cloud-share-line">
				<input class="button_style_2" type="button" style="float:right;" onclick="onekeyfollowSave();" value="保存">
			</div>
		</div>
	</div>
	<div id="sohuchangyan" class="cloud-box cloud-hide">
		<div class="cloud-banner">
			<div class="cloud-banner-ico"></div>
			<div class="cloud-banner-setting" data-first="<?php echo $sohu_changyan_first ? 1 : 0;?>"></div>
			<div class="cloud-switch"><input class="switch" type="checkbox" name="sohu_changyan" value="1"<?php if($sohu_changyan):?> checked="checked"<?php endif;?> /></div>
			<h3>搜狐畅言评论</h3>
		</div>
		<div class="cloud-panel cloud-bg-white">
			<div class="cloud-panel-arrow"></div>
			<div class="cloud-panel-context">
				<div class="bk_10"></div>
					<form id="changyanCodeForm" action="?app=cloud&controller=changyan&action=code" method="post" onsubmit="saveChangyanCode(this);return false;" <?php if (empty($sohu_changyan_user)):?> style="display:none;"<?php endif;?>>
						<div class="cloud-share-textarea">
							<textarea id="changyanCode" name="code"><?php echo $sohu_changyan_code;?></textarea>
						</div>
						<div class="bk_10"></div>
						<div class="cloud-share-line">
							<button class="f_r button_style_2">保存</button>
							<span class="mar_l_4">当前用户：<span id="changyanUser"><?php echo $sohu_changyan_user['user'];?></span>@cmstop</span>
							<a class="mar_l_16" href="javascript:logoutChangyan();">登出</a>
						</div>
						<div class="cloud-share-line">
							<a class="f_l mar_l_4" href="javascript:getChangyanCode();">重新获取畅言评论代码</a>
							<a class="f_l mar_l_16" href="?app=cloud&controller=changyan&action=jump" target="_blank">跳转至搜狐畅言后台</a>
						</div>
					</form>
					<form id="changyanRegisterForm" action="?app=cloud&controller=changyan&action=register" method="post" onsubmit="registerChanyan(this);return false;" AUTOCOMPLETE="off"<?php if (!empty($sohu_changyan_user)):?> style="display:none;"<?php endif;?>>
						<div><label>用户名: <input class="cloud-input-text mar_l_8" type="text" name="user" /></label></div>
						<div class="bk_10"></div>
						<div><label>密　码: <input class="cloud-input-text mar_l_8" type="password" name="password" /></label></div>
						<div class="bk_10"></div>
						<div><input class="button_style_2 mar_l_50" type="submit" value="提交"></div>
					</form>
			</div>
		</div>
	</div>
    <div id="mobileVerification" class="cloud-box cloud-hide">
        <div class="cloud-banner">
            <div class="cloud-banner-ico"></div>
            <div class="cloud-banner-setting"></div>
            <div class="cloud-switch"><input class="switch" type="checkbox" name="mobile_verification" value="1"<?php if($mobile_verification):?> checked="checked"<?php endif;?> /></div>
            <h3>手机验证</h3>
        </div>
        <div class="cloud-panel cloud-bg-white">
            <div class="cloud-panel-arrow"></div>
            <div class="cloud-panel-context">
                <div class="bk_10"></div>
                <div><label>当前余额 : <input class="mar_l_8" style="border: none;width: 50px" value="暂未获取" type="text" disabled id="getbalance" name="getbalance" /></label><a class="new-refresh mar_l_8" href="javascript:getbalance()"></a></div>
                <div class="bk_10"></div>
                <form id="changyanRegisterForm" action="?app=cloud&controller=mverification&action=save" method="post" onsubmit="mobileVerification(this);return false;">
                    <div><label>请求地址 : <input class="cloud-input-text mar_l_8" type="text" value="<?=$mobile_verification_info['requestURL']?>" disabled="disabled" name="requestURL" /></label></div>
                    <div class="bk_10"></div>
                    <div><label>通讯密钥 : <input class="cloud-input-text mar_l_8" type="text" name="key" value="<?=$mobile_verification_info['key']?>" /></label></div>
                    <div class="bk_10"></div>
                    <div><label>短信内容 :
                    <div class="bk_10"></div>
                    <div class="cloud-onekeyfollow-textarea" style="width:610px"><textarea name="message"><?=$mobile_verification_info['message']?></textarea></div></div>
                    <div class="bk_10"></div>
                    <div><label><input class="button_style_2" type="submit" value="保存"></label></div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
<script type="text/javascript">
var thirdlogin_template = '<tr id="tl_{apiid}"><td class="thirdlogin_sort" width="100" style="cursor: move;"><span>{sort}</span></td><td width="380">{name}</td><td width="120" class="state">{state}</td><td width="160"><a href="javascript:;" onclick="thirdLoginEdit({apiid});">配置</a> <a class="set_thirdlogin_state" href="javascript:;" onclick="set_state($(this)); return false;">禁用</a></td></tr>';
</script>