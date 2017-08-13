<?php
class model_admin_category extends model
{
	private $tree;
	private $category;

	public function __construct()
	{
		parent::__construct();
		$this->_table = $this->db->options["prefix"] . "category";
		$this->_primary = "catid";
		$this->_fields = array("catid", "parentid", "name", "pinyin", "ischarge", "abbr", "alias", "parentids", "childids", "workflowid", "model", "template_index", "template_list", "template_date", "path", "url", "iscreateindex", "urlrule_index", "urlrule_list", "urlrule_date", "urlrule_show", "pagesize", "enablecontribute", "allowcomment", "title", "keywords", "description", "posts", "pv", "sort", "disabled", "watermark");
		$this->_readonly = array("catid", "parentids", "childids");
		$this->_create_autofill = array();
		$this->_update_autofill = array();
		$this->_filters_input = array();
		$this->_filters_output = array();
		$this->_validators = array();
		import("helper.tree");
		$this->tree = new tree("#table_category", "catid");
		$this->category = table("category");
	}

	public function get($catid, $fields = "*")
	{
		$r = $this->tree->get($catid, $fields);
		$r["model"] = unserialize($r["model"]);
		return $r;
	}

	public function add($data)
	{
		$data["pinyin"] = htmlspecialchars(trim($data["pinyin"]));
		$data["abbr"] = htmlspecialchars(trim($data["abbr"]));
		if (!$data["pinyin"] || !$data["abbr"]) {
			$pinyin = $this->pinyin($data["name"]);
			!$data["pinyin"] && ($data["pinyin"] = $pinyin["pinyin"]);
			!$data["abbr"] && ($data["abbr"] = $pinyin["abbr"]);
		}

		$data["model"] = serialize($data["model"]);
		$data["pagesize"] = intval($data["pagesize"]);
		$data = $this->filter_array($data, array("parentid", "name", "pinyin", "ischarge", "abbr", "alias", "parentids", "childids", "workflowid", "model", "template_index", "template_list", "template_date", "path", "url", "iscreateindex", "urlrule_index", "urlrule_list", "urlrule_date", "urlrule_show", "pagesize", "enablecontribute", "allowcomment", "title", "keywords", "description", "posts", "pv", "sort", "disabled", "watermark"));
		$result = $this->tree->set($data);
		loader::lib("uri")->category($result, NULL, NULL, $this->get($result));
		$this->update_cache();
		return $result;
	}

	public function edit($catid, $data)
	{
		if (!$this->tree->exists($catid)) {
			$this->error = "栏目不存在";
			return false;
		}

		$extends = array();

		if (!empty($data["extend"])) {
			foreach ($data["extend"] as $key ) {
				$extends[$key] = $data[$key];

				if ($key == "model") {
					$extends[$key] = serialize($extends[$key]);
				}

				if ($key == "pagesize") {
					$extends[$key] = intval($extends[$key]);
				}
			}
		}

		$data["catid"] = $catid;
		$data["pinyin"] = htmlspecialchars(trim($data["pinyin"]));
		$data["abbr"] = htmlspecialchars(trim($data["abbr"]));
		if (!$data["pinyin"] || !$data["abbr"]) {
			$pinyin = $this->pinyin($data["name"]);
			!$data["pinyin"] && ($data["pinyin"] = $pinyin["pinyin"]);
			!$data["abbr"] && ($data["abbr"] = $pinyin["abbr"]);
		}

		$data["model"] = serialize($data["model"]);
		$data["pagesize"] = intval($data["pagesize"]);
		$data = $this->filter_array($data, array("catid", "parentid", "name", "pinyin", "ischarge", "abbr", "alias", "parentids", "childids", "workflowid", "model", "template_index", "template_list", "template_date", "path", "url", "iscreateindex", "urlrule_index", "urlrule_list", "urlrule_date", "urlrule_show", "pagesize", "enablecontribute", "allowcomment", "title", "keywords", "description", "posts", "pv", "sort", "disabled", "watermark"));
		$result = $this->tree->set($data);

		if (!empty($extends)) {
			$extends["catid"] = $catid;
			$this->tree->set_each($extends);
		}

		loader::lib("uri")->category($catid, NULL, NULL, $this->get($catid));
		$this->update_cache();
		return $result;
	}

	public function delete($catid)
	{
		$result = (is_numeric($catid) ? $this->tree->rm($catid) : array_map(array($this->tree, "rm"), explode(",", $catid)));
		$this->update_cache();
		return $result;
	}

	public function move($catid, $parentid)
	{
		if (!$this->tree->exists($catid)) {
			$this->error = "栏目不存在";
			return false;
		}

		$result = $this->tree->set(array("catid" => $catid, "parentid" => $parentid));

		if (!$result) {
			$this->error = "不能移动栏目到子栏目下";
			return false;
		}

		$this->update_cache();
		return true;
	}

	public function ls($where, $order = "`catid` ASC")
	{
		return $this->select($where, "*", $order);
	}

	public function categorys()
	{
		$data = $this->select();
		$categorys = array();

		foreach ($data as $value ) {
			$categorys[$value["catid"]] = $value["name"];
		}

		return $categorys;
	}

	public function search($name)
	{
		return $this->tree->search($name, "`catid`,`name`,`url`");
	}

	public function get_pos($catid = NULL)
	{
		if (is_null($catid)) {
			return false;
		}

		return $this->tree->pos($catid, "`catid`,`parentids`,`name`,`url`");
	}

	public function get_child($catid = NULL)
	{
		return $this->tree->get_child($catid, "`catid`,`parentids`,`childids`,`name`,`path`,`sort`,`url`");
	}

	public function repair()
	{
		@set_time_limit(600);

		if (is_array($this->category)) {
			$catids = array_keys($this->category);

			foreach ($catids as $catid ) {
				if ($catid == 0) {
					continue;
				}

				$this->category[$catid]["parentids"] = $parentids[$catid] = $this->get_parentids($catid);
			}

			$uri = loader::lib("uri", "system");

			foreach ($catids as $catid ) {
				if ($catid == 0) {
					continue;
				}

				$childids = $this->get_childids($catid);
				$this->update(array("parentids" => $parentids[$catid], "childids" => $childids), $catid);
				$uri->category($catid);
			}

			$this->update_cache();
		}

		return true;
	}

	public function get_parentids($catid, $parentids = "", $n = 1)
	{
		if (!is_array($this->category) || !isset($this->category[$catid])) {
			return false;
		}

		$parentid = $this->category[$catid]["parentid"];
		if ($parentid && ($parentid != $catid)) {
			$parentids = ($parentids ? $parentid . "," . $parentids : $parentid);
			$parentids = $this->get_parentids($parentid, $parentids, ++$n);
		}
		else {
			if (!$parentids) {
				$parentids = NULL;
			}

			$this->category[$catid]["parentids"] = $parentids;
		}

		return $parentids;
	}

	public function get_childids($catid)
	{
		$childids = array();

		if (is_array($this->category)) {
			foreach ($this->category as $id => $cat ) {
				if ($cat["parentid"] && ($id != $catid)) {
					$parentids = explode(",", $cat["parentids"]);

					if (in_array($catid, $parentids)) {
						$childids[] = $id;
					}
				}
			}
		}

		$childids = implode(",", $childids);

		if (!$childids) {
			$childids = NULL;
		}

		return $childids;
	}

	public function update_cache()
	{
		table_cache("category");
	}

	public function catesearch($name)
	{
		$name = addslashes($name);
		return $this->select("(`name` LIKE '%$name%' OR `pinyin` LIKE '$name%' OR `abbr` LIKE '$name%') AND childids IS NULL", "catid,name,pinyin,abbr", "catid ASC");
	}

	public function pinyin($name)
	{
		$pinyin = array();
		$abbr = array();
		import("helper.pinyin");
		$chars = preg_split("/([\\xF0-\\xF7][\\x80-\\xBF]{3}|[\\xE0-\\xEF][\\x80-\\xBF]{2})/m", $name, -1, PREG_SPLIT_NO_EMPTY + PREG_SPLIT_DELIM_CAPTURE);

		foreach ($chars as $char ) {
			if (preg_match("/[\\xE0-\\xEF][\\x80-\\xBF]{2}/", $char)) {
				$pinyin[] = pinyin::get($char, "utf-8");
				$abbr[] = pinyin::get($char, "utf-8", 1);
			}
			else {
				$normalize = preg_replace("/[^a-zA-Z0-9\s]/", "", $char);
				$pinyin[] = preg_replace("/\s+/", "", $normalize);
				$abbr[] = preg_replace("/(\s|\b)([a-zA-Z0-9]).*?(\s|\b)/", "\2", $normalize);
			}
		}

		return array("pinyin" => implode("", $pinyin), "abbr" => implode("", $abbr));
	}
}


