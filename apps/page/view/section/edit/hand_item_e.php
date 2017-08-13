<link rel="stylesheet" href="apps/page/css/content.css" />
<div id="section-form">
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
    <div class="panel-right">
        <input type="button" id="pick-content" class="button_style_1" value="选取内容" />
        <div class="tabs">
            <ul>
                <li class="active">编辑内容</li>
            </ul>
        </div>

        <div id="section-form-list" class="section-form-list"></div>
    </div>
</div>
<textarea style="display:none;" id="tpl-form">
    <?=script_template('<form method="POST" name="section_hand_edit_{%index%}" action="?app=page&controller=section&action=edititem" class="section-form">
        <input type="hidden" name="sectionid" value="'.$sectionid.'" />
        <input type="hidden" name="row" value="'.$row.'" />
        <input type="hidden" name="col" value="'.$col.'" />
        <input type="hidden" name="recommendid" value="'.$item['recommendid'].'" />
        '.section_fields_form(decodeData($section['fields']), $item).'
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