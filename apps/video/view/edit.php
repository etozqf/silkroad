<?php $this->display('header');?>
<style type="text/css">
    .check-repeat-panel .icon {background: url(<?=IMG_URL?>js/lib/dropdown/bg.gif) no-repeat scroll 0 -50px transparent;	margin-right: 8px;	width: 16px;height: 20px;float: left;}
    #videolist_info{
        background:#F9FCFD;
        border:1px solid #9AC4DC;
        margin-right:10px;
        color: #0033FF;
        width:375px;
        padding:2px 0 4px 5px;
        display:inline;
    }
    #videolist_info img{
        margin: 0;
        padding: 0;
    }
    #videolist_btn{
        display:inline;
    }
    .lineTextarea{
        width:443px;height:23px;line-height:23px;padding:0 3px;margin:0;
    }
</style>
<script type="text/javascript">
var get_tag = <?php echo $get_tag;?> + 0;
var maxUploadSize = <?php echo $upload_max_filesize;?> + 0;
var videoServerOpen = <?php echo ($openserver ? 1 : 0); ?>;
</script>

<form name="video_edit" id="video_edit" method="POST" class="validator" action="?app=video&controller=video&action=edit" enctype="multipart/form-data">
<input type="hidden" name="contentid" id="contentid" value="<?=$contentid?>" />
<input type="hidden" name="modelid" id="modelid" value="<?=$modelid?>">
<input type="hidden" name="old_listid" id="old_listid" value="<?=$listid?>">
<input type="hidden" name="aid" id="aid"  value="<?=$aid?>"/>
<? if($status == 6): ?>
<input type="hidden" name="url" id="url" value="<?=$url?>" />
<? endif; ?>
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
	<tr>
		<th width="80"><span class="c_red">*</span> 栏目：</th>
		<td><?=element::category('catid', 'catid', $catid)?></td>
	</tr>
	<tr>
		<th><span class="c_red">*</span> 标题：</th>
		<td>
			<?=element::title('title', $title, $color)?>
			<label><input type="checkbox" name="has_subtitle" id="has_subtitle" value="1" <?=($subtitle ? 'checked' : '')?> class="checkbox_style" onclick="show_subtitle()" /> 副题</label>
		</td>
	</tr>
	<tr id="tr_subtitle" style="display:<?=($subtitle ? 'table-row' : 'none')?>">
		<th>副题：</th>
		<td><input type="text" name="subtitle" id="subtitle" value="<?=$subtitle?>" size="100" maxlength="120" /></td>
	</tr>
	<tr>
		<th valign="top" ><div style="height:10px;width:100%"> </div><span class="c_red">*</span>视频：</th>
		<td>
            <table border="0" width="70%" >
                <tr>
                    <td><input type="text" name="video" id="video" value="<?=$video?>" size="60" /></td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td valign="bottom" height="30">
                                    <?php if($openserver) { ?>
                                        <button type="button" class="button_style_1" id="videoSelect">选择视频</button>
                                    <?php }else{ ?>
                                        <div id="videoInput" name='videoInput' class="uploader"/></div>
                                    <?php } ?>
                                </td>
                                <?php if(setting('cloud', 'spider_allowed')):?>
                                    <td valign="bottom" height="30">
                                        <button type="button" class="button_style_1" id="videoSearch">搜索视频</button>
                                    </td>
                                <?php endif;?>
                                <?php if(setting('cloud', 'v56_upload')):?>
                                <td valign="bottom" height="30">
                                    <button type="button" class="button_style_1" id="v56Uploader">56视频</button>
                                </td>
                                <script type="text/javascript">
                                $.getScript('apps/video/js/v56.js', function(){
                                    $('#v56Uploader').v56Uploader(function(json){
                                        if (!json.vid && json.msg) {
                                            return ct.warn(json['msg']);
                                        }
                                        $('#v56').val(json.vid);
                                        $('#title').val(decodeURIComponent(json['title']));
                                        $('#video').val(json['swf'] || '[v56]'+json.vid+'[/v56]');
                                        $('[name="thumb"]').val(json['thumb']);
                                        $('[name="source"]').val('56网');
                                    });
                                });
                                </script>
                                <input type="hidden" id="v56" name="v56" value="" />
                                <?php endif;?>
                                <td valign="bottom" height="30" id="thirdparty"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
		</td>
	</tr>
	<tr>
		<th>时长：</th>
		<td><input type="text" name="playtime" id="playtime" value="<?=$playtime?>" size="10"/> 秒</td></tr>
    </tr>
    <tr>
        <th>视频专辑：</th>
        <td class="c_077ac7">
            <div id="videolist_info">
                <input id="listid" name="listid" type="hidden" value="<?php echo $listid; ?>" />
                <span id="videolist_listname"></span>
                <span>&nbsp;<img src="images/delete.gif" alt="清除选择" id="videolist_no" style="cursor:pointer" /></span>
            </div>
            <div id="videolist_btn"><button type="button" class="button_style_1" id="videolist_select">选择专辑</button></div>
        </td>
    </tr>
    <tr>
        <th>简介：</th>
        <td><textarea name="description" id="description" cols="96" rows="4"><?=$description?></textarea></td>
    </tr>
    <tr>
        <th>Tags：</th>
        <td><?=element::tag('tags', $tags)?></td>
    </tr>
    <tr>
        <th>缩略图：</th>
        <td><?php echo element::image('thumb',$thumb,45);?></td>
    </tr>
    <tr>
        <th>来源：</th>
        <td class="c_077ac7">
            <input type="text" name="source" autocomplete="1" value="<?=$source?>" url="?app=system&controller=source&action=suggest&q=%s" />
            <label>
                <input id="reserved" type="checkbox" class="mar_l_8" /> 转载
            </label>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <label for="editor">编辑： </label>
            <input type="text" name="editor" value="<?=$editor?>" size="15" />
        </td>
    </tr>
    <tr class="source_panel">
        <th>来源标题：</th>
        <td class="c_077ac7">
            <input type="text" name="source_title" value="<?php echo $source_title;?>" class="source-link-input" />
        </td>
    </tr>
    <tr class="source_panel">
        <th>来源链接：</th>
        <td class="c_077ac7">
            <input id="source_link" type="url" name="source_link" value="<?php echo $source_link;?>" class="source-link-input" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a id="open_source_link" href="javascript:;">打开链接</a>
        </td>
    </tr>
    <tr>
        <th>属性：</th>
        <td><?=element::property("proid", "proids", $proids)?></td>
    </tr>
    <tr>
        <th><?=element::tips('权重将决定文章在哪里显示和排序')?> 权重：</th>
        <td>
            <?=element::weight($weight, $myweight);?>
        </td>
    </tr>
</table>

<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
    <tr>
        <th width="80"><?=element::tips('视频定时发布时间')?> 上线：</th>
        <input type="hidden" name="oldpublished" value="<?=$published?>" />
        <td width="170"><input type="text" name="published" id="published" class="input_calendar" value="<?=$published?>" size="20"/></td>
        <th width="80">下线：</th>
        <td><input type="text" name="unpublished" id="unpublished" class="input_calendar" value="<?=$unpublished?>" size="20"/></td>
    </tr>
    <?php if(priv::aca('system', 'related')): ?>
    <tr>
        <th class="vtop">相关：</th>
        <td colspan="3"><?=element::related($contentid)?></td>
    </tr>
    <?php endif;?>
    <tr>
        <th>评论：</th>
        <td colspan="3"><label><input type="checkbox" name="allowcomment" id="allowcomment" value="1" <?php if ($allowcomment) echo 'checked';?> class="checkbox_style"/> 允许</label></td>
    </tr>
    <tr>
        <th>状态：</th>
        <td colspan="3"><?=table('status', $status, 'name')?></td>
    </tr>
    <?php 
        $db = & factory::db();
        $arr = explode(',',$yuanquid);
        $res = '';
        $count = count($arr);
        foreach($arr as $k=>$v){
            if($k<$count-1){
                $res .= table('content',$v,'title').';';
            }elseif($k==$count-1){
                $res .= table('content',$v,'title');
            }
        }
        
        ?>
        <tr>
            <th class="vtop">所属园区：</th>
            <td><textarea type="text" value="" name="yuanqu" class=""><?=$res?></textarea></td>
            <td id="tishi" style="color:red;display:none"></td>
        </tr>
</table>
<script type="text/javascript">
        $(function(){
            $("textarea[name=yuanqu]").blur(function(){
                $("#tishi").css('display','none');
                var yuanqu = $(this).val();
                if(yuanqu){
                //发送ajax查询是否存在
                $.getJSON(APP_URL+'?app=system&controller=yuanqu&action=chaxun&jsoncallback=?&yuanqu='+yuanqu,function(data){
                        if(!data.state){
                            $("#tishi").css('display','block').html(data.error);
                        }
                
            });
            }
        })
    })
</script>
<div id="field" onclick="field.expand(this.id)" class="mar_l_8 hand title" title="点击展开" style="display:none;"><span class="span_close">扩展字段</span></div>
<table id="field_c" width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
</table>

<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form mar_l_8">
    <tr>
        <th width="80"></th>
        <td width="60">
			<input type="submit" value="保存" class="button_style_2" style="float:left"/>
		</td>
        <td width="60">
            <input type="button" value="预览" onclick="preview(<?=$contentid?>, <?=$catid?>, <?=$modelid?>)" class="button_style_2" style="float:left"/>
        </td>
        <td style="color:#444;text-align:left">按Ctrl+S键保存</td>
    </tr>
</table>
</div>
</form>

<?php $this->display('content/success', 'system');?>

<link href="<?=IMG_URL?>js/lib/autocomplete/style.css" rel="stylesheet" type="text/css" />
<link href="<?=IMG_URL?>js/lib/colorInput/style.css" rel="stylesheet" type="text/css" />
<script src="<?=IMG_URL?>js/lib/cmstop.autocomplete.js" type="text/javascript"></script>
<script src="<?=IMG_URL?>js/lib/cmstop.colorInput.js" type="text/javascript"></script>
<script type="text/javascript" src="apps/system/js/psn.js"></script>
<script type="text/javascript" src="js/related.js"></script>
<script type="text/javascript" src="apps/system/js/field.js" ></script>
<script type="text/javascript">
$(function(){
    if($('#videoSelect')){
        $('#videoSelect').click(function(){
            var d = ct.iframe({
                    title:'?app=video&controller=vms&action=index&selector=1',
                    width:600,
                    height:519
                },
                {
                    ok:function(r){
                        if($('#title').val() == '') $('#title').val(r.title);
                        if($('#tags').val() == '') tagInput.addItem(r.tags);
                        r.tags || $('#title').trigger('change');
                        $('#video').val('[ctvideo]'+r.id+'[/ctvideo]');
                        $('#playtime').val(r.playtime);
                        $('[name="thumb"]').val(r.pic).trigger('change');
                        d.dialog('close');
                    }
                });
        });
    }
    $('#videoSearch').click(function(){
        var ifm = ct.iframe({
            'url':'?app=video&controller=search',
            'width':800,
            'height':464
        }, {
            'ok':function(response, url) {
                if (response.state) {
                    $('#title').val(response.title);
                    $('#video').val(response.content.url);
                    $('[name="thumb"]').val(response.content.thumb);
                    $('#playtime').val(response['time']);
                    $('#source_link').val(url).trigger('change');
                }
                ifm.dialog('close');
            }
        });
    });
    if($('#videoInput')){
        $("#videoInput").uploader({
            script         : '?app=video&controller=video&action=upload',
            fileDataName   : 'ctvideo',
            fileDesc		 : '视频',
            fileExt		 : '*.swf;*.flv;*.avi;*.wmv;*.rm;*.rmvb;',
            buttonImg	 	 :'images/videoupload.gif',
            sizeLimit      : maxUploadSize,
            multi:false,
            complete:function(response,data){
                var aidaddr=response.split('|');
                $("#aid").val(aidaddr[0]);
                aidaddr[1]=UPLOAD_URL+aidaddr[1];
                $("#video").val(aidaddr[1]);
            },
            error:function(data){
                var maxsize = maxUploadSize;
                var m = maxsize/(1024*1024);
                if(data.file.size>maxsize)
                    ct.warn('视频大小不得超过'+m+'M');
            }
        });
    }
    var videolist_info = $('#videolist_info');
    var videolist_select = $('#videolist_select');
    var videolist_no = $('#videolist_no');
    videolist_select.click(function(){
        var d = ct.iframe({
                title:'?app=video&controller=videolist&action=selector',
                width:580,
                height:'auto'
            },
            {
                ok:function(r){
                    $('#listid').val(r.listid);
                    $('#videolist_listname').html(r.listname);
                    videolist_info.show();
                    videolist_select.html('重新选择');
                    d.dialog('close');
                }
            });
    });
    videolist_no.click(function(){
        $('#listid').val(0);
        $('#videolist_listname').html('');
        videolist_info.hide();
        videolist_select.html('选择专辑');
    });
    videolist_info.hide();
    if($('#listid').val()){
        $.getJSON('?app=video&controller=videolist&action=getname&listid='+$('#listid').val(),function(json){
            if(json && json.data){
                $('#videolist_listname').html(json.data.listname);
                videolist_info.show();
            }
        });
    }
    // thirdparty
    var thirdpartyDiv = $('#thirdparty');
    $.getJSON('?app=video&controller=thirdparty&action=getlist', function(json){
        if(json && json.state){
            for(var i=0;i<json.data.length;i++){
                thirdpartyDiv.append('<button type="button" class="button_style_1" value="'+json.data[i].id+'">'+json.data[i].title+'</button>');
            }
        }
        thirdpartyDiv.find('button').bind('click', function(){
            var id = $(this).attr('value');
            var d = ct.iframe({
                    title:'?app=video&controller=thirdparty&action=selector&id='+id,
                    width:800,
                    height:469
                },
                {
                    ok:function(r){
                        if($('#title').val() == '') $('#title').val(r.title);
                        if($('#tags').val() == '') tagInput.addItem(r.tags);
                        r.tags || $('#title').trigger('change');
                        $('#video').val(r.video);
                        $('#playtime').val(r.time);
                        $('[name="thumb"]').val(r.thumb).trigger('change');
                        d.dialog('close');
                    }
                });
        });
    });
});

// 获取自定义字段
$(function() {
    var catid = $('#catid');
    function categoryReady() {
        catid.data('selectree') && catid.data('selectree').bind('checked', function(item) {
            item.catid && field.getbycid(<?=$contentid?>, item.catid);
            item.catid && content.isModelEnabled(item.catid);
        });
    }
    if (catid.data('selectree')) {
        categoryReady();
    } else {
        catid.bind('categoryReady', categoryReady);
    }
    if(catid.val()) field.getbycid(<?=$contentid?>, catid.val());
});
//预览功能
var preview = function (contentid, catid, modelid) {
    window.open(APP_URL+'?app=system&controller=content&action=preview&contentid='+contentid+'&catid='+catid+'&modelid='+modelid);
}
</script>
<?php $this->display('footer');