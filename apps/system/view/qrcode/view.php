<div class="bk_8"></div>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form qrcode-detail">
    <tr>
        <th width="70">链接地址：</th>
        <td><a href="<?=$qrcode['short_url']?>" target="_blank"><?=htmlspecialchars($qrcode['str'])?></a></td>
    </tr>
    <tr>
        <th>备注：</th>
        <td class="qrcode-detail-note"><p><?=nl2br($qrcode['note'])?></p></td>
    </tr>
    <tr>
        <th>访问量：</th>
        <td>
            <span class="ui-label"><?=$qrcode['pv']?></span>
        </td>
    </tr>
    <tr>
        <th style="vertical-align:top;">访问统计：</th>
        <td>
            <div id="qrcode-pie-container">
                <div id="qrcode-pie-overlay"></div>
                <div id="qrcode-pie"></div>
            </div>
        </td>
    </tr>
</table>
<div class="bk_5"></div>