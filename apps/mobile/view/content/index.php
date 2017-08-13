<!DOCTYPE HTML>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<title><?=$head['title']?></title>
<?=$resources?>
<script type="text/javascript">
$.validate.setConfigs({
    xmlPath:'<?=ADMIN_URL?>apps/<?=$app?>/validators/'
});
$(ct.listenAjax);

var hotVideo = <?php echo empty($hot_video) ? '[]' : $hot_video;?>
</script>
</head>
<body>
<div class="content-list">
    <div class="list-action">
        <div class="list-btn">
            <div class="dropmenu">
                <h3>发布</h3>
                <ul>
                    <?php foreach ($mobile_models as $_modelid => $_model): ?>
                    <?php if (priv::aca('mobile', $_model['alias'], 'add')): ?><li class="<?=$_model['alias']?>" model="<?=$_model['alias']?>">发<?=$_model['name']?></li><?php endif; ?>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <div class="list-search">
            <div class="search_icon search">
                <input type="text" id="input-keyword" value="<?=$keywords?>" size="15" placeholder="标题" />
                <a id="btn-search" href="javascript:;">搜索</a>
                <?php if (priv::aca('mobile', 'content', 'search')): ?><a href="javascript:;" class="more_search" onclick="MobileContent.search('<?=$catid?>', '<?=$modelid?>', <?=$status?>);" title="高级搜索">高级搜索</a><?php endif; ?>
            </div>
        </div>
        <div class="list-filter">
            <dl>
                <dt>模型：</dt>
                <dd>
                    <div id="filter-model" class="ui-filter">
                        <a<?php if (!$modelid): ?> class="active"<?php endif; ?> href="javascript:;" data-model="" data-modelid="">全部</a>
                        <?php foreach ($mobile_models as $_modelid => $_model): ?>
                        <a<?php if ($_modelid == $modelid): ?> class="active"<?php endif; ?> href="javascript:;" data-model="<?=$_model['alias']?>" data-modelid="<?=$_modelid?>"><?=$_model['name']?></a>
                        <?php endforeach; ?>
                    </div>
                </dd>
                <dt>状态：</dt>
                <dd>
                    <div id="filter-status" class="ui-filter">
                        <?php foreach ($mobile_status as $status_value => $status_name): ?>
                        <a<?php if ($status == $status_value): ?> class="active"<?php endif; ?> href="javascript:;" data-status="<?=$status_value?>"><?=$status_name?></a>
                        <?php endforeach; ?>
                    </div>
                </dd>
            </dl>
        </div>
    </div>
    <?php $this->display('content/table', 'mobile'); ?>
</div>

<div class="content-viewer">
    <div class="tag_1">
        <ul class="tag_list">
            <li id="btn-view"><a href="javascript:void(0);">查看</a></li>
            <li class="active"><a href="javascript:void(0);"><?php if ($category): ?>频道统计<?php else: ?>内容统计<?php endif; ?></a></li>
        </ul>
    </div>
    <div class="ui-tab-content">
        <div class="ui-tab-content-item content-detail-container">
            <iframe width="100%" height="100%" frameborder="0" src="about:blank"></iframe>
        </div>
        <div class="ui-tab-content-item ui-tab-content-item-active content-stat-container">
            <div class="content-stat-overlay"></div>
            <div class="content-stat">
                <div class="content-stat-panel">
                    <h2>
                        <?php if (priv::aca('mobile', 'stat', 'content')): ?><a href="javascript:void(0);" onclick="ct.assoc.open('?app=mobile&controller=stat&action=content<?php if ($catid): ?>&catid=<?=$catid?><?php endif; ?>', 'newtab');">查看详细</a><?php endif; ?>
                        <?php if ($category['catname']): ?><?=$category['catname']?>-<?php endif; ?>综合统计
                    </h2>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td width="120">今日发稿：<span id="stat_posts_today">...</span></td>
                                <td width="150">今日PV：<span id="stat_pv_today">...</span></td>
                                <td rowspan="2">稿件量：<span class="txt-highlight"><span id="stat_posts">...</span></span> </td>
                                <td width="150" rowspan="2">待审稿件：<span class="txt-highlight"><span id="stat_posts_submit">...</span></span> </td>
                            </tr>
                            <tr>
                                <td>昨日发稿：<span id="stat_posts_yesterday">...</span></td>
                                <td>昨日PV：<span id="stat_pv_yesterday">...</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <script type="text/javascript" src="chart/highcharts.js"></script>
                <div class="content-stat-panel">
                    <h2><?php if ($category['catname']): ?><?=$category['catname']?>-<?php endif; ?>最近30天PV统计</h2>
                    <div id="content-stat-diagram"></div>
                </div>
                <div class="content-stat-panel">
                    <div class="box_10 mar_t_10 p_r clear" id="rank_pv" style="height: 263px;">
                        <h3 class="layout">
                            <span class="f_l b">访问排行榜</span>
                        </h3>
                        <div class="p_a tag_span tag_list_3">
                            <span class="s_4">今日</span>
                            <span>昨日</span>
                            <span>本周</span>
                            <span>本月</span>
                        </div>
                        <ul class="txt_list" id="rank_today"></ul>
                        <ul class="txt_list" id="rank_yesterday" style="display:none;"></ul>
                        <ul class="txt_list" id="rank_week" style="display:none;"></ul>
                        <ul class="txt_list" id="rank_month" style="display:none;"></ul>
                        <script type="text/template" id="tpl-rank-row">
                            <li>
                                <span class="f_r c_red">{pv}</span>
                                <span class="f_l"><a href="javascript:MobileContent.view('{contentid}', '{model}');">{title}</a></span>
                            </li>
                        </script>
                        <div class="clear"></div>
                    </div>
                    <script type="text/javascript">
                        $(function (){
                            $('div.tag_span > span').mouseover(function (){
                                var span = $(this), index,
                                    parent = span.parent(),
                                    lists = parent.parent().find('ul.txt_list');
                                span.siblings().removeClass('s_4');
                                lists.hide();
                                index = parent.find('span').index(span);
                                span.addClass('s_4');
                                lists.eq(index).show();
                            });
                        })
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
