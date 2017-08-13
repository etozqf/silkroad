<?php

class priv extends object 
{
	static $priv, $category, $page, $userid, $roleid, $cache;
	
	static function init($userid, $roleid)
	{
		self::$userid = $userid;
		self::$roleid = $roleid;
		
		if ($roleid == 1) return true;

        if (!self::$cache)
        {
            self::$cache = factory::cache();
        }
			
		self::$priv = self::$cache->get('priv_'.$userid);
		if (!self::$priv) self::$priv = priv::cache($userid, $roleid);
	}
	
	static function cache($userid, $roleid)
	{
		if (!self::$cache)
		{
			self::$cache = factory::cache();
		}
		
		self::$userid = $userid;
		self::$roleid = $roleid;

		if ($roleid == 1) return true;
		
		$priv = array(
			'aca' => array(),
			'catid' => array(),
			'pageid' => array(),
			'sectionid' => array(),
			'section_pageid' => array(),
            'openaca' => array()
		);
		$db = factory::db();
		
		$data = $db->select("SELECT a.* FROM `#table_role_aca` r LEFT JOIN `#table_aca` a ON r.acaid=a.acaid WHERE r.roleid=?", array($roleid));
		foreach ($data as $r)
		{
			$priv['aca'][] = array($r['app'], $r['controller'], $r['action']);
		}
		self::$priv['aca'] = $priv['aca'];
		
		$data = $db->select("SELECT `menuid`,`url` FROM `#table_menu`");
		foreach ($data as $r)
		{
			if (priv::url($r['url'], false)) $priv['menuid'][] = intval($r['menuid']);
		}
		
		if ($roleid > 2)
		{ 
			$data = $db->select("SELECT `catid` FROM `#table_category_priv` WHERE `userid`=?", array($userid));
			foreach ($data as $r)
			{
				$priv['catid'][] = intval($r['catid']);
			}
			
			$data = $db->select("SELECT `pageid` FROM `#table_page_priv` WHERE `userid`=?", array($userid));
			foreach ($data as $r)
			{
				$priv['pageid'][] = intval($r['pageid']);
			}
			
			$data = $db->select("SELECT `sectionid` FROM `#table_section_priv` WHERE `userid`=?", array($userid));
			foreach ($data as $r)
			{
				$priv['sectionid'][] = intval($r['sectionid']);
			}
			if (!empty($priv['sectionid']))
			{
				$data = $db->select("SELECT distinct `pageid` FROM `#table_section` WHERE `sectionid` IN (".implode_ids($priv['sectionid']).")");
				foreach ($data as $r)
				{
					$priv['section_pageid'][] = intval($r['pageid']);
				}
			}
		}

        // 增加接口ACA
        $data = $db->select("SELECT a.* FROM `#table_openaca_user` r LEFT JOIN `#table_openaca` a ON r.acaid=a.acaid WHERE r.userid=?", array($userid));
        foreach ($data as $r)
        {
            $priv['openaca'][] = array($r['app'], $r['controller'], $r['action']);
        }
        self::$priv['openaca'] = $priv['openaca'];
		
		self::$cache->set('priv_'.$userid, $priv);
		return $priv;
	}

	static function aca($app, $controller, $action = null, $strict = true)
	{
		if (self::$roleid == 1) return true;
		
		$acas = @include(ROOT_PATH.'apps/'.$app.'/config/aca_public.php');
		if ($acas && (in_array($controller, $acas) || in_array($controller.($action ? '/'.$action : ''), $acas))) return true;

		foreach (self::$priv['aca'] as $r)
		{
			if ($r[0] == $app && (empty($r[1]) || (!$strict && empty($controller)) || ($r[1] == $controller && (empty($r[2]) || empty($action) || $r[2] == $action || in_array($action, explode(',', $r[2])))))) return true;
		}
		return false;
	}
	
	static function category($catid, $child = false)
	{
		if (self::$roleid < 3) return true;
		
		if (is_null($catid) && $child) return !empty(self::$priv['catid']);

		if (is_null(self::$category)) self::$category = table('category');
		$parentids = self::$category[$catid]['parentids'];
		$catids = $parentids ? $parentids.','.$catid : $catid;
		if ($child)
		{
			$childids = self::$category[$catid]['childids'];
			if ($childids)
			{
				$catids .= ','.$childids;
			}
		}
		$catids = explode(',', $catids);
		return array_intersect(self::$priv['catid'], $catids) ? true : false;
	}
	
	static function page($pageid, $child = false)
	{
		if (self::$roleid < 3) return true;
		
		if (is_null($pageid) && $child) return !empty(self::$priv['pageid']);
		
		if (is_null(self::$page)) self::$page = table('page');
		$parentids = self::$page[$pageid]['parentids'];
		$pageids = $parentids ? $parentids.','.$pageid : $pageid;
		if ($child)
		{
			$childids = self::$page[$pageid]['childids'];
			if ($childids)
			{
				$pageids .= ','.$childids;
			}
		}
		$pageids = explode(',', $pageids);
		return array_intersect(self::$priv['pageid'], $pageids) ? true : false;
	}
	
	static function section_page($pageid, $child = false)
	{
		if (self::$roleid < 3) return true;
		
		if (is_null($pageid) && $child) return !empty(self::$priv['section_pageid']);
		
		if (is_null(self::$page)) self::$page = table('page');
		if ($child)
		{
			$childids = self::$page[$pageid]['childids'];
			$pageids = $childids ? $childids.','.$pageid : $pageid;
			$pageids = explode(',', $pageids);
			return array_intersect(self::$priv['section_pageid'], $pageids) ? true : false;
		}
		else 
		{
			return in_array($pageid, self::$priv['section_pageid']);
		}
	}
	
	static function section($sectionid, $pageid = null)
	{
		if (self::$roleid < 3 || in_array($sectionid, self::$priv['sectionid'])) return true;

		if (is_null($pageid)) $pageid = table('section', $sectionid, 'pageid');
		return priv::page($pageid);
	}
	
	static function menu($menuid)
	{
		if (self::$roleid == 1) return true;
		
		return is_numeric($menuid) ? in_array($menuid, self::$priv['menuid']) : array_intersect(self::$priv['menuid'], $menuid);
	}
	
	static function url($url, $strict = true)
	{
		if (self::$roleid == 1) return true;

		if ($url && $url[0] == '?')
		{
            $url = explode('@', $url);
			parse_str(substr($url[0], 1), $aca);
			if (isset($aca['app']) && !priv::aca($aca['app'], $aca['controller'], $aca['action'], $strict)) return false;
		}
		return true;
	}
	
	static function button($action = null, $modelid = 0, $controller = null)
	{
		if(!$modelid)
		{
			$model = table('model');
			foreach ($model as $m)
			{
				if (priv::aca($m['alias'], (is_null($controller) ? $m['alias'] : $controller), $action)) 
				{
					return true;
				}
			}
			return false;
		}
		else 
		{
			$modelalias = table('model', $modelid, 'alias');
			return priv::aca($modelalias, (is_null($controller) ? $modelalias : $controller), $action);
		}
	}

    /**
     * 接口权限检测
     *
     * @static
     * @param $app
     * @param $controller
     * @param null $action
     * @return bool
     */
    static function openaca($app, $controller, $action = null)
    {
        if (self::$roleid == 1) return true;

        $acas = @include(ROOT_PATH.'apps/'.$app.'/config/openaca_public.php');
        if ($acas && (in_array($controller, $acas) || in_array($controller.($action ? '/'.$action : ''), $acas))) return true;

        foreach (self::$priv['openaca'] as $r)
        {
            if ($r[0] == $app && (empty($r[1]) || ($r[1] == $controller && (empty($r[2]) || empty($action) || $r[2] == $action || in_array($action, explode(',', $r[2])))))) return true;
        }
        return false;
    }
}