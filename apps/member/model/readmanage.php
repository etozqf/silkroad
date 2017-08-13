<?php

class model_readmanage extends model implements SplSubject {
    function __construct() {
        parent::__construct();
        $this->_table = $this->db->options['prefix'].'member_readmanage';
        $this->_primary = 'mid';
        $this->_fields = array('mid', 'userid', 'idtype', 'id', 'dateline');
    }

    public function fetch_all_by_uid($userid, $idtype = 'contentid', $page = 1, $pagesize = 15) {
        $db = factory::db();
        $start = ($page - 1) * $pagesize;
        if($idtype == 'contentid') {
            return $db->select("SELECT * FROM #table_member_readmanage AS r INNER JOIN #table_content AS c ON r.id=c.contentid WHERE r.userid=$userid AND idtype='contentid' ORDER BY r.dateline DESC LIMIT $start, $pagesize");

        } elseif($idtype == 'catid') {
            return $db->select("SELECT *, c.name as title FROM #table_member_readmanage AS r INNER JOIN #table_category AS c ON r.id=c.catid WHERE r.userid=$userid AND idtype='catid' ORDER BY r.dateline DESC LIMIT $start, $pagesize");

        } elseif($idtype == 'proid') {
            return $db->select("SELECT *, p.name as title FROM #table_member_readmanage AS r INNER JOIN #table_property AS p ON r.id=p.proid WHERE r.userid=$userid AND idtype='proid' ORDER BY r.dateline DESC LIMIT $start, $pagesize");

        } elseif($idtype == 'mid') {
            return $db->select("SELECT r.*, m.mid as mmid, m.name as title FROM #table_member_readmanage AS r INNER JOIN #table_magazine AS m ON r.id=m.mid WHERE r.userid=$userid AND idtype='mid' ORDER BY r.dateline DESC LIMIT $start, $pagesize");

        } elseif($idtype == 'eid') {
            return $db->select("SELECT r.*, e.mid as emid, e.title as title FROM #table_member_readmanage AS r INNER JOIN #table_magazine_edition AS e ON r.id=e.eid WHERE r.userid=$userid AND idtype='eid' ORDER BY r.dateline DESC LIMIT $start, $pagesize");

        }
    }

    public function count_by_uid($userid, $idtype = 'contentid') {
        return $this->count(array('userid'=>$userid, 'idtype'=>$idtype));
    }


    public function fetch_by_mid($id) {
        return $this->get_by('mid', $id);
    }

    public function fetch_all($mids) {
        if(!$mids) return array();
        $db = factory::db();
        return $db->select("SELECT * FROM #table_member_readmanage WHERE mid IN (".implode(',', $mids).")");
    }

    public function fetch_by_uid($userid, $idtype = 'contentid', $id){
        if(!$id) return array();
        $db = factory::db();
        return $db->get("SELECT * FROM #table_member_readmanage WHERE userid='$userid' AND id='$id' AND idtype='$idtype'");
    }

    public function addinfo($userid, $idtype, $id) {
        if(!$userid || !$idtype || !$id) return false;
        $db = factory::db();
        $exist = false;
        if($idtype == 'contentid') {
            $exist = $db->get("SELECT * FROM #table_content WHERE contentid='$id' AND status=6");
        } elseif($idtype == 'catid') {
            $exist = $db->get("SELECT * FROM #table_category WHERE catid='$id'");
        } elseif($idtype == 'proid') {
            $exist = $db->get("SELECT * FROM #table_property WHERE proid='$id'");
        } elseif($idtype == 'mid') {
            $exist = $db->get("SELECT * FROM #table_magazine WHERE mid='$id'");
        } elseif($idtype == 'eid') {
            $exist = $db->get("SELECT * FROM #table_magazine_edition WHERE eid='$id'");
        }

        if($exist) {
            $this->insert(array('userid'=>$userid, 'idtype'=>$idtype, 'id'=>$id, 'dateline'=>time()));
        }
        return true;
    }

    public function attach(SplObserver $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(SplObserver $observer)
    {
        if($index = array_search($observer, $this->observers, true)) unset($this->observers[$index]);
    }

    public function notify()
    {
        foreach ($this->observers as $observer)
        {
            $observer->update($this);
        }
    }
}

?>