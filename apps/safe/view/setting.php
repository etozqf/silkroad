<?php $this->display('header', 'safe');?>
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
        <caption>CmsTop系统程序安全设置</caption>
        <tr>
            <th width="100"><?=element::tips('Auth Key (32位，由字母大小写、数字、特殊符号的组合最佳)')?> Auth Key：</th>
            <td><?php echo $report['authkey'];?></td>
        </tr>
        <tr>
            <th>Cache前缀：</th>
            <td>
                <?php echo $report['cache'];?>
            </td>
        </tr>
        <tr>
            <th>Cookie前缀：</th>
            <td>
                <?php echo $report['cookie'];?>
            </td>
        </tr>
    </table>
    <div class="bk_10"></div>
    <form id="setting_edit_basic" action="?app=safe&controller=setting&action=edit" method="POST" class="validator">
        <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
            <caption>安全设置</caption>
            <tr>
                <th width="150"><?=element::tips('一行一条数据 下面相同')?> 禁止访问网站IP列表：</th>
                <td><textarea name="system[setting][ipbanned]" rows="6" cols="30" class="bdr"><?=$system_setting[ipbanned]?></textarea></td>
            </tr>
            <tr>
                <th>允许登录后台IP列表：</th>
                <td><textarea name="system[setting][ipaccess]" rows="6" cols="30" class="bdr"><?=$system_setting[ipaccess]?></textarea></td>
            </tr>
            <tr>
                <th>后台登录失败锁定：</th>
                <td><input id="maxloginfailedtimes" type="text" name="system[setting][maxloginfailedtimes]" value="<?=$system_setting[maxloginfailedtimes]?>" size="3"/> 次</td>
            </tr>
            <tr>
                <th>IP 锁定时间：</th>
                <td><input id="lockedhours" type="text" name="system[setting][lockedhours]" value="<?=$system_setting[lockedhours]?>" size="3"/> 小时</td>
            </tr>
            <tr>
                <th>页面刷新最短时间间隔：</th>
                <td><input id="minrefreshsecond" type="text" name="system[setting][minrefreshsecond]" value="<?=$system_setting[minrefreshsecond]?>" size="3"/> 秒</td>
            </tr>
            <tr>
                <th width="150">启用后台操作日志：</th>
                <td>
                    <input type="radio" name="system[setting][enableadminlog]" value="1" class="radio" <?php if ($system_setting[enableadminlog]) echo 'checked';?>/>是 &nbsp;&nbsp;
                    <input type="radio" name="system[setting][enableadminlog]" value="0" class="radio" <?php if (!$system_setting[enableadminlog]) echo 'checked';?>>否</td>
            </tr>
            <tr>
                <th>离开锁定时间：</th>
                <td>
                    <input type="text" name="system[setting][autolock]" value="<?php echo empty($system_setting[autolock]) ? 15 : $system_setting[autolock];?>" size="3" min="1" required /> 分钟
                </td>
            </tr>
            <tr>
                <th>安全邮箱：</th>
                <td>
                    <input type="text" name="system[setting][safetyemail]" value="<?=$system_setting[safetyemail]?>" size="30" required />
                </td>
            </tr>
        </table>
        <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
            <caption>系统域名安全报告设置</caption>
            <tr>
                <th width="100">接收邮箱：</th>
                <td><input id="email" type="text" name="safe[setting][email]" value="<?=$safe_setting[email]?>" size="30"/></td>
            </tr>
            <tr>
                <th></th>
                <td valign="middle">
                    <input type="submit" id="submit" value="保存" class="button_style_2"/>
                </td>
            </tr>
        </table>
    </form>

    <script type="text/javascript">
        $(function(){
            $('#setting_edit_basic').ajaxForm(function(json){
                if(json.state) ct.tips(json.message);
                else ct.error(json.error);
            });
            $('img.tips').attrTips('tips', 'tips_green', 200, 'top');
        });
    </script>
<?php $this->display('footer', 'safe');?>