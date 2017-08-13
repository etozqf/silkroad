<?php

class discuz_x2 extends discuz_vendor
{
    function __construct(array $options = array())
    {
        $this->prefix = value($options, 'prefix', '');
        $this->table_forum = 'forum_forum';
        $this->table_thread = 'forum_thread';
        $this->table_post = 'forum_post';
        $this->table_member = 'common_member';
    }

    function member_query($fields = '*', $options = array(), $page = 1, $pagesize = null)
    {
        $where = $this->_where($options);

        return $this->_db->page("
            SELECT {$fields}
            FROM {$this->prefix}{$this->table_member}
            {$where}
        ", $page, $pagesize);
    }

    function member_count($options = array())
    {
        $where = $this->_where($options);

        $result = $this->_db->get("
            SELECT COUNT(*) as `total`
            FROM {$this->prefix}{$this->table_member}
            {$where}
        ");

        return $result ? intval($result['total']) : 0;
    }

    function forum_query($fields = '*', $options = array())
    {
        $where = $this->_where($options);

        return $this->_db->select("
            SELECT {$fields}
            FROM {$this->prefix}{$this->table_forum}
            {$where}
        	ORDER BY type, displayorder
        ");
    }

    function has_table_forum()
    {
        $tables = $this->_db->list_tables();
        return in_array($this->prefix . $this->table_forum, $tables);
    }

    function has_table_thread()
    {
        $tables = $this->_db->list_tables();
        return in_array($this->prefix . $this->table_thread, $tables);
    }

    protected function _where($options)
    {
        $where = is_array($options) ? implode(' AND ', $options) : $options;
        if ($where)
        {
            $where = " WHERE $where";
        }
        return $where;
    }
}