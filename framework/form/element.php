<?php
class element extends form_element
{
	public static function role($id, $value, $attribute = null)
	{
		$settings = array();
		$settings['name'] = $settings['id'] = $id;
		$settings['value'] = $value;
		$settings['attribute'] = $attribute;
		foreach (table('role') as $roleid=>$v)
		{
			$settings['options'][$roleid] = $v['name'];
		}
		return parent::select($settings);
	}
	
	public static function department($dep_id = 0, $uni_id = 0, $disable = false)
	{
		$sel_arr = array();
		$sel_arr['name'] = 'deparmentid';
		$sel_arr['id'] = 'depid_'.$uni_id;
		$sel_arr['value'] = $dep_id;
		if($disable) $sel_arr['disabled'] = true;
		$roles = array();
		foreach (table('department') as $value)
		{
			$roles[$value['deparmentid']] = $value['name'];
		}
		$sel_arr['options'] = $roles;
		$roles = parent::select($sel_arr);
		return $roles;
	}
	
	public static function role_dropdown($name, $value=null, $departmentid = null, $attr = null, $tips = null)
	{
		$roles = array();
		if ($tips)
		{
			$roles[] = $tips;
		}
		
		if ($departmentid)
		{
			$data = loader::model('admin/role','system')->getsByDepartmentid($departmentid);
			foreach ($data as $k=>$v)
		    {
		        $roles[$v['roleid']] = $v['name'];
		    }
		}
		else
		{
			foreach (table('role') as $k=>$v)
			{
				$roles[$k] = $v['name'];
			}
		}
	    return parent::select(array(
	       'name'=>$name,
	       'value'=>$value,
	       'options'=>$roles,
	       'attribute'=>$attr
	    ));
	}
	
	public static function department_dropdown($name, $value = null, $attr = null, $tips = '-------')
	{
		import('helper.treeview');
		$treeview = new treeview(table('department'));
		$html = "<select name=\"$name\" $attr>\n";
		$html .= '<option value="">'.$tips.'</option>';
		$html .= $treeview->select(null, $value, '<option value="{$departmentid}" {$selected}>{$space}{$name}</option>');
		$html .= '</select>';
		return $html;
	}

	public static function sex($sex_id = MALE, $uni_id = 0, $disable = false)
	{
		$sel_arr = array();
		$sel_arr['name'] = 'sex';
		$sel_arr['id'] = 'sex_'.$uni_id;
		$sel_arr['value'] = $sex_id ? $sex_id : FEMALE;
		if($disable) $sel_arr['disabled'] = true;

		$sex = array(MALE=>'男', FEMALE=>'女');

		$sel_arr['options'] = $sex;
		return parent::select($sel_arr);
	}

    /**
     * 栏目选择器
     *
     * @param $id
     * @param $name
     * @param null $value
     * @param array $options
     */
    public static function cate($id, $name, $value = null, array $options = array())
    {
        $defaults = array(
            'size' => 1,
            'attr' => null,
            'tips' => '请选择',
            'priv' => true,
            'multiple' => false
        );

        $options = !empty($options) ? array_merge($defaults, $options) : $defaults;
        $attributes = array(
            'rootClass' => 'category-datalist',
            'type' => value($options, 'multiple') ? 'multiple' : 'single',
            'checkParents' => value($options, 'checkparents') ? true : false
        );
        if (isset($options['height'])) $attributes['height'] = intval($options['height']);

        echo '<input type="text" name="'.$name.'" id="'.$id.'" data-catid="'.$value.'" data-value="'.value($options, 'tips').'" value="'.$value.'" '.value($options, 'attr').' />',
            '<script type="text/javascript">(function(){
            var loadst = function() {
                if(typeof($.Selectree)=="undefined"){
                    cmstop.fet("'.IMG_URL.'js/lib/selectree/selectree.css");
				cmstop.fet("'.IMG_URL.'js/lib/cmstop.selectree.js",init);
			} else {init();}
		}
		var init = function() {
			$(function(){
			    var elem = $("#'.$id.'");
				elem.Selectree('.json_encode($attributes).');
				elem.trigger("categoryReady");
			});
		}
		if(typeof($().autocomplete)=="undefined"){
			cmstop.fet("'.IMG_URL.'js/lib/cmstop.autocomplete.js", loadst);
		}else{
			loadst();
		}
	})()</script>';
    }

    /**
     * 栏目选择器
     *
     * @deprecated 由于参数繁多而难以维护和扩展，该函数已被废弃，请使用 element::cate() 替代
     *
     * @param $id
     * @param $name
     * @param null $value
     * @param int $size
     * @param null $attr
     * @param string $tips
     * @param bool $priv
     * @param bool $multiple
     * @param array $option
     */
    public static function category($id, $name, $value = null, $size = 1, $attr = null, $tips = '请选择', $priv = true, $multiple = false, $option = array())
	{
        if (!is_array($option)) $option = array();
        $option['size'] = $size;
        $option['attr'] = $attr;
        $option['tips'] = $tips;
        $option['priv'] = $priv;
        $option['multiple'] = $multiple;
        self::cate($id, $name, $value, $option);
	}
	
	public static function front_cat($id = "catid", $name = "catid", $value = '',$width = '150px', $option = array() )
	{
		echo '<input type="text" name="'.$name.'" id="'.$id.'" data-catid="'.$value.'" data-value="请选择" value="'.$value.'" width="'.$width.'" />',
		'<script type="text/javascript">if(typeof($.Selectree)=="undefined")document.write(\'<script src="'.IMG_URL.'/js/lib/cmstop.selectree.js"><\\/script><link rel="stylesheet" href="'.IMG_URL.'js/lib/selectree/selectree.css">\');if(typeof($().autocomplete)=="undefined"){document.write(\'<script src="'.IMG_URL.'js/lib/cmstop.autocomplete.js"><\\/script>\');}$(function(){$("#'.$id.'").Selectree({rootClass:"category-datalist",recently:false,"dataUrl":"'.($option['dataurl']?$option['dataurl']:'?app=system&controller=panel&action=category&catid=tree').'", "disable":"search", "checkParents":'.($option['checkparents']?"true":"false").', '.($option['cateurl']?'"cateUrl":"'.$option['cateurl'].'"': "").'});});</script>';
	}

	public static function check_category($id, $name, $value = null, $size = 1, $attr = null)
	{
		import('helper.treeview');
		$treeview = new treeview(table('category'));
		$html = "<select name=\"$name\" id=\"$id\" size=\"$size\" $attr>\n";
		$html .= '<option value="">请选择</option>';
		$html .= $treeview->select(null, $value, '<option value="{$catid}">{$space}{$name}</option>');
		$html .= '</select>';
		return $html;
	}

	public static function page($id, $name, $value = null, $size = 1, $attr = null)
	{
		import('helper.treeview');
		$treeview = new treeview(table('page'));
		$html = "<select name=\"$name\" id=\"$id\" size=\"$size\" $attr>\n";
		$html .= '<option value="">不限页面</option>';
		$html .= $treeview->select(null, $value, '<option value="{$pageid}">{$space}{$name}</option>');
		$html .= '</select>';
		return $html;
	}
	
	public static function page_dropdown($value = null, $attr = '')
	{
		import('helper.treeview');
		$data = table('page');
		foreach ($data as $key=>$item)
		{
			if ($item['status'] == 0)
			{
				unset($data[$key]);
			}
		}
		$treeview = new treeview($data);
		$html = array("<select {$attr}>\n");
		$html[] = $treeview->select(null, $value, '<option value="{$pageid}">{$space}{$name}</option>');
		$html[] = '</select>';
		return implode('',$html);
	}

	public static function psn($id, $name, $value, $size = 30, $type = 'dir')
	{
		return "<input type=\"text\" name=\"$name\" id=\"$id\" value=\"$value\" size=\"$size\"/> <input type=\"button\" class=\"button_style_1\" onclick=\"psn.select('$id', '$type', '$value')\" value=\"选择\" /><a href=\"javascript:void(0);\" onclick=\"ct.assoc.open('?app=system&controller=psn&action=index','newtab')\">设置发布点</a>";
	}
	
	public static function dsn_select($id, $value = null, $attribute = null)
	{
		$settings = array();
		$settings['name'] = $settings['id'] = $id;
		$settings['value'] = $value;
		$settings['attribute'] = $attribute;
		foreach (table('dsn') as $dsnid=>$v)
		{
			$settings['options'][$dsnid] = $v['name'];
		}
		return parent::select($settings);
	}

	public static function psn_select($id, $value, $attribute = null)
	{
		$settings = array();
		$settings['name'] = $settings['id'] = $id;
		$settings['value'] = $value;
		$settings['attribute'] = $attribute;
		foreach (table('psn') as $psnid=>$v)
		{
			$settings['options'][$psnid] = $v['name'];
		}
		return parent::select($settings);
	}

	public static function model($id, $name, $value = null, $attribute = null)
	{
		$settings = array();
		$settings['id'] = $id;
		$settings['name'] = $name;
		$settings['value'] = $value;
		$settings['attribute'] = $attribute;
		foreach (table('model') as $modelid=>$v)
		{
			if($v['name'] == '辩论') continue;
			$settings['options'][$modelid] = $v['name'];
		}
		return parent::select($settings);
	}

	public static function model_checkbox($value = array(),$id = array())
	{
		$settings = array();
		$settings['name'] = $id;
		$settings['value'] = $value;
		$options = array();
		foreach (table('model') as $value)
		{
			$options[$value['modelid']] = $value['name'];
		}
		$settings['options'] = $options;
		
		return parent::checkbox($settings);
	}

	public static function guestbook_type($id, $name, $value = null, $size = 1, $attr = null)
	{
		import('helper.treeview');
		$treeview = new treeview(table('guestbook_type'));
		$html = "<select name=\"$name\" id=\"$id\" size=\"$size\" $attr>\n";
		$html .= '<option value="">类型</option>';
		$html .= $treeview->select(null, $value, '<option value="{$typeid}">{$name}</option>');
		$html .= '</select>';
		return $html;
	}

	public static function guestbook_type_radio($value = 1, $id = 'typeid')
	{
		$settings = array();
		$options = array();
		$settings['name'] = $settings['id'] = $id;
		$settings['value'] = $value;
		foreach (table('guestbook_type') as $value) {
			$options[$value['typeid']] = $value['name'];
		}
		$settings['options'] = $options;
		return parent::radio($settings);
	}
	
	public static function workflow($id, $value, $attribute = null)
	{
		$settings = array();
		$settings['name'] = $settings['id'] = $id;
		$settings['value'] = $value;
		$settings['attribute'] = $attribute;
		foreach (table('workflow') as $workflowid=>$v)
		{
			$settings['options'][$workflowid] = $v['name'];
		}
		return parent::select($settings)." <a href=\"javascript:;\" onclick=\"ct.assoc.open('?app=system&controller=workflow&action=index','newtab')\">管理</a>";
	}

	public static function channel($name,  $checkeds = array())
	{
		$category = table('category');
		import('helper.treeview');
		$treeview = new treeview($category);
		$html = $treeview->get(null, 'category_tree', '<li><input id="category_{$catid}" name="'.$name.'" type="checkbox" value="{$catid}" class="category_{$catid}_children" onclick="select_treeview_children_channel({$catid})" /><span id="{$catid}">{$name}</span>{$child}</li>');
		return $html;
	}

	public static function template($id, $name, $value, $size = 30)
	{
		return '<input type="text" id="'.$id.'" name="'.$name.'" value="'.$value.'" size="'.$size.'" />
		<input type="button" onclick="ct.template($(this).prev())" class="button_style_1" value="选择">
		<img src="images/edit.gif" alt="编辑" width="16" height="16" class="hand" onclick="if($(\'#'.$id.'\').val()==\'\')return false;ct.assoc.open(\'?app=system&controller=template&action=edit&path=\'+$(\'#'.$id.'\').val(),\'newtab\');"/>
		<img width="16" height="16" alt="清除" src="images/del.gif" onclick="$(\'#'.$id.'\').val(\'\')">';
	}
	
	public static function image($name='image', $value = '', $size = 30, $thumb = 1, $attr = '')
	{
		$sid = str_replace('.', '_', microtime(true) . uniqid());
		return '<input type="text" name="'.$name.'" id="image_'.$sid
			.'" size="'.$size.'" value="'.$value.'" '.($attr?$attr.' ':'').'/><script type="text/javascript">$(function(){$("#image_'.$sid
			.'").imageInput('.($thumb?'true':'false').');})</script>';
	}
	
	public static function photo($name='photo',$value = '')
	{
		$photo = $value ? $value : 'nopic.jpg';
		$sid = str_replace('.','_',microtime(true) . uniqid());
		return '<div class="photo"><input type="hidden" name="'.$name
			.'" id="photo_'.$sid
			.'" value="'.$value.'"/><script>$(function(){$("#photo_'.$sid
			.'").photoInput();})</script></div>';
	}

	public static function state($value = 0, $id = 'state')
	{
		$settings = array();
		$settings['name'] = $settings['id'] = $id;
		$settings['value'] = $value;
		$settings['options'] = array(0=>'启用', 1=> '禁用');
		return parent::radio($settings);
	}

	public static function charset($value='utf8', $id = 'state')
	{
		$settings = array();
		$settings['name'] = $settings['id'] = $id;
		$settings['value'] = $value;
		$settings['options'] = array('gbk'=>'GBK', 'gb2312'=>'gb2312', 'utf8'=>'UTF8','latin1'=>'latin1');
		return parent::radio($settings);
	}

	public static function member_groups($value = 5, $id='groupid',$defaultvalue = '')
	{
		$settings = array();
		$settings['name'] = $settings['id'] = $id;
		$settings['value'] = $defaultvalue;
		$options = array();
		$member_group = table('member_group');
		foreach ($member_group as $value) {
			$options[$value['groupid']] = $value['name'];
		}
		$settings['options'] = $options;
		return parent::select($settings);
	}

	public static function member_photo($userid = 0,$width = '80',$height = '80',$size = 'small')
	{
		static $photos;
		if(!isset($photos[$userid]))
		{
			$member = loader::model('member_front', 'member');
			$photos[$userid] = $member->get_photo($userid, $width, $height, $size);
		}
		return $photos[$userid];
	}

	public static function title($id = 'title', $value = '', $color = '', $size = 80, $maxlength = 80, $width = '478px')
	{
		$html = array(
			parent::text(array(
				'id'=>$id,
				'name'=>$id,
				'value'=>$value,
				'size'=>$size,
				'maxlength'=>$maxlength,
				'class'=>'bdr inputtit_focus',
				'style'=>'width:' . $width
			)),
			parent::hidden(array(
				'name'=>'color',
				'value'=>$color,
				'class'=>'color-input',
				'size'=>7,
				'attribute'=>'oninited="$(\'#'.$id.'\').css(\'color\', color)" onpicked="$(\'#'.$id.'\').css(\'color\', color)"',
			))
		);
		return implode('',$html);
	}

	public static function tag($id = 'tags', $value = '', $size = 60, $maxlength = 60)
	{
		$html = '<input id="'.$id.'" class="vali_pass" type="text" size="'.$size.'" name="'.$id.'" value="'.htmlspecialchars($value).'" autocomplete="1" url="?app=system&controller=tag&action=suggest&tag=%s" listUrl="?app=system&controller=tag&action=page&page=%s" paramVal="tag" paramTxt="tag" anytext="1" />';
		$html .= '<script type="text/javascript" src="'.IMG_URL.'js/lib/cmstop.suggest.js"></script>';
		$html .= '<link href="'.IMG_URL.'js/lib/suggest/style.css" rel="stylesheet" type="text/css" />';
		$html .= '<script type="text/javascript">$(function() { window.tagInput = suggest($("#'.$id.'"), {width:450,limit:5,delimiter:" ",url:"?app=system&controller=tag&action=suggest&tag=%s",listUrl:"?app=system&controller=tag&action=page&page=%s",paramVal:"tag",paramTxt:"tag",anytext:"1"}); });</script>';
		return $html;
	}

	public static function section($contentid = null)
	{
		$sections = array();
		if ($contentid)
		{
			$recommend = loader::model('admin/section_recommend', 'page');
			$sections = $recommend->getByContentid($contentid);
		}

        $view = factory::view();
        $view->assign('sections', json_encode($sections));
        return $view->fetch('section/section', 'page');
	}

    public static function place($places)
    {
        $view = factory::view();
        $view->assign('places', json_encode($places));
        return $view->fetch('special/place', 'special');
    }
	
	public static function related($contentid = null)
	{
        $related = loader::model('admin/related', 'system');
      
        $relateds = $related->ls($contentid);
      	$contentid = !is_null($contentid)?$contentid:TIME;
        $html = '<div class="expand mar_l_8">';
        
	        $html .= '  <div class="div_show">';
	        $html .= '    <input type="text" name="related_keywords" id="related_keywords" size="20" />';
	        $html .= '    <input type="button" name="related" value="搜索" class="button_style_1" onclick="related_select('.$contentid.',$(\'#related_keywords\').val())" />';	
	        $html .= '    <ul id="related_data">';
            foreach ($relateds as $i=>$d)
            {
               $html .= '<li><input type="hidden" name="related[]" id="related_'.$i.'" value="'.$d['title'].'|'.$d['thumb'].'|'.$d['url'].'|'.$d['time'].'|'.$d['orign_contentid'].'"/><a href="'.$d['url'].'" target="_blank">'.$d['title'].'</a></li>';
            }
	        $html .= '    </ul>';
	        $html .= '  </div>';
       
        $html .= '</div>';
        return $html;
	}

	public static function tips($message)
	{
		return '<img src="images/question.gif" width="16" height="16" class="tips hand" tips="'.$message.'" align="absmiddle"/>';
	}
	
	public static function weight($weight, $maxweight)
	{
        $weight = intval($weight);
		$instance = new setting();
		$setting = $instance->get('system');
		$setting = explode("\n",$setting['weight']);
		$points = array();
		$maxlen = $maxweight ? $maxweight : 100;
		$csswidth = $maxlen * 6;
		while (list($key,$value) = each($setting)) {
			if($value) $temp = explode('|',trim($value));
			else break;
			$points[] = '"'.($temp[0]/$maxlen).'":"'.$temp[1].'"';
		}
		$points = '{'.implode(',',$points).'}';
		$weight = $weight/$maxlen;
		echo '<script type="text/javascript" src="'.IMG_URL.'js/lib/cmstop.adjuster.js"></script>
		<div style="position:relative;background:url(css/images/weightr.gif) no-repeat scroll 45px 0px;width:670px;height:25px;">
            <input type="text" style="width: 34px; ime-mode: disabled;float:left" name="weight" value="'.($weight*$maxlen).'" size="3" />
            <div id="weight" style="width:'.$csswidth.'px;height:20px;position:absolute;left:56px;top:1px;"></div>
        </div>
		<script type="text/javascript">
		$(function(){
            $("#weight").slider({
            	isStep     : false,
            	stepConfig :'.$points.',
            	offset	   :'.$weight.',
            	onDragInit :function(h, t, p, c){
            		var length = p.length;
            		h.attr("tips", c["'.$weight.'"]?(('.$maxlen.'*'.$weight.')+"\uff1a"+c["'.$weight.'"]):"");
            		h.attrTips("tips", "tips_green", 200, "top");
            		for(var i=0;i<length;i++)
            			p[i][0].attr("msg",Math.round(p[i][1]*'.$maxlen.')+"\uff1a"+p[i][2]).css("margin-top","2px").attrTips("msg", "tips_green", 200, "top");
            	},
            	onDrag	   :function(h,e,p){
            		h.parent().prev().val(parseInt('.$maxlen.'*p));
            	},
            	onDragEnd  :function(h,e,percent,c){
            		if(c[1]){
            			h.attr("tips",Math.round(percent*'.$maxlen.')+"\uff1a"+c[1]);
            			var evt = $.Event("mouseover");
            			var off = h.offset();
            			evt.pageX = off.left;
            			evt.pageY = off.top;
            			$.event.trigger(evt, [], h[0]);
            		}else h.attr("tips","");
            		h.parent().prev().val(parseInt('.$maxlen.'*percent+0.1));
            	},
            	handleBg   : "url(css/images/weight.gif) no-repeat"
            }).prev().keyup(function(e){
            	if(e.keyCode<48 || (e.keyCode>57 && (e.keyCode < 96 || e.keyCode > 105))) return;
            	var val = this.value;
            	if(parseInt(val)>'.$maxlen.' || parseInt(val)<0) return;
            	$.slider.setPoint(val/'.$maxlen.')})
            });
           </script>';
	}
	
	public static function status($id, $name, $value, $attr = null)
	{
		$options = array();
		$statuss = table('status');
		foreach ($statuss as $status=>$r)
		{
			$options[$status] = $r['name'];
		}
		return parent::select(array('id'=>$id, 'name'=>$name, 'value'=>$value, 'attr'=>$attr, 'options'=>$options));
	}
	
	public static function model_change($catid, $modelid)
	{
		$string = '<select id="changemodel" style="width:70px">';
		$models = table('model');
        foreach ($models as $mid=>$m)
        {
        	$m = table('model', $mid);
        	if (priv::aca($m['alias'], $m['alias'], 'index')) $string .= '<option value="'.$m['alias'].'" ico="'.$m['alias'].'" '.($modelid == $mid ? 'selected' : '').'>'.$m['name'].'</option>';
        }
        $string .= '</select>';
        return $string;
	}
	
	public static function referto($id = "referto", $name = "options", $value = '',$width = '150px' )
	{
		echo'同时发布到：', element::category($id, $name.'[catid]', $value, 1, null, '请选择', true, true);
	}
	
	public static function property($id = "proid", $name = "proids", $value = '',$width = '150px' )
	{
		echo '<input id="'.$id.'" width="'.$width.'" class="selectree" name="'.$name.'" value="'.$value.'" initurl="?app=system&controller=property&action=name&proid=%s" url="?app=system&controller=property&action=cate&proid=%s" paramVal="proid" paramTxt="name" multiple="multiple"/>';
		echo '<script>$(function(){$(\'#'.$id.'\').selectree();})</script>';
	}

	public static function property_view($proid = array())
	{
        if (! is_array($proid))
        {
            if (strpos($proid, ',') !== false)
            {
                $proid = explode(',', $proid);
            }
            else
            {
                $proid = array($proid);
            }
        }
		$toppro = $subpro = array();
		$db = factory::db();
		$propertys	= $db->select("SELECT * FROM `#table_property` ORDER BY `sort`");
		unset($db);
		foreach ($propertys as $property)
		{
			if ($property['parentid']) continue;
			$checked = '';
			foreach ($propertys as $p)
			{
				if ($p['parentid'] == $property['proid'])
				{
					$p['class'] = in_array($p['proid'], $proid) ? 'checked' : '';
					$checked .= $p['class'];
					$subpro[$property['proid']][] = $p;
				}
			}
			$property['class'] = $checked ? '' : 'checked';
			$toppro[] = $property; 
		}
		
		$data = '';
		foreach ($toppro as $property)
		{
			$data .= '<dl class="proids">';
			$data .= '<dt id="proid_'.$property['proid'].'">'.$property['name'].'：</dt><dd>';
			$data .= '<a id="pro_'.$property['proid'].'" href="javascript:;" class="'.$property['class'].'">全部</a>';
			foreach ($subpro[$property['proid']] as $p)
			{
				$data .= '<a id="'.$p['proid'].'" href="javascript:;" class="'.$p['class'].'">'.$p['name'].'</a>';
			}
			$data .= '</dd>';
			$data .= '</dl>';
		}
		return $data;
	}
	
	public static function property_show($url)
	{
		$proid = array();
		if (preg_match("/proid\=([\d,]*)/", $url, $matches))
		{
			$url = str_replace("proid=".$matches[1], "", $url);
			$proid = is_numeric($matches[1]) ? array($matches[1]) : explode(',', $matches[1]);
		}
		if (strpos($url, '?') === false) $url .= '?';
		if (!in_array(substr($url, -1), array('?', '&'))) $url .= '&';

		$toppro = $subpro = array();
		$propertys = table('property');
		foreach ($propertys as $property)
		{
			if ($property['parentid']) continue;
			$checked = '';
			foreach ($propertys as $p)
			{
				if ($p['parentid'] == $property['proid'])
				{
					$p['class'] = in_array($p['proid'], $proid) ? 'checked' : '';
					$checked .= $p['class'];
					$proids = array_diff($proid, explode(',', $property['childids']));
					$proids[] = $p['proid'];
					$p['url'] = $url."proid=".implode(',', $proids);
					$subpro[$property['proid']][] = $p;
				}
			}
			$property['class'] = $checked ? '' : 'checked';
			$proids = array_diff($proid, explode(',', $property['childids']));
			$property['url'] = $url."proid=".implode(',', $proids);
			$toppro[] = $property; 
		}
		
		$data = '';
		foreach ($toppro as $property)
		{
			$data .= '<dl class="proids">';
			$data .= '<dt id="proid_'.$property['proid'].'">'.$property['name'].'：</dt><dd>';
			$data .= '<a id="pro_'.$property['proid'].'" href="'.$property['url'].'" class="'.$property['class'].'">全部</a>';
			foreach ($subpro[$property['proid']] as $p)
			{
				$data .= '<a id="'.$p['proid'].'" href="'.$p['url'].'" class="'.$p['class'].'">'.$p['name'].'</a>';
			}
			$data .= '</dd>';
			$data .= '</dl>';
		}
		return $data;
	}

	public function seccode($is_admin = false)
	{
		$url	= $is_admin ? ADMIN_URL : APP_URL;
		$data	= '<label for="seccode">验证码： </label><input type="text" name="seccode" id="seccode" size="4" maxlength="4" style="ime-mode:disabled;width:65px;"/> <img id="seccode_image" src="'.$url.'?app=system&controller=seccode&action=image" style="cursor:pointer;" alt="验证码,看不清楚?请点击刷新验证码" align="absmiddle"/>';
		$data	.= '<script type="text/javascript">var seccode_image = $(\'#seccode_image\');seccode_image.click(function(){this.src=\''.$url.'?app=system&controller=seccode&action=image&=\'+Math.random()*5;});</script>';
		return $data;
	}

    public static function addon($contentid = 0)
    {
        $uuid = uniqid('mod-addon-');
        $url_prefix = ADMIN_URL . 'apps/addon/';
        $data = <<<EOF
<div class="mod-addon" id="{$uuid}"></div>
<link rel="stylesheet" type="text/css" href="{$url_prefix}css/addon.css" />
<script type="text/javascript" src="{$url_prefix}js/addon.js"></script>
<script type="text/javascript">$(function() { Addon.render('#{$uuid}', {$contentid}); });</script>
EOF;
        return $data;
    }

    public static function date_unit($name, $value, $id = null)
    {
        $units = array(
            '天' => 86400,
            '周' => 86400 * 7,
            '月' => 86400 * 30,
            '年' => 86400 * 365
        );
        $html = "<select name=\"$name\"";
        if ($id) $html .= " id=\"$id\"";
        $html .= '>';
        foreach ($units as $name => $unit)
        {
            $html .= "<option value=\"$unit\"".($value == $unit ? (' selected') : '').">$name</option>";
        }
        $html .= '</select>';
        return $html;
    }

    public static function icon($name, $value, $id = null)
    {
        static $icons = null;
        if (is_null($icons))
        {
            $icons = array();
            $path = IMG_PATH.'icon'.DS;
            $dir = 'icon/';
            $files = glob($path.'{*.png,*.gif,*.jpg}', GLOB_BRACE);
            foreach ($files as $file)
            {
                $file = preg_replace('#^'.preg_quote(IMG_PATH.$dir).'#i', '', $file);
                $pathinfo = pathinfo($file);
                $icon = basename($pathinfo['basename'], '.'.$pathinfo['extension']);
                $icons[$icon] = IMG_URL.$dir.$file;
            }
        }
        if (!count($icons)) return '';

        $html = "<select name=\"$name\"";
        if ($id) $html .= " id=\"$id\"";
        $html .= '>';
        foreach ($icons as $name => $icon)
        {
            $html .= "<option icon=\"$icon\" value=\"$name\"".($value == $name ? (' selected') : '').">$name</option>";
        }
        $html .= '</select>';
        return $html;
    }
}