<div class="bk_10"></div>

<div class="suggest mar_l_8 mar_r_8" style="display:none;">
    <h2>友情提示</h2>
    <p> 批量导入功能是对在线视频上传方式的一种补充。允许通过磁盘复制，SCP复制，FTP传输等方式将视频文件批量传入指定目录来达到上传视频的目的。<br/><br/>
        使用步骤：<br/>
        1、通过任意一种方式如FTP,SSH将视频文件指上传至视频服务器上的data/ftp目录，此目录位于视频系统安装目录下的data/ftp，且不允许修改。<br/>
        2、上传完成后到CmsTop系统中的视频管理界面，点击“批量导入”，填写相应视频信息，点击确定后，即可将ftp目录中的视频加入转码队列。<br/><br/>
        注意事项：<br/>
        <font color=red>在文件上传过程中使用该功能将导致不完整的视频文件进入转码队列，请务必在上传结束后再使用。</font>
    </p>
</div>

<form method="POST" action="">
    <div id="importBox"></div>
</form>
