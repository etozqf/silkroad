<div class="related-panel">
    <div class="operation_area">
        <form action="" id="form-query">
            <input id="keyword" type="text" value="<?=$keywords?>" placeholder="搜索标题" size="30" name="keyword">
            <select name="modelid">
                <option value="">选择模型</option>
                <?php foreach (mobile_model() as $_modelid => $_model): ?>
                <option value="<?=$_modelid?>"><?=$_model['name']?></option>
                <?php endforeach; ?>
            </select>
            <input id="catid" name="catid" width="150"
                   url="?app=mobile&controller=setting&action=category_tree&disabled=0&catid=%s"
                   initUrl="?app=mobile&controller=setting&action=category_name&catid=%s"
                   paramVal="catid"
                   paramTxt="catname"
                   multiple="1"
                   value=""
                   alt="选择频道" />
            <input type="submit" class="button_style_1" value="搜索" />
        </form>
    </div>
    <div class="related-panel-left">
        <div class="related-panel-title">待选(<span data-role="loaded">0</span>/<span data-role="total">0</span>)</div>
        <div class="related-content-list"></div>
        <div id="left-msg">暂无内容</div>
    </div>
    <div class="related-panel-right">
        <div class="related-panel-title">已选(<span data-role="selected">0</span>)</div>
        <div class="related-selected-list"></div>
        <div id="right-msg">未选择</div>
    </div>
</div>