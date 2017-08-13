<?php $this->display('header', 'safe');?>
    <link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/tablesorter/style.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/pagination/style.css"/>
    <script type="text/javascript" src="<?=IMG_URL?>js/lib/cmstop.table.js"></script>
    <script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.pagination.js"></script>
    <!--contextmenu-->
    <link rel="stylesheet" type="text/css" href="<?php echo IMG_URL?>js/lib/contextMenu/style.css" />
    <script type="text/javascript" src="<?php echo IMG_URL?>js/lib/cmstop.contextMenu.js"></script>

    <script type="text/javascript" src="apps/safe/js/verify.js"></script>
    <div class="bk_8"></div>
    <div class="table_head">
        <div class="tag_1">
            <ul class="tag_list">
                <li><a href="javascript:void(0);" onclick="tableApp.load('status=3');tab('#tab_1');return false;" id="tab_1" class="s_3">全部</a></li>
                <li><a href="javascript:void(0);" onclick="tableApp.load('status=0');tab('#tab_2');return false;" id="tab_2">被修改文件</a></li>
                <li><a href="javascript:void(0);" onclick="tableApp.load('status=2');tab('#tab_3');return false;" id="tab_3">丢失文件</a></li>
                <li><a href="javascript:void(0);" onclick="tableApp.load('status=1');tab('#tab_4');return false;" id="tab_4">未知文件</a></li>
            </ul>
            <div class="f_l">
                <button class="button_style_4 check-start" type="button" style="margin-top:2px;">开始校验</button>
            </div>
            <?php
            if (!$exists) {
                ?>
                <div class="f_r create_file_notice">
                    您首次运行本工具程序，需要创建文件MD5库&nbsp;&nbsp;<button class="button_style_1 create_filemd5" type="button">点击生成</button>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <ul id="right_menu" class="contextMenu">
        <li class="edit">
            <a href="#verify.view">
                查看
            </a>
        </li>
    </ul>
    <!--头部结束-->
    <table width="98%" cellpadding="0" cellspacing="0" id="item_list" class="tablesorter table_list mar_l_8">
        <thead>
        <tr>
            <th width="3%" class="bdr_3"><input type="checkbox" id="check_all" class="checkbox_style"/></th>
            <th>文件名</th>
            <th width="13%">最后修改时间</th>
            <th width="13%">文件权限修改时间</th>
            <th width="10%">操作</th>
        </tr>
        </thead>
        <tbody id="list_body"></tbody>
    </table>
    <div class="table_foot">
        <div id="pagination" class="pagination f_r"></div>
        <p class="f_l">
            <input type="button" id="button" onclick="verify.deal(); return false;" value="合并" class="button_style_1" />
            <span class="result"></span>
        </p>
    </div>
    <script type="application/javascript">
        function tab(id) {
            $('.s_3').removeClass('s_3');
            $(id).addClass('s_3');
        }

        function init_row_event(id, tr) {
            tr.find('img.enable').bind('click', function(){
                verify.enable(id);
            });
            tr.find('img.del').bind('click', function(){
                verify.del(id);
            });
            tr.find('img.addition').bind('click', function(){
                var file = tr.find("input[name='addition']").val();
                verify.addition(file, id);
            });
        }

        function json_loaded(json) {
            $('#pagetotal').html(json.total);
            $("span.result").html(json.check_information);
        }

        var row_template = '<tr id="row_{fileid}">';
        row_template +='    <td class="t_c"><input type="checkbox" class="checkbox_style" name="chk_row" id="chk_row_{fileid}" value="{fileid}" /></td>';
        row_template +='	<td class="t_l filename">{path}</td>';
        row_template +='	<td class="t_c">{modified}</td>';
        row_template +='	<td class="t_c">{created}</td>';
        row_template +='	<td class="t_c">{operation}<input type="hidden" name="status" value="{status}"/></td>';
        row_template +='    </tr>';
        var tableApp = new ct.table('#item_list', {
            rowIdPrefix : 'row_',
            pageSize : "<?=$pagesize;?>",
            rowCallback: 'init_row_event',
            dblclickHandler : 'verify.view',
            jsonLoaded : 'json_loaded',
            template : row_template,
            baseUrl  : '?app=<?=$app?>&controller=<?=$controller?>&action=page'
        });
        $(function(){
            tableApp.load();
        });
    </script>
<?php $this->display('footer', 'safe');?>