<link rel="stylesheet" type="text/css" href="apps/system/css/relation.css"/>
<form name="add" id="property_add" method="POST" action="?app=system&controller=property&action=relation">
<input name="proid" type="hidden" value="<?=$proid?>"/>
<?php
 $db=factory::db();
 $result=$db->select("select catid from #table_property_category where proid={$proid}");
 foreach($result as $key=>$value){
     $arr[]=$value['catid'];
 }
?>
<div class="container"><span class="t_a" ><input type="checkbox" id="choose_all">&nbsp;&nbsp;全选</span>
    
  <div class="proid_content"> <!--content START-->

    <?php
       foreach($cate_list as $keys=> $value)
       {
          
    ?>

      <div class='one_category'><span><input type="checkbox" class="one_f" name="catid[]" value="<?php echo $value['catid'];?>"  <?php echo in_array($value['catid'],$arr)?"checked":"";?>   /><span class="t_a"><?php echo $value['name'];?></span></span>
          
        <?php
          //只存在二级栏目时，遍历二级栏目
          if(!empty($value['second']))
          {

            foreach($value['second'] as $key=>$s_val)
            {
        ?>  
            <div class='two_category'>
                <span><input type="checkbox" name="catid[]" value="<?php echo $s_val['catid'];?>"  <?php echo in_array($s_val['catid'],$arr)?"checked":"";?>/><span class="t_a"><?php echo $s_val['name'];?></span></span>
                
                <?php
                //只存在三级栏目时，遍历三级栏目
                    if(!empty($s_val['three']))
                    {
                 ?>    
                <div class="three_category">
                    <?php 
                        foreach($s_val['three'] as $k=>$t_val)
                       {
                    ?>
                        <li><input type="checkbox" name="catid[]" value="<?php echo $t_val['catid'];?>" <?php echo in_array($t_val['catid'],$arr)?"checked":"";?> /><span class="t_a"><?php echo $t_val['name'];?></span></li>
                    <?php 
                        }
                    ?>
                </div>
                <?php
                    }
                 ?>   
            </div>
         

          <?php
             }
           } 
          ?>


      </div>
    <?php 
         
      }
    ?>




  </div><!--content END-->
  <input type="submit" value="保存" class="button_style_2"/></td>
</div>
</form>

<script type="text/javascript">
$('#property_add').ajaxForm('property.add_submit');
$(function(){

/* 复选框递归 全选/反选 */
   $(":checkbox").each(function(i){
      $(this).click(function(){
          if($(this).attr("checked"))
          {
             //判断其是否有子元素，如果存在子元素则全部进行选中
             if($(this).parent().nextAll("div"))
             {
                $(this).parent().nextAll("div").find(":checkbox").attr("checked",true);
             }
             else
             {
                $(this).attr("checked",true);
             }
             
          }
          else
          {

            //判断其是否有子元素，如果存在子元素则全部进行取消
            if($(this).parent().nextAll("div"))
            {
               $(this).parent().nextAll("div").find(":checkbox").attr("checked",false);
            }
            else
            {
                $(this).attr("checked",false);
            }
            
          }
      })
   })
 

})
</script>