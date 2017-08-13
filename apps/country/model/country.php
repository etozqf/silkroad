<?php
/**
 * 国别报告,前台生成pdf对应模型
 * 
 * @author  houxiaowen@cmstop.com
 */
class model_country extends model
{
    private $data;

    function __construct()
    {
        parent::__construct();

        $this->_table = $this->db->options['prefix'] . 'country_pdf';
        $this->_primary = 'cid';
        $this->_fields = array(
            'cid', 'uid', 'contentid', 'pdf', 'property', 'pubtime', 'ip', 'choren', 'status',
        );
        $this->_readonly = array('cid');
        $this->_create_autofill = array('pubtime' => TIME, 'ip' => IP,);
        $this->_update_autofill = array();
        $this->_filters_input = array();
        $this->_filters_output = array();
        $this->_validators = array(
            'pdf' => array(
                'not_empty' => array('内容不能为空'),

            )
        );
    }

    /**
     * 添加国别报告PDF地址以及相关信息
     */
    public function add($pdf, $uid, $contentid, $choren, $property)
    {
        $data = array(
            'pdf'       => $pdf,
            'uid'       => $uid,
            'contentid' => $contentid,
            'choren'    => $choren,
            'property'  => $property,
        );
        $this->insert($data);
    }

    /**
     * 获取生成pdf的内容
     */
    public function get_pdfdata($contentids)
    {
        $db = factory::db();
        $sql = "SELECT a.content, c.catid, ca.name, ca.alias FROM `#table_content` c LEFT JOIN `#table_article` a ON c.`contentid` = a.`contentid` LEFT JOIN `#table_category` ca ON c.`catid` = ca.`catid` WHERE c.`contentid` IN({$contentids}) AND c.`status` = 6 ORDER BY ca.`catid` ASC ";
        
        $res = $db->select($sql);
        return $res;
    }

    /**
     * 获取栏目和内容
     */
    public function get_content($proid, $catid)
    {
        $db = factory::db();
        $sql = "SELECT c.contentid, a.content FROM `#table_category` ca LEFT JOIN `#table_content` c ON ca.`catid` = c.`catid` LEFT JOIN `#table_article` a ON c.`contentid` = a.`contentid` LEFT JOIN `#table_content_property` cp ON c.`contentid` = cp.`contentid` WHERE ca.`catid` = {$catid} AND cp.`proid` = {$proid} AND c.`status` = 6 AND c.`modelid` = 1 ORDER BY c.`published` DESC ";
        $res = $db->get($sql);
        return $res;
    }

    /**
     * 获取简介
     */
    public function get_jianjie($proid, $catid)
    {
        $db = factory::db();
        $sql = "SELECT c.contentid, c.thumb, a.description, ca.name, ca.alias FROM `#table_content` c LEFT JOIN `#table_category` ca ON ca.`catid` = c.`catid` LEFT JOIN `#table_article` a ON c.`contentid` = a.`contentid` LEFT JOIN `#table_content_property` cp ON c.`contentid` = cp.`contentid` WHERE ca.`catid` = {$catid} AND cp.`proid` = {$proid} AND c.`status` = 6 AND c.`modelid` = 1 ORDER BY c.`published` DESC ";
        $res = $db->get($sql);
        if (!$res) {
            $res['name'] = table('category', $catid, 'name');
            $res['alias'] = table('category', $catid, 'alias');
        }
        return $res;
    }

    /**
     * 获取国别报告栏目
     */
    public function cat($gbbg)
    {
        $category = loader::model('category', 'system');
        $where = array('parentid' => $gbbg);
        $field = 'catid, name, pinyin, abbr, alias';
        $categorys = $category->select($where, $field);
        return $categorys;
    }

    /**
     * 分页显示
     */
    public function ls($where, $fields = '*', $order, $page, $pagesize)
    {

        $data = $this->page($where,$fields,$order,$page,$pagesize);
        $result = array(
            'total' => intval($this->count($where)),
            'data'  =>$data,
        );
        return $result;
    }
}
