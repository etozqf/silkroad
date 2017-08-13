<form action="/?app=member&controller=index&action=addreadmanage&idtype=<?=$idtype?>" method="post">
    <div class="addreadmanage">
        <?php if($idtype == 'proid') {?>
        <p class="typename">选择属性：</p>
        <p class="property"><?=element::property()?></p>
        <?php } elseif($idtype == 'catid') {?>
        <p class="typename">选择栏目：</p>
        <p class="property"><?=element::cate('catid', 'catid', 0, array('multiple'=>true, 'checkparents'=>true))?></p>
        <?php } elseif($idtype == 'contentid' || $idtype == 'eid') {?>
        <p class="typename">输入内容id：</p>
        <p class="property"><input type="text" name="ids" placeholder="多个id，使用逗号（,）隔开"/></p>
        <?php } elseif($idtype == 'mid') {?>
        <p class="typename">选择刊物：</p>
        <p class="property">
            <select name="mid">
                <?php foreach($magazinelist as $val):?>
                <option value="<?=$val['mid']?>"><?=$val['name']?></option>
                <?php endforeach;?>
            </select>
        </>
        <?php }?>
        <input type="hidden" name="userid" value="<?=$userid?>"/>
        <input type="hidden" name="addsubmit" value="true"/>
    </div>
</form>
