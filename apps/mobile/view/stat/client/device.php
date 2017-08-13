<?php $this->display('header', 'system'); ?>

<style type="text/css">
    .ui-diagram-tab-content {
        min-height: 200px;
    }
    .table-container {
        padding: 0 0 5px;
    }
</style>

<link rel="stylesheet" href="apps/mobile/css/stat.css" />

<link rel="stylesheet" href="<?=IMG_URL?>js/lib/loading/style.css" />
<script type="text/javascript" src="<?=IMG_URL?>js/lib/jquery.loading.js"></script>
<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<div class="stat-client-container">
    <div class="tag_1">
        <ul class="tag_list">
            <li><a href="?app=mobile&controller=stat&action=client">统计概况</a></li>
            <li><a href="?app=mobile&controller=stat&action=client_user">用户分析</a></li>
            <li class="active"><a href="?app=mobile&controller=stat&action=client_device">终端统计</a></li>
        </ul>
    </div>
    <div class="stat-client">
        <div style="overflow:hidden;padding-top:5px;">
            <div class="ui-filter" style="float:right;">
                <input type="text" name="starttime" value="<?=$day_min?>" size="12" class="input_calendar" style="width:70px;" /> ~
                <input type="text" name="endtime" value="<?=$day_max?>" size="12" class="input_calendar" style="width:70px;" />
            </div>
        </div>
        <div class="client-row">
            <div class="ui-diagram">
                <div class="ui-diagram-title">
                    <h3>设备分布</h3>
                </div>
                <div class="ui-diagram-content">
                    <div id="tab-device" class="tag_1">
                        <ul class="tag_list">
                            <li class="active"><a>活跃用户</a></li>
                            <li><a>新增用户</a></li>
                            <li><a>全部</a></li>
                        </ul>
                    </div>
                    <div class="ui-diagram-tab-content">
                        <div class="table-container" data-table="device-activeusers"></div>
                    </div>
                    <div class="ui-diagram-tab-content" style="display:none;">
                        <div class="table-container" data-table="device-newusers"></div>
                    </div>
                    <div class="ui-diagram-tab-content" style="display:none;">
                        <div class="table-container" data-table="device-totalusers"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="client-row">
            <div class="ui-diagram">
                <div class="ui-diagram-title">
                    <h3>操作系统</h3>
                </div>
                <div class="ui-diagram-content">
                    <div id="tab-os" class="tag_1">
                        <ul class="tag_list">
                            <li class="active"><a>活跃用户</a></li>
                            <li><a>新增用户</a></li>
                            <li><a>全部</a></li>
                        </ul>
                    </div>
                    <div class="ui-diagram-tab-content">
                        <div class="table-container" data-table="version-activeusers"></div>
                    </div>
                    <div class="ui-diagram-tab-content" style="display:none;">
                        <div class="table-container" data-table="version-newusers"></div>
                    </div>
                    <div class="ui-diagram-tab-content" style="display:none;">
                        <div class="table-container" data-table="version-totalusers"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/template" id="tpl-table">
    <table cellspacing="0" cellpadding="0" class="table-percent">
        <tbody></tbody>
    </table>
</script>

<script type="text/template" id="tpl-row">
    <tr>
        <th width="20%">{name}</th>
        <td>
            <div class="ui-percent">
                <div class="ui-percent-number">{percent} ({count})</div>
                <div class="ui-percent-bar">
                    <div class="ui-percent-bar-inner" style="width:{display};"></div>
                </div>
            </div>
        </td>
    </tr>
</script>

<script type="text/javascript" src="apps/mobile/js/stat/client/device.js"></script>
