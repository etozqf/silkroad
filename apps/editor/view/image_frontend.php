<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>插入图片</title>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="<?=IMG_URL?>js/lib/jquery-ui/dialog.css" />
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.ui.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/config.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/cmstop.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.dialog.js"></script>
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.cookie.js"></script>

    <script type="text/javascript" src="<?=APP_URL?>uploader/cmstop.uploader.js"></script>
    <script type="text/javascript" src="<?=APP_URL?>js/tiny_mce/tiny_mce_popup.js"></script>
    <script type="text/javascript" src="<?=APP_URL?>js/tiny_mce/utils/mctabs.js"></script>
    <script type="text/javascript" src="<?=APP_URL?>js/tiny_mce/utils/form_utils.js"></script>
    <script type="text/javascript" src="<?=APP_URL?>js/tiny_mce/utils/editable_selects.js"></script>
    <script type="text/javascript" src="<?=APP_URL?>js/tiny_mce/plugins/ct_image/js/dialog.js"></script>
    <link href="<?=APP_URL?>js/tiny_mce/plugins/ct_image/css/dialog.css" rel="stylesheet" type="text/css" />
    <style  type="text/css">
        body{background-color:#FFFFFF}
        fieldset{ margin:0; padding:4px;}
        .mceActionPanel{margin: 0 8px;}
        .upload-progress {display:none!important;}
    </style>
    <script type="text/javascript">
        var UPLOAD_MAX_FILESIZE = '<?php echo $upload_max_filesize; ?>';
    </script>
<body id="advimage" style="display: none">
<form onsubmit="CmsTopImageDialog.insert();return false;" action="#">
    <div class="panel_wrapper">
        <div id="general_panel" class="panel current">
            <fieldset>
                <table class="properties">
                    <tr>
                        <td width="50"><label for="src">图片地址</label></td>
                        <td><input id="src" name="src" title="输入或上传" type="text" style="width:99%" value="" /></td>
                        <td width="85"><span id="upload" style="width:80px;height:18px;text-align:center;background:#F8F8F8;border:solid 1px #CCC;float:left;margin-top:1px;cursor:pointer;">上传图片</span></td>
                    </tr>
                    <tr>
                        <td><label for="width">图片大小</label></td>
                        <td colspan="2">
                            <input id="width" name="width" title="宽" type="text" size="5" value="" /> x
                            <input name="height" title="高" type="text" size="5" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td><label for="alt">图片说明</label></td>
                        <td colspan="2"><input id="alt" name="alt" type="text" style="width:99%" value="" /></td>
                    </tr>
                </table>
            </fieldset>
        </div>
    </div>
    <div class="mceActionPanel">
        <input type="submit" class="button"   name="insert" value="插入" style="margin-right:5px;" />
        <input type="button" class="button"   name="cancel" value="取消" onclick="tinyMCEPopup.close();" />
    </div>
</form>
</body>
</html> 
