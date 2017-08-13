<link rel="stylesheet" href="apps/page/css/content.css" />
<div id="section-form">
    <?php if ($check): ?>
    <div class="panel-left">
        <div class="tabs" style="margin-bottom:0;">
            <ul>
                <li class="active">编辑推荐 <span data-role="check-count"></span></li>
                <li>已否决 <span data-role="delete-count"></span></li>
            </ul>
        </div>
        <div class="tab-contents">
            <div class="tab-content">
                <div class="scroll-list" id="check-list"></div>
            </div>
            <div class="tab-content" style="display: none;">
                <div class="scroll-list" id="delete-list"></div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="panel-right">
        <input type="button" id="pick-content" class="button_style_1" value="选取内容" />
        <input type="button" id="add-input" class="button_style_1" value="手工录入" />
        <div class="tabs">
            <ul>
                <li class="active">录入内容</li>
            </ul>
        </div>

        <div id="section-form-list" class="section-form-list"></div>
    </div>
</div>
<textarea style="display:none;" id="tpl-form">
    <?=script_template('<form method="POST" name="section_recommend_add_{%index%}" action="?app=page&controller=section_recommend&action=add" class="section-form">
        <div class="section-form-title">
            <img class="hand remove" src="images/del.gif" width="16" height="16" alt="" />
            <span class="index" data-role="index">{%index%}</span>
            <span data-role="title"></span>
        </div>
        <input type="hidden" name="sectionid" value="'.$sectionid.'"/>
        <input type="hidden" name="recommendid" value="" />
        '.section_fields_form(decodeData($section['fields'])).'
        <div class="section-form-overlay">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <td data-role="msg">添加成功</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>')?>
</textarea>
<textarea style="display:none;" id="tpl-row-check">
    <?=script_template('<div class="scroll-list-item" recommendid="{%recommendid%}">
        <h5 class="list-item-title content-model" tips="{%tips%}">
            <a href="{%url%}" target="_blank"><div class="icon {%modelname%}"></div></a>
            {%title%}
        </h5>
        <p>
            <img title="否决" src="images/del.gif" width="16" height="16" class="hand remove" alt="" />
            <span>{%recommended_name%}</span>
            <span><a href="javascript:void(0);" target="_self" onclick="if (url && url.member) { url.member(\'{%userid%}\'); } else { return false; }">{%recommendedby_name%}</a></span>
        </p>
    </div>')?>
</textarea>
<textarea style="display:none;" id="tpl-row-deleted">
    <?=script_template('<div class="scroll-list-item" recommendid="{%recommendid%}">
        <h5 class="list-item-title content-model" tips="{%tips%}">
            <a href="{%url%}" target="_blank"><div class="icon {%modelname%}"></div></a>
            {%title%}
        </h5>
        <p>
            <span>{%recommended_name%}</span>
            <span><a href="javascript:void(0);" target="_self" onclick="if (url && url.member) { url.member(\'{%userid%}\'); } else { return false; }">{%recommendedby_name%}</a></span>
        </p>
    </div>')?>
</textarea>