<?php

class import extends object 
{
	public $sqldb;
	public $remotedb;

    protected $_error;

	function __construct()
	{
		loader::_load('import_functions','lib'); //引入自定义函数库
	}

	function connect_db($dbtype, $dbhost, $dbport, $dbuser, $dbpw, $dbname = '', $charset = '')
	{
		$this->remotedb = $dbname;
		$dsn = '';
		$driver_options = array();
		
		if($dbtype == 'mysql') {
			$dsn = "mysql:host=$dbhost;dbname=$dbname;port=$dbport;charset=$charset";
		} else if ($dbtype == 'odbc') {
			$dsn = "odbc:$dbhost";
		} else if ($dbtype == 'access_odbc') {
			$dsn = "odbc:driver={microsoft access driver (*.mdb)};dbq=$dbhost";
		} else if ($dbtype == 'mssql') {
			$dsn = "mssql:host=$dbhost;dbname=$dbname;charset=$charset";
		}
		
		try {
			$this->sqldb = new PDO($dsn, $dbuser, $dbpw, $driver_options);
			$this->sqldb->exec("SET character_set_connection='$charset',character_set_results='$charset',character_set_client=binary");
		} catch (PDOException $e) {
			$this->_error = 'Connection failed: ' . $e->getMessage();
			return false;
		}
		return true;
	}

	function filter_fields($info, $offset, $keyid)
	{
		$result = array();
		$result['table'] = trim($info['table']);
		$firstdot = strpos($result['table'], ',');
		$firsttable = false;
		if($firstdot)
		{
			$startpos = intval(strpos($result['table'], ' '));
			$firsttable = trim(substr($result['table'], $startpos, $firstdot-$startpos));
		}

		$condition = trim($info['condition']);
		if($condition) $result['condition'] = " WHERE $condition";
		$number = intval($info['number']);
		if($number) $result['limit'] = " LIMIT $offset,$number";

		$result['total'] = $this->get_total($info);
		
		$result['orderby'] = $firsttable ? $firsttable.'.'.$keyid : $keyid;
		$result['selectfield'] = $info['selectfield'] ? $info['selectfield'] : '*';
		return $result;
	}
	
	//取数据库
	function get_databases()
	{
		$result = $this->sqldb->query("SHOW DATABASES");
		$r = $result->fetchAll(PDO::FETCH_ASSOC);
		return $r;
	}
	
	//取表
	function get_tables()
	{
		$result = $this->sqldb->query("SHOW TABLES");
		$r = $result->fetchAll(PDO::FETCH_ASSOC);
		return $r;
	}
	
	//取字段
	function get_fields($table,$dbtype)
	{
		$fields = array();
		$result = $this->sqldb->query("SHOW COLUMNS FROM $table");
		$r = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach ($r as $key => $value)
		{
			$fields[] = $value['Field'];
		}
		return $fields;
	}
	
	function get_total($info)
	{
		$db = $this->connect_db($info['dbtype'], $info['dbhost'], $info['dbport'], $info['dbuser'], $info['dbpw'], $info['dbname'], $info['charset']);
		if(!$db) return false;
		
		$table = trim($info['table']);
		$condition = str_replace('$maxid', 0, $info['condition']);
		if($condition) $condition = " WHERE $condition";
		
		$sql="SELECT COUNT(*) AS total FROM $table $condition";
		
		$result = $this->sqldb->query($sql);
		$r = $result->fetch(PDO::FETCH_ASSOC);
		
		$result = intval($r['total']);
		return $result;
	}
	
	function get_data($sql, $offset, $size)
	{
		$res = $this->sqldb->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
		$res->execute();
		$importnum = $res->rowCount();
		$r = $res->fetch(PDO::FETCH_ASSOC);
		
	}
	
	function add_member($import_info, $offset)
	{
		$this->member = loader::model('member','member');
		$this->member_detail = loader::model('member_detail','member');
		//先取远程数据库中数据
		$datefunc = $data = $newdata = $datadefault = array();
		$keyid = $import_info['userid']['field'];
		if(!$keyid)
		{
			$this->_error = '没有指定源数据库对应 CmsTop 系统的 ID';
			return false;
		}
		$number = $import_info['number'];
		$dbcon = $this->connect_db($import_info['dbtype'], $import_info['dbhost'], $import_info['dbport'], $import_info['dbuser'], $import_info['dbpw'], $import_info['dbname'], $import_info['charset']);
		if(!$dbcon) return false;

        $fields = @include app_dir('system') . 'config' . DS . 'member_fields.php';
        if (! $fields) return false;
		
		if(is_array($fields)) foreach($fields as $field=>$val_field)
		{
			if($field == 'userid') continue;
			$datadefault[$field] = trim($import_info[$field]['value']);
			$data[$field] = trim($import_info[$field]['field']);
			$datafunc[$field] = trim($import_info[$field]['func']);
		}
		//将groupids处理
		if(is_array($import_info['groupids']))foreach ($import_info['groupids'] as $k => $v)
		{
			if(!empty($v))
			{
				$groupidArr=explode (',', $v);
				foreach($groupidArr as $ki => $vi)
				{
					$datagroup[$vi] = $k;
				}
			}
		}
		
		$result = $this->filter_fields($import_info, $offset, $keyid);
		@extract($result);
		
		// 处理SQL
		if($import_info['dbtype'] == 'mysql')
		{
			$sql="SELECT $selectfield FROM $table $condition ORDER BY  $orderby $limit";
		}
		else
		{
			$sqlNum = $number + $offset;
			$sql = "SELECT $selectfield FROM (SELECT TOP $number $selectfield FROM (SELECT TOP $sqlNum $selectfield FROM $table $condition ORDER BY $orderby DESC) ORDER BY $orderby) ORDER BY $orderby DESC";
		}

		if ($res = $this->sqldb->query($sql))
        {
            $importnum = $res->rowCount();
            while ($r = $res->fetch(PDO::FETCH_ASSOC))
            {
                $r = new_addslashes($r);
                foreach($data as $k => $v)
                {
                    //处理用户组
                    if($k == 'groupid')
                    {
                        $newdata[$k] = (isset($datagroup[$r[$v]]))?$datagroup[$r[$v]]:$import_info['defaultgroupid'];
                    }
                    elseif (!empty($v) && !empty($r[$v])) //如果填写了对应的字段并且取的数据有值的话
                    {
                        $newdata[$k] = (!empty($datafunc[$k]))?$datafunc[$k]($r[$v]):$r[$v]; //默认有处理函数
                    }
                    elseif (!empty($datadefault[$k])) //有默认值
                    {
                        $newdata[$k] = $datadefault[$k]; //设为默认值
                    }
                }
                //是否检测重复
                if($import_info['membercheck'])
                {
                    if(! $this->member->validate(array('username' => $r['username'], 'email' => $r['email']))) continue;
                }
                //处理字符集
                $sysCharset = str_replace('-','', config('config', 'charset'));
                if($import_info['charset'] != $sysCharset ) $newdata = str_charset($import_info['charset'], $sysCharset, $newdata);

                $userid = $this->member->add($newdata);
                if(!empty($userid))
                {
                    $newdata['userid']=$userid;
                    $this->member_detail->add($newdata);
                }
                unset($newdata);//添加完重置
            }
        }
        else
        {
            $importnum = 0;
        }

		
		$name = $import_info['name'];
		$finished = 0;
		if($number && ($importnum < $number))
		{
			$finished = 1;
		}
		$import_info['maxid'] = $maxid + $number;
		$import_info['importtime'] = TIME;
		$this->setting($import_info, 'member');
		return $finished.'-'.$total;
	}
	
	function add_article($import_info, $offset)
	{
		$this->article = loader::model('admin/article','article');
		$datafunc = $data = $newdata = $datadefault = $datacat= array();
		$keyid = $import_info['contentid']['field'];
		if(!$keyid)
		{
			$this->_error = '没有指定源数据库对应 CmsTop 系统的 ID';
			return false;
		}
		
		$number = $import_info['number'];
		$dbcon = $this->connect_db($import_info['dbtype'], $import_info['dbhost'], $import_info['dbport'], $import_info['dbuser'], $import_info['dbpw'], $import_info['dbname'], $import_info['charset']);
		if(! $dbcon) return false;

		$fields = @include app_dir('system') . 'config' . DS . 'article_fields.php';
        if (! $fields) return false;
		foreach($fields as $field => $val_field)
		{
			if($field == 'contentid') continue;
			
			$datadefault[$field] = trim($import_info[$field]['value']);
			$data[$field] = trim($import_info[$field]['field']);
			$datafunc[$field] = trim($import_info[$field]['func']);
		}
		//将CATID处理
		if(is_array($import_info['catids'])) foreach ($import_info['catids'] as $k => $v)
		{
			if(!empty($v))
			{
				$catidArr=explode (',', $v);
				foreach($catidArr as $ki => $vi)
				{
					$datacat[$vi] = $k;
				}
			}
		}
		$result = $this->filter_fields($import_info, $offset, $keyid);
		@extract($result);
		
		//处理SQL
		if($import_info['dbtype'] == 'mysql')
		{
			$sql="SELECT $selectfield FROM $table $condition ORDER BY  $orderby $limit";
		}
		else
		{
			$sqlNum = $number + $offset;
			$sql = "SELECT $selectfield FROM (SELECT TOP $number $selectfield FROM (SELECT TOP $sqlNum $selectfield FROM $table $condition ORDER BY $orderby DESC) ORDER BY $orderby) ORDER BY $orderby DESC";
		}

		if ($res = $this->sqldb->query($sql))
        {
            $importnum = $res->rowCount();
            while ($r = $res->fetch(PDO::FETCH_ASSOC))
            {
                $r = new_addslashes($r);
                foreach($data as $k => $v)
                {
                    //处理catid
                    if($k == 'catid')
                    {
                        $newdata[$k] = (isset($datacat[$r[$v]]))?$datacat[$r[$v]]:$import_info['defaultcatid'];
                    }
                    elseif(!empty($v) && !empty($r[$v]))
                    {
                        $newdata[$k] = (!empty($datafunc[$k]))?$datafunc[$k]($r[$v]):$r[$v]; //默认有处理函数
                    }
                    elseif (!empty($datadefault[$k])) //有默认值
                    {
                        $newdata[$k] = $datadefault[$k]; //设为默认值
                    }
                }
                $sysCharset = str_replace('-','', config('config', 'charset'));
                if($import_info['charset'] != $sysCharset ) $newdata = str_charset($import_info['charset'], $sysCharset, $newdata);
                $this->article->add($newdata); //数据导入库
                unset($newdata);
            } //end while
        }
        else
        {
            $importnum = 0;
        }
		
		$name = $import_info['name'];
		$finished = 0;
		if($number && ($importnum < $number))
	    {
			$finished = 1;
		}
		
		$import_info['maxid'] = $offset+$importnum;
		$import_info['importtime'] = TIME;
		$this->setting($import_info, 'article');
		
		return $finished.'-'.$total;
	}
	
	function setting($setting, $type = 'article')
	{
		if(empty($setting) || !is_array($setting)) return false;
		$setting['edittime'] = TIME;
		$array[$setting['names']] = $setting;
		cache_write('import/'.$type.'/'.$setting['names'].'.php', $array);
		return true;
	}

	function view($name, $type = 'article')
	{
		if(!$name || !$type) return false;
		$array = cache_read('import/'.$type.'/'.$name.'.php');
		return $array[$name];
	}

	function delete($name, $type)
	{
		if(!isset($name)) return false;
		cache_delete('import/'.$type.'/'.$name.'.php');
		return true;
	}

    function error()
    {
        return $this->_error;
    }
}