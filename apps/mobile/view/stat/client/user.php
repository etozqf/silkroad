<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/stat.css" />

<script type="text/javascript" src="<?=IMG_URL?>js/lib/json2.js"></script>

<link rel="stylesheet" href="<?=IMG_URL?>js/lib/loading/style.css" />
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.loading.js"></script>
<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<div class="stat-client-container">
    <div class="tag_1">
        <ul class="tag_list">
            <li><a href="?app=mobile&controller=stat&action=client">统计概况</a></li>
            <li class="active"><a href="?app=mobile&controller=stat&action=client_user">用户分析</a></li>
            <li><a href="?app=mobile&controller=stat&action=client_device">终端统计</a></li>
        </ul>
    </div>
    <div class="banner">
        <div class="banner-content">
            <div class="ui-filter">
                <?php $today = date('Y-m-d', TIME); ?>
                <a data-min="<?=$today?>" data-max="<?=$today?>">今日</a>
                <?php $yesterday = date('Y-m-d', strtotime('-1 day')); ?>
                <a data-min="<?=$yesterday?>" data-max="<?=$yesterday?>">昨日</a>
                <?php $monday = date('Y-m-d', strtotime(date('o-\\WW'))); ?>
                <a data-min="<?=$monday?>" data-max="<?=$today?>">本周</a>
                <?php list($year, $month) = explode('-', $today); $firstday = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year)); ?>
                <a data-min="<?=$firstday?>" data-max="<?=$today?>">本月</a>
                <input type="text" name="starttime" value="<?=$day_min?>" data-max="<?=$today?>" size="12" class="input_calendar" style="width:70px;" /> ~
                <input type="text" name="endtime" value="<?=$day_max?>" data-max="<?=$today?>" size="12" class="input_calendar" style="width:70px;" />
            </div>
        </div>
    </div>
    <div class="stat-client">
        <div class="client-row">
            <dl class="ui-label">
                <dt>指标：</dt>
                <dd>
                    <a class="active" data-series="newusers">新增用户</a>
                    <a data-series="totalusers">累计用户</a>
                    <a data-series="activeusers">活跃用户</a>
                    <a data-series="activepercent">用户活跃率</a>
                    <a data-series="loadnum">启动次数</a>
                </dd>
            </dl>
        </div>
        <div class="client-row">
            <div id="diagram-user" style="height:450px;"></div>
        </div>
        <div class="client-row">
            <div id="table-user">
                <img id="btn-export" class="hand export" src="images/export.png" alt="导出" title="导出" />
                <table cellspacing="0" cellpadding="0" class="table-light">
                    <thead>
                    <tr>
                        <th width="13%">日期</th>
                        <th width="15%">新用户</th>
                        <th width="18%">累计用户</th>
                        <th width="17%">活跃用户</th>
                        <th width="12%">用户活跃率</th>
                        <th width="25%">启动次数</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<form id="form-export" method="post" action="?app=mobile&controller=stat&action=client_user_export" target="_blank" style="display:none;">
    <input type="hidden" name="data" value="" />
</form>

<script type="text/template" id="tpl-row">
    <tr>
        <td>{datetime}</td>
        <td>{newusers}</td>
        <td>{totalusers}</td>
        <td>{activeusers}</td>
        <td>{activepercent}</td>
        <td>{loadnum}</td>
    </tr>
</script>

<script type="text/javascript" src="chart/highcharts.js"></script>
<script type="text/javascript" src="apps/mobile/js/stat/client/user.js"></script>