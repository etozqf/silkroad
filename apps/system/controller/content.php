<?php
class controller_content extends system_controller_abstract
{
	public function __construct($app)
	{
		parent::__construct($app);
	}

	public function stat()
	{
		$contentid = intval($_GET["contentid"]);
		$pv = loader::model("pv");
		$data["pv"] = $pv->get($contentid);
		$comments = loader::model("comments");
		$data["comments"] = $comments->get($contentid);
		$data["islogin"] = (setting("comment", "islogin") ? 1 : 0);
		$data = $this->json->encode($data);
		echo $_GET["jsoncallback"] . "($data);";
	}
	
	public function getcont()
	{
		$contentid = intval($_GET['contentid']);
		$data = array();
		$data['allow'] = 0;
		if(readprivileges($contentid, $this->_userid)) {
			$data['allow'] = 1;
			$db = factory::db();
			$cont = $db->get("select * from #table_article where contentid = $contentid");
			$data['content'] = $cont['content'];
		}
		$pattern="/<a href=\"(.*?)\.pdf/i";
		$replace='<a href="${1}.pdf?auth=b978d3501ca8d3f22625682abd2e553e"';
		$data['content']=preg_replace($pattern,$replace,$data['content']);
		$data = $this->json->encode($data);
		echo $_GET["jsoncallback"]. "($data);";
	}

	public function space()
	{
		$contentid = intval($_GET["contentid"]);
		$type = $_GET["type"];

		if ($this->system["pagecached"]) {
			$keyid = md5("pagecached_system_content_space_" . $contentid . "_" . $type);
			cmstop::cache_start($this->system["pagecachettl"], $keyid);
		}

		$content = loader::model("content");
		$r = $content->get($contentid);
		$spaceid = intval($r["spaceid"]);

		if ($spaceid) {
			$space = loader::model("space", "space")->get($spaceid);

			if (2 < $space["status"]) {
				$where = "`spaceid`=$spaceid AND `status`=6";
				$fields = "*";
				$order = "published DESC";
				$page = 1;
				$pagesize = intval($_GET["pagesize"]);

				if (!$pagesize) {
					$pagesize = 10;
				}

				$article = $content->page($where, $fields, $order, $page, $pagesize);

				if ($type == "html") {
					$this->template->assign("space", $space);
					$this->template->assign("article", $article);
					$html = $this->template->fetch("article/space_content.html");
					$result = array("spaceid" => $spaceid, "html" => $html);
				}
				else {
					$result = array("spaceid" => $spaceid, "space" => $space, "article" => $article);
				}
			}
			else {
				$result = array("spaceid" => false, "message" => "");
			}
		}
		else {
			$result = array("spaceid" => false, "message" => "");
		}

		$data = $this->json->encode($result);
		echo $_GET["jsoncallback"] . "($data);";

		if ($this->system["pagecached"]) {
			cmstop::cache_end();
		}
	}

	public function contents()
	{
		$this->template->display("system/contents.html");
	}

	public function preview()
	{
		if ($this->is_get()) {
			$cache = factory::cache();

			if ($_GET["contentid"]) {
				$contentid = intval($_GET["contentid"]);
				$catid = intval($_GET["catid"]);
				$modelid = intval($_GET["modelid"]);

				if (!$catid) {
					$this->showmessage("请选择栏目！");
					exit();
				}

				$modelname = table("model", $modelid, "alias");
				$model = loader::model("admin/" . $modelname, $modelname);
				$pathdata = table("category", $catid, "model");
				$pathdata = unserialize($pathdata);
				$data = $model->get($contentid);
				if (($modelname != "link") && ($modelname != "special")) {
					$path = $pathdata[$modelid]["template"];

					if ($modelname == "video") {
						$videotype = explode(".", $data["video"]);
						$num = count($videotype) - 1;
						$player = ($videotype[$num] ? $videotype[$num] : "flash");
						$this->template->assign("player", $player);
					}

					$this->template->assign($data);
					$this->template->display($path);
				}
				else if ($modelname == "link") {
					header("location: " . $data["url"]);
				}
			}
			else if ($_GET["key"]) {
				$data = $cache->get($_GET["key"]);
				$cache->rm($_GET["key"]);
				echo $data;
			}
			else {
				$this->showmessage("没有此内容预览！");
			}
		}
		else {
			$this->showmessage("没有此内容预览！");
		}
	}

	public function pages()
	{
		$page = intval($_GET["page"]);
		$pagesize = intval($_GET["size"]);
		$catid = intval($_GET["catid"]);
		$page = $page * $pagesize;
		$data = loader::model("content")->ls($page, $pagesize, $catid);

		if ($data) {
			echo $_GET["jsoncallback"] . "(" . $this->json->encode(array("state" => true, "data" => $data)) . ");";
		}
		else {
			echo $_GET["jsoncallback"] . "(" . $this->json->encode(array("state" => false)) . ");";
		}
	}
}


