<?php
/**
 * CmsTop OpenPort API (PHP)
 *
 * @copyright (c) 2012 CmsTop (http://www.cmstop.com)
 * @version   $Id$
 */

abstract class CMSTOP_API
{
	/**
	 * Dispatcher 分发动作
	 *
	 * @param string $action
	 */
	public function dispatch($action) {
		if (!preg_match('/^[a-z]\w*$/', $action)) {
			header('HTTP/1.1 404');
			exit;
		}
		$action = $action . 'Action';
		if (!method_exists($this, $action)) {
			header('HTTP/1.1 404');
			exit;
		}
		//ob_start();
		$return = $this->$action();
		//$output = ob_get_clean();
		switch (true) {
		case is_int($return):
			if ($return < 100 || $return > 599) {
				$return = 200;
			}
			header("HTTP/1.1 $code");
			exit;
		case is_string($return):
			exit($return);
		case is_array($return):
			$json = json_encode($return);
			exit(empty($_GET['jsoncallback']) ? $json : "{$_GET['jsoncallback']}($json)");
		default:
			exit($output);
		}
	}
	
	/**
	 * API
	 * 
	 * 提供数据过滤界面(包含form表单)及资源文件(css,js)，用于选择数据
	 * 
	 * NOTE: 请根据$_REQUEST['authkey']进行验证
	 * 
	 * @return array 
	 * array(   
	 *    'state'  => true,
	 *    'html'   => '<form>拥有一个form包裹的表单元素集合</form>',
	 *    'assets' => array(
	 *        'assets'  => 'http://path/to/some.css http://path/to/some.js',
	 *        'depends' => 'lib.jQuery'
	 *    )
	 * )
	 * 或者
	 * array(
	 *    'state' => false,
	 *    'error' => 'error string'
	 * )
	 */
	abstract protected function getPickerAction();
	
	/**
	 * API
	 * 
	 * 提供数据过滤界面(包含表单元素)及资源文件(css,js)，用于操作自动数据调用
	 * 
	 * NOTE:
	 *  1) 请根据$_REQUEST['authkey']进行验证
	 *  2) 输入的$_POST数据用于视图渲染，该数据来自上一次表单的保存，
	 *     返回的html应当用$_POST中的数据填充表单
	 *  3) $_POST没有数据时返回空表单
	 * 
	 * @return array 
	 * array(   
	 *    'state'  => true,
	 *    'html'   => '表单元素结合(input textarea select)',
	 *    'assets' => array(
	 *        'assets'  => 'http://path/to/some.css http://path/to/some.js',
	 *        'depends' => 'lib.jQuery'
	 *    )
	 * )
	 * 或者
	 * array(
	 *    'state' => false,
	 *    'error' => 'error string'
	 * )
	 */
	abstract protected function getPortAction();
	
	/**
	 * API
	 * 
	 * 根据条件，查询数据
	 * 
	 * NOTE:
	 *  1) 请根据$_REQUEST['authkey']进行验证
	 *  2) 请求参数($_REQUEST)包含:
	 *   size   页面大小pagesize，当此值为空时，请给与默认值10，以防止查询出巨大数据
	 *   page   当前页面
	 *   fields 需要的字段，以","分割
	 *   ...    其它数据，来自表单的定义
	 *  3) fields中可能出现的字段(函数必须处理，选择性的返回):
	 *   id          记录编号
	 *   title       记录标题
	 *   url         记录链接
	 *   thumb       记录配图、封面
	 *   time        记录发布时间 unix timestamp
	 *   date        记录发布时间 格式：YYYY-MM-DD HH:mm:ss (Y-m-d H:i:s)
	 *   weight      记录的权重、优先级
	 *   description 记录的摘要、描述
	 *   tips        记录的相关信息以<br/>分割
	 * 
	 * @return array
	 * 当page参数被传递时,无论是否为0,返回JSON-OBJECT
	 * array (
	 *    'data'  => array(), // 查询出的记录集
	 *    'count' => int,     // 查询出的记录数,
	 *    'total' => int,     // 数据库中符合条件的总记录数,
	 *    'size'  => int,     // 页面大小 pagesize
	 *    'page'  => int,     // 当前页面 
	 * )
	 * 否则返回 JSON-ARRAY
	 * array(0=>row,1=>row,2=>row,...) // 查询出的记录集
	 */
	abstract protected function getDataAction();

	/**
	 * API
	 * 
	 * 根据文章id返回文章到文本内容
	 * 
	 * NOTE:
	 *  1) 请根据$_REQUEST['authkey']进行验证
	 *  2) 请求参数($_REQUEST)包含:
	 *   contentid		帖子的id
	 * @return array
	 * array(
	 *   'state' =>	boolean		状态位
	 *   'title' =>	string		请求文章的标题
	 *   'content' => string	请求文章的内容
	 * )
	 *
	 */
	abstract protected function getContentAction();
}

abstract class API_Db
{
	/**
	 * 创造一个数据库适配器
	 *
	 * Options:
	 *  host         => The hostname on which the database server resides.
	 *  port         => The port number where the database server is listening.
	 *  unix_socket  => The MySQL Unix socket (shouldn't be used with host or port)
	 *  dbname       => The name of the database.
	 *  charset      => The character set.
	 *  username     => The user name for login
	 *  password     => The password for login
	 *  case_folding => The folding force column names to a specific case.
	 *  fetch_mode   => The fetch mode must be one of the PDO::FETCH_* constants.
	 *  pdo_type     => The pdo type for mssql.
	 *  driver_options
	 *
	 * @param string $driver oci|mysql|mssql
	 * @param array  $options
	 *
	 * @return API_Adapter
	 */
	public static function factory($driver, array $options)
	{
		$driver = ucfirst(strtolower($driver));
		$driverClass = 'API_Adapter_'.$driver;
		return new $driverClass($options);
	}
}

abstract class API_Adapter
{
	/**
	 * @var PDO
	 */
	protected $_connection;

	protected $_config = array();

	protected $_fetchMode = PDO::FETCH_ASSOC;

	protected $_caseFolding = PDO::CASE_NATURAL;

	protected $_driver = 'pdo';

	/**
	 * Configure:
	 *  host         => The hostname on which the database server resides.
	 *  port         => The port number where the database server is listening.
	 *  unix_socket  => The MySQL Unix socket (shouldn't be used with host or port)
	 *  dbname       => The name of the database.
	 *  charset      => The character set.
	 *  username     => The user name for login
	 *  password     => The password for login
	 *  case_folding => The folding force column names to a specific case.
	 *  fetch_mode   => The fetch mode must be one of the PDO::FETCH_* constants.
	 *  pdo_type     => The pdo type for mssql.
	 *  driver_options
	 *
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->_config = $config;
		if (isset($config['case_folding'])) {
			$this->_caseFolding = $config['case_folding'];
		}

		if (isset($config['fetch_mode'])) {
			$this->setFetchMode((int)$config['fetch_mode']);
		}
	}

	/**
	 * Set the default fetch mode for this statement
	 *
	 * @param $mode The fetch mode must be one of the PDO::FETCH_* constants.
	 */
	public function setFetchMode($mode)
	{
		$this->_fetchMode = $mode;
	}

	protected function _connect()
	{
		// if we already have a PDO object, no need to re-connect.
		if ($this->_connection) {
			return;
		}

		// get the dsn first, because some adapters alter the $_pdoType
		$dsn = $this->_dsn();

		// check for PDO extension
		if (!extension_loaded('pdo') || !in_array($this->_driver, PDO::getAvailableDrivers())) {
			throw new Exception("require depends component");
		}

		$this->_connection = new PDO(
			$dsn,
			$this->_config['username'],
			$this->_config['password'],
			empty($this->_config['driver_options']) ? null : $this->_config['driver_options']
		);

		// set the PDO connection to perform case-folding on array keys, or not
		$this->_connection->setAttribute(PDO::ATTR_CASE, $this->_caseFolding);

		// always use exceptions.
		$this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	abstract protected function _dsn();

	/**
	 * @return PDO
	 */
	public function getConnection()
	{
		$this->_connect();
		return $this->_connection;
	}

	/**
	 * @param string $sql
	 *
	 * @return PDOStatement
	 */
	public function prepare($sql)
	{
		$stmt = $this->getConnection()->prepare($sql);
		$stmt->setFetchMode($this->_fetchMode);
		return $stmt;
	}

	/**
	 * @param string $sql
	 * @param array  $bind
	 *
	 * @return PDOStatement
	 */
	public function query($sql, $bind = array())
	{
		// make sure $bind to an array;
		if (is_array($bind)) {
			$tmpBind = array();
			foreach ($bind as $name => $value) {
				if (is_int($name)) {
					$tmpBind[$name] = $value;
				} else {
					if ($name[0] != ':') {
						$name = ":$name";
					}
					if (is_array($value)) {
						if ($ret = $this->whereIn($name, $value, $tmpBind)) {
							$sql = preg_replace('/'.preg_quote($name).'\b/', $ret, $sql);
						}
					} else {
						$tmpBind[$name] = $value;
					}
				}
			}
			$bind = $tmpBind;
		} else {
			$bind = array($bind);
		}

		$stmt = $this->prepare($sql);

		$stmt->execute($bind);

		// return the results embedded in the prepared statement object
		$stmt->setFetchMode($this->_fetchMode);
		return $stmt;
	}

	/**
	 * @param string $sql
	 * @param array  $bind
	 * @param int    $count
	 * @param int    $offset
	 *
	 * @return array
	 */
	public function fetchAll($sql, $bind = array(), $count = 0, $offset = 0)
	{
		if ($count > 0) {
			$sql = $this->limit($sql, $count, $offset);
		}
		$stmt = $this->query($sql, $bind);
		$result = $stmt->fetchAll($this->_fetchMode);
		return $result;
	}

	/**
	 * @param string $sql
	 * @param array  $bind
	 *
	 * @return array|object|false
	 */
	public function fetchRow($sql, $bind = array())
	{
		$stmt = $this->query($sql, $bind);
		$result = $stmt->fetch($this->_fetchMode);
		return $result;
	}

	/**
	 * @param string $sql
	 * @param array  $bind
	 * @param int    $num
	 *
	 * @return string|false
	 */
	public function fetchColumn($sql, $bind = array(), $num = 0)
	{
		$stmt = $this->query($sql, $bind);
		$result = $stmt->fetchColumn($num);
		return $result;
	}

	/**
	 * @param string $sql
	 * @param array  $bind
	 * @param int    $num
	 *
	 * @return string
	 */
	public function fetchCol($sql, $bind = array(), $num = 0)
	{
		$stmt = $this->query($sql, $bind);
		return $stmt->fetchAll(PDO::FETCH_COLUMN, $num);
	}

	/**
	 * @param string $name
	 * @param array  $values
	 * @param array  $bind
	 *
	 * @return string
	 */
	public function whereIn($name, array $values, array &$bind)
	{
		$keys = array();
		foreach (array_unique($values) as $key=>$val) {
			$key = $name ."_". $key;
			$keys[] = $key;
			$bind[$key] = $val;
		}
		return implode(',', $keys);
	}

	/**
	 * @param string $sql
	 * @param int    $count
	 * @param int    $offset
	 *
	 * @return string
	 */
	abstract public function limit($sql, $count, $offset = 0);

	/**
	 * sql查找字符串
	 *
	 * @param $needle
	 * @param $subject
	 *
	 * @return string
	 */
	public function instr($subject, $needle)
	{
		return "INSTR({$subject}, {$needle}) > 0";
	}

	/**
	 * sql连接字符串
	 *
	 * @param  string args... 字符串碎片
	 * @return string
	 */
	public function concat()
	{
		$slices = func_get_args();
		return 'CONCAT(' . implode(',', $slices) . ')';
	}

	/**
	 * sql date格式转字符串格式 2012-07-13 13:05:06
	 *
	 * @param $date
	 *
	 * @return mixed
	 */
	public function dateToChar($date)
	{
		return "DATE_FORMAT({$date}, '%Y-%m-%d %H:%i:%s')";
	}

	/**
	 * sql 时间字符串(2012-07-13 13:05:06)转Date
	 *
	 * @param $char
	 *
	 * @return string
	 */
	public function charToDate($char)
	{
		return $char;
	}
}

class API_Adapter_Oci extends API_Adapter
{
	protected $_driver = 'oci';

	protected function _dsn()
	{
		$dsn = $this->_config;
		return 'oci:dbname=//'.$dsn['host'].':'
			.(empty($dsn['port']) ? 1521 : $dsn['port']).'/'.$dsn['dbname']
			.(empty($dsn['charset']) ? '' : ";charset={$dsn['charset']}");
	}
	public function limit($sql, $count, $offset = 0)
	{
		$limit_sql = "SELECT t2.*
	        FROM (
	            SELECT t1.*, ROWNUM AS \"t_rownum\"
	            FROM (
	                " . $sql . "
	            ) t1
	        ) t2
	        WHERE t2.\"t_rownum\" BETWEEN " . ($offset+1) . " AND " . ($offset+$count);
		return $limit_sql;
	}
	public function concat()
	{
		$slices = func_get_args();
		return implode('||', $slices);
	}
	public function dateToChar($date)
	{
		return "TO_CHAR({$date},'YYYY-MM-DD HH24:MI:SS')";
	}
	public function charToDate($char)
	{
		return "TO_DATE({$char},'YYYY-MM-DD HH24:MI:SS')";
	}
}
class API_Adapter_Mysql extends API_Adapter
{
	protected $_driver = 'mysql';

	protected function _dsn()
	{
		// baseline of DSN parts
		$dsn = $this->_config;

		$str = "mysql:";
		if (isset($dsn['unix_socket'])) {
			$str .= "unix_socket={$dsn['unix_socket']}";
		} else {
			$str .= "host={$dsn['host']}";
			if (isset($dsn['port'])) {
				$str .= ";port={$dsn['port']}";
			}
		}
		if (!empty($dsn['dbname'])) {
			$str .= ";dbname={$dsn['dbname']}";
		}
		$init = "SET SQL_MODE=''";
		if (!empty($dsn['charset'])) {
			$str .= ";charset={$dsn['charset']}";
			$init .= ", NAMES '" . $dsn['charset'] . "';";
		}
		$this->_config['driver_options'][1002] = $init;

		return $str;
	}

	public function limit($sql, $count, $offset = 0)
	{
		$sql .= " LIMIT $count";
		if ($offset > 0) {
			$sql .= " OFFSET $offset";
		}

		return $sql;
	}
}

/**
 * depends PDO_Dblib
 */
class API_Adapter_Mssql extends API_Adapter
{
	protected $_driver = 'dblib';
	protected $_server2000 = false;

	public function __construct(array $config)
	{
		parent::__construct($config);
		$this->_server2000 = strpos($this->fetchColumn('select @@version'), 'Microsoft SQL Server  2000') !== false;
	}

	protected function _dsn()
	{
		$dsn = $this->_config;

		if (isset($dsn['pdo_type'])) {
			switch (strtolower($dsn['pdo_type'])) {
			case 'mssql':
				$this->_driver = 'mssql';
				break;
			case 'freetds': case 'dblib': default:
				$this->_driver = 'dblib';
				break;
			}
		}

		$str = "dblib:host={$dsn['host']}";

		if (isset($dsn['port'])) {
			$seperator = ':';
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$seperator = ',';
			}
			$str .= $seperator . $dsn['port'];
		}
		if (!empty($dsn['dbname'])) {
			$str .= ";dbname={$dsn['dbname']}";
		}
		if (!empty($dsn['charset'])) {
			$str .= ";charset={$dsn['charset']}";
		}

		return $str;
	}

	/**
	 * need a order by
	 *
	 * @param string $sql
	 * @param int    $count
	 * @param int    $offset
	 *
	 * @return mixed|string
	 */
	public function limit($sql, $count, $offset = 0)
	{
		return $this->_server2000
			? $this->_limit2000($sql, $count, $offset)
			: $this->_limit2005($sql, $count, $offset);
	}

	protected function _limit2000($sql, $count, $offset = 0)
	{
		$sql = preg_replace(
			'/^SELECT\s+(DISTINCT\s)?/i',
			'SELECT $1TOP ' . ($count+$offset) . ' ',
			$sql
		);

		if ($offset > 0) {
			$orderby = stristr($sql, 'ORDER BY');

			if ($orderby !== false) {
				$orderParts = explode(',', substr($orderby, 8));
				$pregReplaceCount = null;
				$orderbyInverseParts = array();
				foreach ($orderParts as $orderPart) {
					$orderPart = rtrim($orderPart);
					$inv = preg_replace('/\s+desc$/i', ' ASC', $orderPart, 1, $pregReplaceCount);
					if ($pregReplaceCount) {
						$orderbyInverseParts[] = $inv;
						continue;
					}
					$inv = preg_replace('/\s+asc$/i', ' DESC', $orderPart, 1, $pregReplaceCount);
					if ($pregReplaceCount) {
						$orderbyInverseParts[] = $inv;
						continue;
					} else {
						$orderbyInverseParts[] = $orderPart . ' DESC';
					}
				}

				$orderbyInverse = 'ORDER BY ' . implode(', ', $orderbyInverseParts);
			}


			$sql = 'SELECT * FROM (SELECT TOP ' . $count . ' * FROM (' . $sql . ') AS inner_tbl';
			if ($orderby !== false) {
				$sql .= ' ' . $orderbyInverse . ' ';
			}
			$sql .= ') AS outer_tbl';
			if ($orderby !== false) {
				$sql .= ' ' . $orderby;
			}
		}

		return $sql;
	}

	protected function _limit2005($sql, $count, $offset = 0)
	{
		if ($offset == 0) {
			$sql = preg_replace('/^SELECT\s/i', 'SELECT TOP ' . $count . ' ', $sql);
		} else {
			$orderby = stristr($sql, 'ORDER BY');

			if (!$orderby) {
				$over = 'ORDER BY (SELECT 0)';
			} else {
				$over = preg_replace('/\"[^,]*\".\"([^,]*)\"/i', '"inner_tbl"."$1"', $orderby);
			}

			// Remove ORDER BY clause from $sql
			$sql = preg_replace('/\s+ORDER BY(.*)/', '', $sql);

			// Add ORDER BY clause as an argument for ROW_NUMBER()
			$sql = "SELECT ROW_NUMBER() OVER ($over) AS \"TBL_NUM\", * FROM ($sql) AS inner_tbl";

			$start = $offset + 1;
			$end = $offset + $count;

			$sql = "WITH outer_tbl AS ($sql) SELECT * FROM outer_tbl WHERE \"TBL_NUM\" BETWEEN $start AND $end";
		}

		return $sql;
	}

	public function instr($subject, $needle)
	{
		return "CHARINDEX({$needle}, {$subject}) > 0";
	}
	public function concat()
	{
		$slices = func_get_args();
		return implode('+', $slices);
	}
	public function dateToChar($date)
	{
		return "CONVERT(varchar(20), {$date}, 120)";
	}
}