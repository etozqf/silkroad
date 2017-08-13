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
            <tr data-role="pushto">
                <th>推送到页面：</th>
                <td><?=element::section($contentid)?></td>
            </tr>
            <tr data-role="pushto">
                <th>推送到专题：</th>
                <td><?=element::place($places)?></td>
            </tr>
             <tr class="pushto" data-role="pushtomobile">
                <th>推送到移动：</th>
                <td>
                    <input type="button" class="button_style_1" value="转发" />
                </td>
            </tr>
            <tr>
                <td colspan="2" class="t_c" data-role="buttons"></td>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    (function() {
        var models = {
            "1": 'article', 
            "2": 'picture',
            "4": 'video',
            "7": 'activity',
            "8": 'vote',
            "9": 'survey'
        },
        modelid = window.modelid;
        if (!models[modelid]) {
            return $('[data-role="pushtomobile"]').remove();
        }
        $('[data-role="pushtomobile"]').find('input').click(function() {
            content.pushToMobile(content.contentid, models[modelid]);
            return false;
        });
    })();
</script>