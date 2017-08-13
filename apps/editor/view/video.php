<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#media_dlg.title}</title>

	<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.js"></script>
	<script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
	<script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>
	<link rel="stylesheet" type="text/css" href="css/admin.css"/>
	<script type="text/javascript" src="uploader/cmstop.uploader.js"></script>

	<script type="text/javascript" src="tiny_mce/tiny_mce_popup.js"></script>
	<script type="text/javascript" src="tiny_mce/plugins/media/js/media.js"></script>
	<script type="text/javascript" src="tiny_mce/utils/mctabs.js"></script>
	<script type="text/javascript" src="tiny_mce/utils/validate.js"></script>
	<script type="text/javascript" src="tiny_mce/utils/form_utils.js"></script>
	<script type="text/javascript" src="tiny_mce/utils/editable_selects.js"></script>

	<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.dropdown.js"></script>
	<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
	<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.contextMenu.js"></script>
	<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/contextMenu/style.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/dropdown/style.css"/>
	<link href="tiny_mce/plugins/media/css/media.css" rel="stylesheet" type="text/css" />

	<!--selectree-->
	<script src="<?=IMG_URL?>js/lib/cmstop.selectree.js"></script>
	<link rel="stylesheet" href="<?=IMG_URL?>js/lib/selectree/selectree.css">
	<script src="<?=IMG_URL?>js/lib/cmstop.autocomplete.js" type="text/javascript"></script>
</head>
<body style="display: none" role="application">
<form onsubmit="Media.insert();return false;" action="#">
		<div class="tabs" role="presentation">
			<ul class="tag_list">
				<li id="general_tab" class="current" aria-controls="general_panel"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');Media.formToData();" onmousedown="return false;">{#media_dlg.general}</a></span></li>
				<li id="source_tab" aria-controls="source_panel"><span><a href="javascript:mcTabs.displayTab('source_tab','source_panel');Media.formToData('source');" onmousedown="return false;">插入代码</a></span></li>
				<li id="mediadepot_tab"><span><a href="javascript:mcTabs.displayTab('mediadepot_tab','mediadepot_panel');" onMouseDown="return false;">媒体库</a></span></li>
			</ul>
		</div>

		<div class="panel_wrapper">
			<div id="general_panel" class="panel current">
				<fieldset>
					<legend>{#media_dlg.general}</legend>

					<table role="presentation" border="0" cellpadding="4" cellspacing="0">
							<tr>
								<td><label for="media_type">{#media_dlg.type}</label></td>
								<td>
									<select id="media_type"></select>
								</td>
							</tr>
							<tr>
							<td><label for="src">{#media_dlg.file}</label></td>
								<td>
									<table role="presentation" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td><input id="src" name="src" type="text" value="" class="mceFocus" onchange="Media.formToData();" /></td>
										<td id="filebrowsercontainer">&nbsp;</td>
										<td><div id="uploadvideo" class="uploader"></div></td>
									</tr>
									</table>
								</td>
							</tr>
							<tr id="linklistrow">
								<td><label for="linklist">{#media_dlg.list}</label></td>
								<td id="linklistcontainer"><select id="linklist"><option value=""></option></select></td>
							</tr>
							<tr>
								<td><label for="width">{#media_dlg.size}</label></td>
								<td>
									<table role="presentation" border="0" cellpadding="0" cellspacing="0">
										<tr>
											<td><input type="text" id="width" name="width" value="" class="size" onchange="Media.formToData('width');" onfocus="Media.beforeResize();" /> x <input type="text" id="height" name="height" value="" class="size" onfocus="Media.beforeResize();" onchange="Media.formToData('height');" /></td>
											<td>&nbsp;&nbsp;<input id="constrain" type="checkbox" name="constrain" class="checkbox" checked="checked" /></td>
											<td><label id="constrainlabel" for="constrain">{#media_dlg.constrain_proportions}</label></td>
										</tr>
									</table>
								</td>
							</tr>
					</table>
				</fieldset>

				<fieldset>
					<legend>{#media_dlg.preview}</legend>
					<div id="prev"></div>
				</fieldset>
			</div>

			
			<div id="source_panel" class="panel">
				<fieldset>
					<legend>插入代码</legend>
					<textarea id="source" style="width: 99%; height: 290px"></textarea>
				</fieldset>
			</div>
			<div id="mediadepot_panel" class="panel"></div>
		</div>

		<div class="mceActionPanel">
			<input type="submit" id="insert" name="insert" value="{#insert}" />
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
		</div>
	</form>
</body>
</html>
