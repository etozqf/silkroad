<?php $this->display('header', 'system'); ?>

<link rel="stylesheet" href="apps/mobile/css/stat.css" />
<script type="text/javascript" src="apps/mobile/js/signature.js"></script>

<div class="bk_8"></div>
<div class="stat-container">
    <div class="stat-row" style="text-align:right;padding-bottom:10px;">
        <input type="text" name="day_min" value="<?=$day_min?>" size="12" class="input_calendar" style="width:70px;" /> ~
        <input type="text" name="day_max" value="<?=$day_max?>" size="12" class="input_calendar" style="width:70px;" />
    </div>
    <div class="stat-row">
        <dl class="proids" id="filter-category">
            <dt>频道：</dt>
            <dd>
                <a data-category="" class="checked">全部</a>
                <?php $mobile_categorys = mobile_category(); ?>
                <?php foreach ($mobile_categorys as &$category): ?>
                <a data-category="<?=$category['catid']?>"><?=$category['catname']?></a>
                <?php endforeach; ?>
            </dd>
        </dl>
    </div>
    <div class="stat-row">
        <dl class="proids" id="filter-model">
            <dt>模型：</dt>
            <dd>
                <a data-modelid=""class="checked">全部</a>
                <?php $mobile_models = mobile_model(); ?>
                <?php foreach ($mobile_models as $modelid => &$model): ?>
                <a data-modelid="<?=$modelid?>"><?=$model['name']?></a>
                <?php endforeach; ?>
            </dd>
        </dl>
    </div>
    <div class="stat-row">
        <dl class="proids" id="filter-viewtype">
            <dt>查看方式：</dt>
            <dd>
                <a data-viewtype="date" class="checked">日期</a>
                <a data-viewtype="category">频道</a>
            </dd>
        </dl>
    </div>
    <div class="stat-row">
        <div class="ui-tips-yellow">
            统计概况：发稿量(<span id="total_posts" style="color:red">0</span>)&nbsp;&nbsp;
            访问量(<span id="total_pv" style="color:red">0</span>)&nbsp;&nbsp;
            评论量(<span id="total_comments" style="color:red">0</span>)
        </div>
    </div>
    <div class="stat-row">
        <div id="stat-diagram-date"></div>
        <div id="stat-diagram-category"></div>
    </div>
    <div class="stat-row">
        <div id="stat-table">
            <table width="100%" cellpadding="0" cellspacing="0" id="item_list" class="table_list">
                <thead>
                    <tr>
                        <th class="bdr_3 t_c" width="100">日期</th>
                        <th class="t_c">发稿量</th>
                        <th class="t_c">访问量</th>
                        <th class="t_c">评论量</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript" src="chart/highcharts.js"></script>
<script type="text/javascript" src="apps/mobile/js/stat/content.js"></script>