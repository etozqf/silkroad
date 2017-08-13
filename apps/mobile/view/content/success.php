<div class="ct_tips success success-msg" id="success-msg">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <th colspan="2" style="text-align:center;"><strong data-role="message">恭喜，内容发布成功</strong></th>
            </tr>
            <tr>
                <th width="75">标题：</th>
                <td data-role="title"></td>
            </tr>
            <tr data-role="url-row">
                <th>查看内容：</th>
                <td><a data-role="url" href="" target="_blank"></a></td>
            </tr>
            <?php if (priv::aca('mobile', 'special', 'recommend')): ?>
            <tr>
                <th>推送到专题：</th>
                <td><?=$this->display('content/model/special/recommend_result')?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2" class="t_c" data-role="buttons"></td>
            </tr>
        </tbody>
    </table>
</div>