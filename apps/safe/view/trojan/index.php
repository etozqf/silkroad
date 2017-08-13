<?php $this->display('header', 'safe');?>
    <link rel="stylesheet" type="text/css" href="apps/safe/css/trojan.css" />
    <div class="bk_8"></div>
    <div class="table_head">
        <div class="exclude">
            忽略目录：
            <label><input type="checkbox" class="mar_l_8" name="exclude" value="data" />&nbsp;缓存</label>
            <label><input type="checkbox" class="mar_l_8" name="exclude" value="public/img" />&nbsp;资源</label>
            <label><input type="checkbox" class="mar_l_8" name="exclude" value="public/upload" checked="checked"/>&nbsp;附件</label>
            <label><input type="checkbox" name="exclude" value="public/www" checked="checked"/>&nbsp;发布</label>
            &nbsp;&nbsp;<button class="button_style_1 control" type="button">开始扫描</button>
        </div>
        <button class="button_style_1 update_trojan" type="button" style="float:right;display:block;">点击更新</button>
        <div class="f_r trojan_version">
            <?php if($updatetime) {?>
                本地木马库最后更新时间：<span id="detecttime"><?=date("Y-m-d H:i:s", $updatetime)?></span>&nbsp;&nbsp;
            <?php } else {?>
                系统还未下载云端木马库&nbsp;&nbsp;
            <?php }?>
        </div>
    </div>
    <div class="bk_8"></div>
    <form>
    <table width="98%" class="table_list mar_l_8" cellspacing="0" cellpadding="0" style="empty-cells:show;table-layout:fixed;  ">
        <thead>
        <tr>
            <th width="60" class="bdr_3">No.</th>
            <th class="t_c">文件名</th>
            <th width="300">描述</th>
            <th width="300">木马特征</th>
            <th width="150">最后修改时间</th>
        </tr>
        </thead>
        <tbody class="output">
        </tbody>
    </table>
    </form>

    <div class="scan-box">
        <div class="progress-control">
            <div class="progress">
                <div class="bar">
                    <div class="percent">0%</div>
                    <div class="indicator"></div>
                </div>
            </div>
        </div>
        <div class="current"></div>
    </div>
    <div class="last-scan"></div>
    <script type="text/javascript">
        var trojan_version = "<?=$maxid?>";
    </script>
    <script type="text/javascript" src="apps/safe/js/trojan.js"></script>
<?php $this->display('footer', 'safe');?>