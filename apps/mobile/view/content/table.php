<div id="itemlist" class="ui-itemlist">
    <table class="ui-itemlist-header" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <th width="35" class="t_c"><input type="checkbox" title="全选" /></th>
            <th class="batch-actions">
                <?php if (mobile_priv::button('unpublish')): ?><input data-status="6" type="button" onclick="MobileContent.unpublish();" class="ui-btn-flat hidden" value="撤稿" /><?php endif; ?>
                <?php if (mobile_priv::button('publish')): ?><input data-status="4" type="button" onclick="MobileContent.publish();" class="ui-btn-flat hidden" value="发布" /><?php endif; ?>
                <?php if (mobile_priv::button('pass')): ?><input data-status="3" type="button" onclick="MobileContent.pass();" class="ui-btn-flat hidden" value="通过" /><?php endif; ?>
                <?php if (mobile_priv::button('restore')): ?><input data-status="0" type="button" onclick="MobileContent.restore();" class="ui-btn-flat hidden" value="还原" /><?php endif; ?>
                <?php if (mobile_priv::button('del')): ?><input data-status="0" type="button" onclick="MobileContent.del();" class="ui-btn-flat hidden" value="彻底删除" /><?php endif; ?>
                <?php if (mobile_priv::button('remove')): ?><input data-status="6,4,3" type="button" onclick="MobileContent.remove();" class="ui-btn-flat hidden" value="删除" /><?php endif; ?>
                &nbsp;
            </th>
            <th width="150" class="t_r">
                <?php if ($catid): ?><label><input type="checkbox" id="filter-slide" /><span id="filter-slide-text">幻灯片</span></label><?php endif; ?>
                <img id="btn-orderby" src="apps/mobile/images/order.png" width="16" height="16" title="排序方式" alt="排序方式" class="hand ui-itemlist-btn" />
            </th>
        </tr>
    </table>
    <div class="ui-itemlist-body"></div>
    <div class="ui-itemlist-footer">
        <div class="ui-itemlist-pager pagination"></div>
        <div class="ui-itemlist-actions">
            <input type="button" onclick="itemlist.reload();" class="ui-btn-flat" value="刷新" />
        </div>
    </div>
</div>
<script type="text/template" id="tpl-item-row">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr class="row-up">
            <td width="35" class="t_c" rowspan="2"><input type="checkbox" title="选择" /></td>
            <td colspan="5">
                <a title="查看"><div class="icon {model}"></div></a>
                <a class="item-title" data-tips="ID：{contentid}<br/>标题：{title}<br/>Tags：{tags}<br/>创建：{createdby_name}（{created_name}）<br/>修改：{updatedby_name}（{updated_name}）<br/>发布：{publishedby_name}（{published_name}）<br/>排序时间：{sorttime_name}<br/>访问量：{pv}<br/>评论量：{comments}">{title}</a>
            </td>
        </tr>
        <tr class="row-down">
            <td width="110">{created_name}</td>
            <td id="bbb"><span title="访问量：{pv||0}" class="item-pv">{pv||0}</span>/<span title="评论量：{comments||0}" class="item-comments">{comments||0}</span></td>
            <td width="75"><a class="item-username" href="javascript:url.member('{createdby}');" title="查看创建人 {createdby_name} 信息">{createdby_name}</a></td>
            <td width="80" class="t_r">
                <?php if (mobile_priv::button('edit')): ?><img src="images/edit.gif" class="hand edit" alt=""><?php endif; ?>
                {lock}
                <?php if (mobile_priv::button('remove')): ?><img src="images/delete.gif" class="hand remove" alt=""><?php endif; ?>
                <?php if (mobile_priv::button('del')): ?><img src="images/delete.gif" class="hand delete" alt=""><?php endif; ?>
                <img src="apps/mobile/images/popset.gif" class="hand popset" alt="">
            </td>
        </tr>
    </table>
</script>

<ul id="menu-orderby" class="contextMenu">
    <li class="default"><a href="#">默认排序</a></li>
    <li class="default"><a href="#created">创建时间</a></li>
    <li class="default" data-status="6"><a href="#published">发布时间</a></li>
    <li class="default" data-status="4"><a href="#unpublished">撤稿时间</a></li>
    <li class="default" data-status="0"><a href="#removed">删除时间</a></li>
    <li class="default"><a href="#pv">访问量</a></li>
    <li class="default"><a href="#comments">评论量</a></li>
</ul>

<ul id="menu-item" class="contextMenu">
    <?php if (mobile_priv::button('publish')): ?><li data-status="4" class="publish"><a href="#MobileContent.publish">发布</a></li><?php endif; ?>
    <?php if (mobile_priv::button('pass')): ?><li data-status="3" class="publish"><a href="#MobileContent.pass">通过</a></li><?php endif; ?>
    <?php if (mobile_priv::button('restore')): ?><li data-status="0" class="publish"><a href="#MobileContent.restore">还原</a></li><?php endif; ?>
    <?php if (mobile_priv::button('view')): ?><li class="view"><a href="#MobileContent.view">查看</a></li><?php endif; ?>
    <?php if (mobile_priv::button('edit')): ?><li class="edit"><a href="#MobileContent.edit">编辑</a></li><?php endif; ?>
    <?php if (mobile_priv::button('unpublish')): ?><li data-status="6" class="unpublish separator"><a href="#MobileContent.unpublish">撤稿</a></li><?php endif; ?>
    <?php if (mobile_priv::button('remove')): ?><li data-status="6,4,3" class="remove"><a href="#MobileContent.remove">删除</a></li><?php endif; ?>
    <?php if ($catid && mobile_priv::button('delete')): ?><li data-status="0" class="remove"><a href="#MobileContent.del">彻底删除</a></li><?php endif; ?>
    <?php if (priv::aca('mobile', 'special', 'recommend')): ?><li data-status="6" data-modelid="!10" class="push separator"><a href="#MobileContent.pushToSpecial">推送到专题</a></li><?php endif; ?>
    <?php if ($catid): ?>
    <?php if (priv::aca('mobile', 'content', 'slider_add')): ?><li data-status="6" data-inslider="0" class="addToSlider separator"><a href="#MobileContent.addToSlider">设为幻灯片</a></li><?php endif; ?>
    <?php if (priv::aca('mobile', 'content', 'slider_edit')): ?><li data-status="6" data-inslider="1" class="editSlider separator"><a href="#MobileContent.editSlider">修改幻灯片</a></li><?php endif; ?>
    <?php if (priv::aca('mobile', 'content', 'slider_remove')): ?><li data-status="6" data-inslider="1" class="addToSlider"><a href="#MobileContent.removeFromSlider">移出幻灯片</a></li><?php endif; ?>
    <?php if (priv::aca('mobile', 'content', 'bumpup')): ?><li data-status="6" class="bumpup"><a href="#MobileContent.bumpup">置顶</a></li><?php endif; ?>
    <?php else: ?>
    <?php if (priv::aca('mobile', 'content', 'bumpup')): ?><li data-status="6" class="bumpup separator"><a href="#MobileContent.bumpup">置顶</a></li><?php endif; ?>
    <?php endif; ?>
    <?php if (priv::aca('mobile', 'content', 'stick')): ?><li data-status="6" data-stick="0" class="stick"><a href="#MobileContent.stick">固顶</a></li><?php endif; ?>
    <?php if (priv::aca('mobile', 'content', 'unstick')): ?><li data-status="6" data-stick="1" class="stick"><a href="#MobileContent.unstick">取消固顶</a></li><?php endif; ?>
    <?php if (priv::aca('mobile', 'qrcode')): ?><li data-status="6" class="qrcode"><a href="#MobileContent.qrcode">生成二维码</a></li><?php endif; ?>
</ul>
