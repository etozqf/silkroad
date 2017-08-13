<style type="text/css">
	#item_selectcountry  .row-block{
		display: inline-block;
	    width: 300px;
	}
	#item_selectcountry .row-l-30{
		margin-left: 30px;
	}
	#item_selectcountry .row-l-60{
		margin-left: 60px;
	}
	#item_selectcountry .row-children{
		display: none;
	}
	#item_selectcountry tr td a{
	 	display: inline-block;
	    height: 16px;
	    line-height: 16px;
	    text-align: center;
	    width: 12px;

	}
</style>
<div class="bk_8"></div>
<form id="item_selectcountry" method="POST" class="validator" action="?app=item&controller=item&action=selectcountry">
<table width="98%" border="0" cellspacing="0" cellpadding="0" class="table_form">
  <?php foreach ($country as $key => $value): ?>
  <tr class="row-block">
       <td><a href="javascript:;">+</a><input xobjectid="1" name="<?=$value['name']?>" value="<?=$value['columnattrid']?>" type="checkbox" <?=in_array($value['name'],$data)?'checked':'';?> ></td>
       <td class="onclick"><?=$value['name']?></td>
       <td class="onclick"><?=$value['alias']?></td>
  </tr>
	  <?php if (!empty($value['childdata'])): ?>
	  <?php foreach ($value['childdata'] as $k => $v): ?>
	  <tr class="row-block row-l-30 row-children-<?=$value['columnattrid']?>"  style="display:none;">
	   		<td><a href="javascript:;">+</a><input xobjectid="1" name="<?=$v['name']?>" value="<?=$v['columnattrid']?>" type="checkbox" <?=in_array($v['name'],$data)?'checked':'';?> ></td>
	   		<td class="onclick"><?=$v['name']?></td>
	   		<td class="onclick"><?=$v['alias']?></td>
	  </tr>
	  		  <?php if (!empty($v['childdata'])): ?>
			  <?php foreach ($v['childdata'] as $m => $n): ?>
			  <tr class="row-block row-l-60 row-children-<?=$v['columnattrid']?>" style="display:none;">
			   		<td><input xobjectid="1" name="<?=$n['name']?>" value="<?=$n['columnattrid']?>" type="checkbox" <?=in_array($n['name'],$data)?'checked':'';?> ></td>
			   		<td class="onclick"><?=$n['name']?></td>
			   		<td class="onclick"><?=$n['alias']?></td>
			  </tr>
			  <?php endforeach ?>
			  <?php endif ?>
	  <?php endforeach ?>
	  <?php endif ?>
  <?php endforeach ?>
</table>
</form>
<script type="text/javascript">
	$('#item_selectcountry tr td a').click(function(){
		var bool = $(this).next('input').attr('checked');
		var id = $(this).next('input').val();
		if($(this).text() == "+"){
			$('.row-children-'+id).show();
			$(this).next('input').attr('checked',true);
			$(this).text("-");
		}else{
			$(this).text("+");
			$('.row-children-'+id).hide();
			$('.row-children-'+id).each(function(){
				var tt = $(this);
				tt.find('a').text("+");
				var cid = tt.find('input').val();
				$('.row-children-'+cid).hide();
			});
		} 
	});

	$('#item_selectcountry tr td input').click(function(){
		var bool = $(this).attr('checked');
		if(!bool){
			var id = $(this).val();
			$('.row-children-'+id).find('input').attr('checked',bool);
			$('.row-children-'+id).each(function(){
				var cid = $(this).find('input').val();
				$('.row-children-'+cid).find('input').attr('checked',bool);
			});
		}
	});
</script>