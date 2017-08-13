<table style="border:1px solid #030303;padding:5px;border-collapse:collapse;">
    <?php
        $td_style='style="border:1px solid #030303;padding:5px;"';
    ?>
    <caption><h2><?=$title?></h2></caption>
    <tr>
        <th <?=$td_style?>>域名</th>
        <th <?=$td_style?>>主机名</th>
        <th <?=$td_style?>>IP</th>
        <th <?=$td_style?>>配置文件</th>
        <th <?=$td_style?>>网站主目录</th>
        <th <?=$td_style?>>写权限</th>
        <th <?=$td_style?>>PHP</th>
        <th <?=$td_style?>>PHP访问目录</th>
        <th <?=$td_style?>>SSI支持</th>
        <th <?=$td_style?>>类型</th>
        <th <?=$td_style?>>安全评级</th>
    </tr>
    <?php foreach($domains as $domain) :?>
    <tr>
        <td <?=$td_style?>><?=$domain['domain']?></td>
        <td <?=$td_style?>><?=$domain['hostname']?></td>
        <td <?=$td_style?>><?=$domain['ip']?></td>
        <td <?=$td_style?>><?=$domain['conf']?></td>
        <td <?=$td_style?>><?=$domain['directory']?></td>
        <td <?=$td_style?>><?=$domain['writable']?></td>
        <td <?=$td_style?>><?=$domain['php_exec']?></td>
        <td <?=$td_style?>><?=$domain['opendir']?></td>
        <td <?=$td_style?>><?=$domain['ssi']?></td>
        <td <?=$td_style?>><?=$domain['name']?></td>
        <td <?=$td_style?>><?=$domain['level']?></td>
    </tr>
    <?php endforeach;?>
</table>

<p style="color:red;">
    CmsTop安全小卫士温馨提示：<br/>
    为了服务器的安全，请保密此邮件信息。
</p>