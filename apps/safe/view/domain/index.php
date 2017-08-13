<?php $this->display('header', 'safe');?>
    <style type="text/css">
        .selectDiv {float: left;margin: 1px 8px 0 0;}
    </style>
    <!--tablesorter-->
    <link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css" />
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
    <!--pagination-->
    <link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css" />
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/jquery.pagination.js"></script>
    <!--list-->
    <link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/list/style.css"/>
    <script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.list.js"></script>

    <div class="bk_8"></div>
    <div class="table_head">
        <div class="f_r" style="padding-top: 3px;">最后检测时间：<span id="detecttime"> </span></div>
        <div class="tag_1">
            <ul class="tag_list">
                <li><a href="javascript:void(0);" value="0" class="s_3" id="tab_0">全部</a></li>
                <li><a href="javascript:void(0);" value="3" id="tab_3">安全</a></li>
                <li><a href="javascript:void(0);" value="2" id="tab_2">可疑</a></li>
                <li><a href="javascript:void(0);" value="1" id="tab_1">未知</a></li>
            </ul>
        </div>

    </div>
    <div class="bk_8"></div>
    <table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list" style="margin-left:6px;">
        <thead>
        <tr>
            <th width="150" class="bdr_3">
                <div name="domain">域名</div>
            </th>
            <th width="100">
                <div name="hostname">主机名</div>
            </th>
            <th width="100">
                <div name="ip">IP</div>
            </th>
            <th width="200">
                <div name="directory">网站目录</div>
            </th>
            <th width="40">
                <div name="writable">写权限</div>
            </th>
            <th width="60">
                <div name="php_exec">PHP支持</div>
            </th>
            <th width="200">
                <div name="opendir">PHP访问目录</div>
            </th>
            <th width="60">
                <div name="ssi">SSI支持</div>
            </th>
            <th width="120">
                <div name="type">类型</div>
            </th>
            <th width="60">
                <div name="level">安全评级</div>
            </th>
        </tr>
        </thead>
        <tbody id="list_body">
        </tbody>
    </table>

    <div class="table_foot">
        <div id="pagination" class="pagination f_r"></div>
        <div class="f_r"> 共<span id="pagetotal">{total}</span>条&nbsp;每页
            <input type="text" value="<?=$size?>" name="pagesize" size="2" id="pagesize"/> 条&nbsp;&nbsp;
        </div>
    </div>
    <div class="suggest mar_t_10 mar_l_8" style="width: 98%;">
        <h2>安全提醒</h2>
        <p>
            域名安全评级:<br/>
            1.可执行PHP的目录不可写，可写的目录不可执行PHP<br/>
            2.对于服务器内安装的其他系统默认为“未知”评级，当出现PHP可执行且目录可写时，给予“<span style="color:red">可疑</span>”评级<br/>
            3.本扫描基于类Unix系统的Shell脚本，需要服务器支持
        </p>
    </div>
    <script type="text/javascript">
        function json_loaded(json) {
            $('#pagetotal').html(json.total);
            $('#detecttime').html(json.detecttime);
        }
        var row_template = '<tr id="row_{domainid}">\
                	<td class="t_l">{domain}</td>\
                	<td class="t_c">{hostname}</td>\
                	<td class="t_c">{ip}</td>\
                	<td class="t_l">{directory}</td>\
                	<td class="t_c">{writable}</td>\
                	<td class="t_c">{php_exec}</td>\
                	<td class="t_c">{opendir}</td>\
                	<td class="t_c">{ssi}</td>\
                	<td class="t_c">{name}</td>\
                	<td class="t_c">{level}</td>\
                </tr>';

        var tableApp = new ct.table('#item_list', {
            rowIdPrefix : 'row_',
            rightMenuId : 'right_menu',
            pageField : 'page',
            pageSize : <?=$size?>,
            dblclickHandler : '',
            rowCallback     : 'init_row_event',
            template : row_template,
            baseUrl  : '?app=<?=$app?>&controller=<?=$controller?>&action=page',
            jsonLoaded: json_loaded
        });
    </script>
    <script type="text/javascript">
        $(function (){
            $('.tag_list').find("a").click(function(){
                tableApp.load('level=' + $(this).attr('value'));
                tab(this.id);
            });
            $('#pagesize').change(function (){
                tableApp.setPageSize(this.value);
                tableApp.load();
            });
        });
        function init_row_event(id, tr){}
        function tab(id) {
            $('.s_3').removeClass('s_3');
            $('#' + id).addClass('s_3');
        }
        tableApp.load();
    </script>
<?php $this->display('footer', 'safe');