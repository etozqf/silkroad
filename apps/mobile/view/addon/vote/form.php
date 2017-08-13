<style type="text/css">
.mod-content-form th {
    color: #077AC7;
}
.mod-content-form .table_info .hand {
    margin: 0;
    vertical-align: middle;
}
.mod-content-form .icon-action-wrap {
    position: relative;
}
.mod-content-form .icon-action {
    display: block;
    width: 16px;
    height: 16px;
    overflow: hidden;
    outline: none;
    cursor: pointer;
    background: #FFF url(css/images/model.gif) no-repeat 0 0;
    position: absolute;
    right: 7px;
    top: 3px;
}
.mod-content-form .icon-action-link {
    background-position: 0 -100px;
}
.mod-content-form .icon-action-thumb {
    background-position: 0 -74px;
}
</style>
<form action="">
    <input type="hidden" name="tabs" value="0" />
    <div class="mod-tabs">
        <div class="tabs-trigger" data-tabs="triggers">
            <ul>
                <li class="tabs-trigger-item" data-tabs="trigger-item">添加</li>
                <li class="tabs-trigger-item" data-tabs="trigger-item">选择</li>
            </ul>
        </div>
        <div class="tabs-content" data-tabs="contents">
            <div class="tabs-content-item" data-tabs="content-item">
                <div class="mod-content-form">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <th width="60"><span class="c_red">*</span> 栏目：</th>
                            <td>
                                <input data-role="catid" name="catid" width="150"
                                       url="?app=system&controller=category&action=cate&dsnid=&catid=%s"
                                       initUrl="?app=system&controller=category&action=name&catid=%s"
                                       paramVal="catid"
                                       paramTxt="name"
                                       value="<?=$catid?>"
                                       ending="1"
                                       alt="选择栏目" />
                                <input type="hidden" name="catname" />
                            </td>
                        </tr>
                        <tr>
                            <th><span class="c_red">*</span> 标题：</th>
                            <td>
                                <?=element::title('title', '', '', 30, 80, '450px')?>
                            </td>
                        </tr>
                        <tr>
                            <th><span class="c_red">*</span> 缩略图：</th>
                            <td>
                                <?=element::image('thumb')?>
                            </td>
                        </tr>
                        <tr>
                            <th><span class="c_red">*</span> 类型：</th>
                            <td>
                                <label><input type="radio" name="type" value="radio" checked="checked" /> 单选</label>
                                <label><input type="radio" name="type" value="checkbox" /> 多选</label>
                                <span style="visibility:hidden;">最多可选 <input type="text" value="" size="2" name="maxoptions"> 项</span>
                            </td>
                        </tr>
                        <tr>
                            <th><span class="c_red">*</span> 选项：</th>
                            <td>
                                <table class="table_info" width="100%" cellpadding="0" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="50" title="拖动以排序">排序</th>
                                            <th>选项</th>
                                            <th width="110">链接</th>
                                            <th width="110">图片</th>
                                            <th width="60">初始票数</th>
                                            <th width="40">操作</th>
                                        </tr>
                                    </thead>
                                    <tbody id="vote-option-placeholder">
                                    </tbody>
                                </table>
                                <div class="bk_5"></div>
                                <button class="button_style_4" data-role="add-option">增加选项</button>
                            </td>
                        </tr>
                        <tr>
                            <th>模式：</th>
                            <td class="lh_24">
                                <label><input onclick="$(this).parent().siblings('span').css('visibility', 'hidden');" name="display" type="radio" value="list" checked="checked" class="checkbox_style" /> 普通模式</label>
                                <label><input onclick="$(this).parent().siblings('span').css('visibility', 'visible');" name="display" type="radio" value="thumb" class="checkbox_style" /> 评选模式</label>
                                <span style="visibility:hidden;">
                                    图片大小：<input type="text" placeholder="宽" name="thumb_width" size="3" value="180" /> x <input type="text" placeholder="高" name="thumb_height" size="3" value="135" /> px
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="tabs-content-item" data-tabs="content-item">
                <div id="item-placeholder">
                    <div class="mod-content-empty">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <button class="button_style_4" data-role="pick">选择投票</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/template" id="item-template">
    <div class="mod-content">
        <input type="hidden" name="contentid" value="{%contentid%}" />
        <input type="hidden" name="thumb" value="{%thumb%}" />
        <input type="hidden" name="url" value="{%url%}" />
        <input type="hidden" name="title" value="{%title%}" />
        <input type="hidden" name="date" value="{%date%}" />
        <input type="hidden" name="catid" value="{%catid%}" />
        <textarea name="catid_html" style="display:none;">{%catid_html%}</textarea>
        <div class="content-thumb">
            <a data-role="url" href="{%url%}" target="_blank"><img src="{%thumb ? thumb : (IMG_URL + 'images/nopic.gif')%}" alt="" /></a>
        </div>
        <div class="content-box">
            <h4 title="{%title%}"><a data-role="url" href="{%url%}" target="_blank">{%title%}</a></h4>
            <p class="date">{%date%}</p>
            <div class="category">
                {%decodeURIComponent(catid_html)%}
            </div>
        </div>
        <div class="content-action">
            <button class="button_style_4" data-role="pick">重新选择</button>
        </div>
    </div>
</script>
<script type="text/template" id="tpl-vote-option">
    <tr class="vote-option">
        <td width="50" class="t_c" style="cursor: move;" data-role="sort">1</td>
        <td class="t_c">
            <input type="text" maxlength="100" size="28" value="{%name%}" name="name" />
        </td>
        <td width="110" class="t_c">
            <div class="icon-action-wrap">
                <span class="icon-action icon-action-link" data-role="add-link"></span>
                <input type="text" size="15" value="{%link%}" name="link" tips="{%link%}" />
            </div>
        </td>
        <td width="110" class="t_c">
            <div class="icon-action-wrap">
                <span class="icon-action icon-action-thumb" data-role="add-thumb"></span>
                <input type="text" size="15" value="{%thumb%}" name="thumb" />
            </div>
        </td>
        <td width="60" class="t_c">
            <input type="text" size="5" value="{%votes%}" name="votes" />
        </td>
        <td width="40" class="t_c">
            <img data-role="remove-option" width="16" height="16" class="hand" title="删除" alt="删除" src="images/del.gif">
        </td>
    </tr>
</script>