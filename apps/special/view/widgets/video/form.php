<div class="bk_8"></div>
<form>
	<table width="95%" border="0" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<th width="65">URL：</th>
				<td><input style="width:320px;" type="text" name="data[video]" id="data_video" value="<?=$data['video']?>" size="40"/></td>
			</tr>
			<tr>
				<th>&nbsp;</th>
				<td><table><tr>
					<td width="60"><button type="button" id="filebtn_flash" style="margin-left: 0;">媒体库</button></td>
					<td width="90"><button id="video_select" type="button">从内容选择</button></td>
					<?php if(setting('video', 'openserver')):?>
					<td width="90"><button id="vms_select" type="button">已转码视频</button></td>
					<?php endif;?>
					<td><div id="video_thirdparty"></div></td>
				</tr></table></td>
			</tr>
			<tr>
				<th>尺寸：</th>
				<td>
					<input type="text" name="data[width]" value="<?=$data['width']?>" size="6"/> × <input type="text" name="data[height]" value="<?=$data['height']?>" size="6"/> px
				</td>
			</tr>
			<tr>
				<th>自动播放：</th>
				<td>
					<input type="checkbox" value="1" name="data[autostart]"  <?php echo empty($data['autostart'])?'':'checked="checked"';?>/>
				</td>
			</tr>
		</tbody>
	</table>
</form>