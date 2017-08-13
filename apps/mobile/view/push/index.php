<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/push.css" />
<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<div class="bk_8"></div>
<div class="tag_1">
    <ul class="tag_list">
        <li class="active"><a href="javascript:void(0);">消息推送</a></li>
        <li><a href="?app=mobile&controller=push&action=log">推送记录</a></li>
    </ul>
</div>

<div class="push-container">
    <form method="POST" action="?app=mobile&controller=push&action=push">
        <div class="push-sidebar">
            <div class="push-row">
                <input type="submit" data-stage="2" class="button-primary button-block" value="推送" />
                <div class="mar_t_10">
                    <span class="ui-label"><label><input type="radio" name="stage" value="2" checked="checked" /> 正式环境</label></span>
                    <span class="ui-label"><label><input type="radio" name="stage" value="1" /> 测试环境</label></span>
                </div>
            </div>
            <!--div class="push-row">
                <label style="cursor:pointer"><input class="checkbox_style" style="vertical-align:baseline;" onclick="this.checked ? $('#pushed').css('visibility', 'visible').removeAttr('disabled') : $('#pushed').css('visibility', 'hidden').attr('disabled','disabled')" type="checkbox" />定时推送&nbsp;&nbsp;</label>
                <input style="visibility:hidden;" type="text" id="pushed" class="input_calendar" name="sendtime" value="<?=date('Y-m-d H:i:s')?>" onclick="DatePicker(this,{'format':'yyyy-MM-dd HH:mm:ss'});"/>
            </div-->
            <div class="push-row">
                <div class="push-platform-container">
                    <div class="push-platform-header">推送目标</div>
                    <div class="push-platform-body">
                        <span class="message"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="push-content">
            <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
                <tbody>
                <tr>
                    <th width="85">推送标题：</th>
                    <td>
                        <div id="push-title" class="ui-related-title">
                            <span class="ui-shortips" title="仅对 android 客户端有效"><span style="color:#df0000; margin-right:3px;">*</span>仅Android平台有效</span>
                            <input type="text" name="title" size="30" data-maxlength="20" style="width:350px;" />
                            <input type="button" id="pick-content" class="button_style_1" value="选取内容" />
                            <input type="hidden" name="contentid" value="<?=$contentid?>" />
                            <input type="hidden" name="modelid" value="<?=$modelid?>" />
                            <div id="row-reference" class="related-item">
                                关联内容：<a href="javascript:void(0);" target="_blank"></a>
                                <a class="hand" href="javascript:void(0);">编辑原内容</a>
                                <a class="hand" href="javascript:void(0);">取消关联</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><span class="c_red">*</span> 推送内容：</th>
                    <td>
                        <div id="push-message">
                            <textarea name="message" cols="30" rows="10" data-maxlength="60" style="width:590px;height:150px;resize:vertical;"></textarea>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.maxlength.js"></script>
<script type="text/javascript" src="apps/mobile/js/push.js"></script>