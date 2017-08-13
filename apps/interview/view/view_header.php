    <ul class="tag_list">
        <li><a href="?app=interview&controller=interview&action=view&contentid=<?=$contentid?>"<?php if($action=='view'):?> class="s_3"<?php endif;?>>查看</a></li>
        <?php if(priv::aca('interview', 'chat', 'index')):?>
        <li><a href="?app=interview&controller=chat&action=index&contentid=<?=$contentid?>"<?php if($controller=='chat' && $action=='index'):?> class="s_3"<?php endif;?>>文字实录</a></li>
        <?php endif;?>
        <?php if($status == 6 && priv::aca('interview', 'question', 'index')):?>
        <li><a href="?app=interview&controller=question&action=index&contentid=<?=$contentid?>"<?php if($controller=='question' && $action=='index'):?> class="s_3"<?php endif;?>>网友提问</a></li>
        <?php endif;?>
        <li><a href="?app=interview&controller=interview&action=view_notice&contentid=<?=$contentid?>"<?php if($action=='view_notice'):?> class="s_3"<?php endif;?>>滚动公告</a></li>
        <li><a href="?app=interview&controller=interview&action=view_review&contentid=<?=$contentid?>"<?php if($action=='view_review'):?> class="s_3"<?php endif;?>>精彩观点</a></li>
        <li><a href="?app=interview&controller=interview&action=view_picture&contentid=<?=$contentid?>"<?php if($action=='view_picture'):?> class="s_3"<?php endif;?>>图片报道</a></li>
    </ul>
    <?php
    if(priv::aca('interview', 'interview', 'edit')) {
        ?>
        <input type="button" name="edit" value="修改" class="button_style_1" onclick="content.edit(<?=$contentid?>)"/>
        <?php
    }
    if($status == 6 && priv::aca('interview', 'html', 'show')) {
        ?>
        <input type="button" name="createhtml" value="生成" class="button_style_1" onclick="content.createhtml(<?=$contentid?>)"/>
        <?php
    }
    if ($status < 6 && priv::aca('interview', 'interview', 'publish')) {
        ?>
        <input type="button" name="publish" value="发布" class="button_style_1" onclick="content.publish(<?=$contentid?>)"/>
        <?php
    }
    if ($status > 0 && priv::aca('interview', 'interview', 'remove')) {
        ?>
        <input type="button" name="remove" value="删除" class="button_style_1" onclick="content.remove(<?=$contentid?>)"/>
        <?php
    }
    if ($status == 0) {
        if (priv::aca('interview', 'interview', 'delete')){
            ?>
            <input type="button" name="delete" value="删除" class="button_style_1" onclick="content.del(<?=$contentid?>)"/>
            <?php
        }
        if (priv::aca('interview', 'interview', 'restore')) {
            ?>
            <input type="button" name="restore" value="还原" class="button_style_1" onclick="content.restore(<?=$contentid?>)"/>
            <?php
        }
    }
    if (($status == 1 || ($workflowid && $status == 2)) && priv::aca('interview', 'interview', 'approve')) {
        ?>
        <input type="button" name="approve" value="送审" class="button_style_1" onclick="content.approve(<?=$contentid?>)"/>
        <?php
    }
    if ($status == 3) {
        if (priv::aca('interview', 'interview', 'pass')) {
            ?>
            <input type="button" name="pass" value="通过" class="button_style_1" onclick="content.pass(<?=$contentid?>)"/>
            <?php
        }
        if (priv::aca('interview', 'interview', 'reject')) {
            ?>
            <input type="button" name="reject" value="退稿" class="button_style_1" onclick="content.reject(<?=$contentid?>)"/>
            <?php
        }
    }
    if (priv::aca('interview', 'interview', 'move')){
        ?>
        <input type="button" name="move" value="移动" class="button_style_1" onclick="content.move(<?=$contentid?>)"/>
        <?php
    }
    if (priv::aca('interview', 'interview', 'reference')){
        ?>
        <input type="button" name="reference" value="引用" class="button_style_1" onclick="content.reference(<?=$contentid?>)"/>
        <?php
    }
    ?>
    <input type="button" name="note" value="批注" class="button_style_1" onclick="content.note(<?=$contentid?>)"/>
    <input type="button" name="version" value="版本" class="button_style_1" onclick="content.version(<?=$contentid?>)"/>
    <input type="button" name="log" value="日志" class="button_style_1" onclick="content.log(<?=$contentid?>)"/>