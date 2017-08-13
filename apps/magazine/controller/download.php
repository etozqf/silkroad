<?php
/**
 * 期号管理
 *
 * @aca 期号管理
 */
final class controller_download extends magazine_controller_abstract
{
    private $edition,$magazine;

    function __construct($app)
    {
        parent::__construct($app);
        $this->magazine = loader::model('admin/magazine');
        $this->edition = loader::model('admin/edition');
    }

    /**
     * 下载
     *
     * @aca 浏览
     */
    function index() {
        $eid = intval($_GET['id']);
        $edition = $this->edition->get_by('eid', $eid);
        if(!$edition || $edition['disabled'] != 1 || !$edition['pdf'] || !file_exists(UPLOAD_PATH.$edition['pdf'])) {
            $this->showmessage('您查看的报告不存在');
        }
        $allow = true;
        if($edition['ispay']) {
            if(!$this->_userid) {
                $allow = false;
            } else {
                //非管理员需要审核权限
		$db = factory::db();
                $member = $db->get("SELECT isfree FROM #table_member WHERE userid=".$this->_userid);
                if($this->_groupid != 1 && !$member['isfree']) {
                    $readmanageModel = loader::model('readmanage', 'member');
                    $read = $readmanageModel->fetch_by_uid($this->_userid, 'eid', $eid);
                    if(!$read) {
                        $read = $readmanageModel->fetch_by_uid($this->_userid, 'mid', $edition['mid']);
                    }
                    if(!$read) {
                        $allow = false;
                    }
                }
            }
        }

        if(!$allow) {
		if($_GET['from'] != 'en') {
            		$this->showmessage('当前报告需要购买后才能查看');
		} else {
			$this->showmessage_cn('Contact us to subscribe to this product.');
		       
 		}
	}
        $pdf = UPLOAD_PATH.$edition['pdf'];

        header('Content-type: application/pdf');
        //header('Content-Disposition: attachment; filename='.$edition['title'].'.pdf'); //直接下载
        header('filename='.$edition['title'].'.pdf');  //直接网页浏览
        readfile($pdf);
    }

}
?>
