<style type="text/css">
    .table_form th,
    .table_form td {
        line-height: 24px;
    }
    .ui-checkable .ui-checkable-item {
        display: initial;
    }
</style>
<div class="bk_8"></div>
<form method="POST" action="?app=mobile&controller=autofill&action=add">
    <table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
        <tbody>
            <tr>
                <th width="80"><span class="c_red">*</span> 配置名称：</th>
                <td><input type="text" name="name" value="" size="30" /></td>
            </tr>
            <tr>
                <th><span class="c_red">*</span> 目标频道：</th>
                <td>
                    <input id="catid" name="catid" width="150" class="selectree"
                           url="?app=mobile&controller=setting&action=category_tree&disabled=0&catid=%s"
                           initUrl="?app=mobile&controller=setting&action=category_name&catid=%s"
                           paramVal="catid"
                           paramTxt="catname"
                           value=""
                           alt="选择频道" />
                </td>
            </tr>
            <tr>
                <th><span class="c_red">*</span> 数据模型：</th>
                <td>
                    <div class="ui-checkable">
                        <input type="hidden" name="modelid" value="1">
                        <?php
                            $in = array(1,2,4,7,8,9,3);
                            foreach ($models as $_model_k => $_model_v) {
                                if(!in_array($_model_v['modelid'], $in)) continue;
                                echo '<span data-value="'.$_model_v['modelid'].'" class="ui-checkable-item">'.$_model_v['name'].'</span>';
                            }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>数据接口：</th>
                <td>
                    <div class="ui-filter" id="filter-port">
                        <a data-port="cmstop" class="active">CmsTop</a>
                        <a data-port="xml">通用抓取接口</a>
                        <input type="hidden" name="port" value="cmstop" />
                    </div>
                </td>
            </tr>
        </tbody>
        <tbody data-port="cmstop">
            <tr>
                <th><span class="c_red">*</span> 栏目：</th>
                <td>
                    <input width="150" class="selectree" name="options[catid]" value=""
                           url="?app=system&controller=category&action=cate&catid=%s"
                           initUrl="?app=system&controller=category&action=name&catid=%s"
                           paramVal="catid"
                           paramTxt="name"
                           multiple="multiple"
                        />
                </td>
            </tr>
            <tr>
                <th>属性：</th>
                <td>
                    <input width="150" class="selectree" name="options[proid]" value=""
                           url="?app=system&controller=property&action=cate&proid=%s"
                           initUrl="?app=system&controller=property&action=name&proid=%s"
                           paramVal="proid"
                           paramTxt="name"
                           multiple="multiple"
                        />
                </td>
            </tr>
            <tr>
                <th>来源：</th>
                <td>
                    <input name="options[sourceid]" class="suggest" width="300" value=""
                           url="?app=system&controller=source&action=suggest&size=10&q=%s"
                           listUrl="?app=system&controller=source&action=page&page=%s"
                           initUrl="?app=system&controller=source&action=name&sourceid=%s"
                           paramVal="sourceid"
                           paramTxt="name"
                        />
                </td>
            </tr>
            <tr>
                <th>创建人：</th>
                <td>
                    <input name="options[createdby]" class="suggest" width="300" value=""
                           url="?app=system&controller=administrator&action=suggest&q=%s"
                           listUrl="?app=system&controller=administrator&action=page&page=%s"
                           initUrl="?app=system&controller=administrator&action=name&userid=%s"
                           paramVal="userid"
                           paramTxt="username"
                        />
                </td>
            </tr>
            <tr>
                <th>权重：</th>
                <td>
                    <input name="options[weight][0]" value="" size="4" type="text" />
                    <label <?=$options['weight']['range']?'':'style="display:none"'?>>
                        <span style="margin:0 5px;">~</span>
                        <input name="options[weight][1]" value="" size="4" type="text" />
                    </label>
                    <input type="checkbox" <?=$options['weight']['range']?'checked="checked"':''?> onclick="$(this).prev('label')[this.checked ? 'show' : 'hide']()" name="options[weight][range]"/> 范围
                    (<em>1~100数字</em>)
                </td>
            </tr>
            <tr>
                <th>发布时间：</th>
                <td>
                    <select name="options[published]">
                        <option value="0">全部时间</option>
                        <option value="1">1 天</option>
                        <option value="2">2 天</option>
                        <option value="7">1 周</option>
                        <option value="31">1 个月</option>
                        <option value="93">3 个月</option>
                        <option value="186">6 个月</option>
                        <option value="366">1 年</option>
                    </select> 以内
                </td>
            </tr>
            <tr>
                <th>关键词：</th>
                <td>
                    <input class="suggest" name="options[tags]" width="300" value=""
                           url="?app=system&controller=tag&action=suggest&tag=%s"
                           listUrl="?app=system&controller=tag&action=page&page=%s"
                           paramVal="tag"
                           paramTxt="tag"
                           anytext="1"
                        />
                </td>
            </tr>
        </tbody>
        <tbody data-port="xml" style="display:none;">
            <tr>
                <th valign="top"><span class="c_red">*</span> 接口地址：</th>
                <td>
                    <input type="text" name="options[xml]" size="50" style="width: 300px;" />
                    <div class="ui-tips-yellow" style="width: 285px;">该接口地址的输出格式必须遵守CmsTop内容共享接口规范，您可以<a href="?app=mobile&controller=autofill&action=xml_sample" target="_blank">下载标准输出示例文件</a>，并调整或建立符合规范的输出文件。</div>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <th><span class="c_red">*</span> 抓取间隔：</th>
                <td><input type="text" name="interval" value="<?=3600 * 12?>" size="10" /> 秒</td>
            </tr>
            <tr>
                <th>发布状态：</th>
                <td>
                    <label>
                        <input type="radio" name="state" value="6" checked="checked" /> 已发
                    </label>
                    &nbsp;&nbsp;
                    <label>
                        <input type="radio" name="state" value="3" /> 待审
                    </label>
                </td>
            </tr>
            <tr>
                <th>配置状态：</th>
                <td>
                    <label><input type="radio" name="disabled" value="0" checked="checked" /> 开启</label>&nbsp;&nbsp;
                    <label><input type="radio" name="disabled" value="1" /> 关闭</label>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<div class="bk_5"></div>
<script type="text/javascript" src="apps/mobile/js/lib/checkable.js"></script>
<script type="text/javascript">
    $('.ui-checkable:eq(0)').checkable({});
</script>