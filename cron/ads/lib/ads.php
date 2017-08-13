<?php

/** 数据库配置文件路径 */
define('DB_CONFIG_FILE', __DIR__ . '/../../../config/db.php');

/**
 * 调用接口自动创建和部署CmsTop Ads广告代码
 */
class AdsClient
{
    const GATEWAY = 'http://adm.cmstop.cn/api/install/company';
    const GATEKEY = 'b2KyhWhXGpKN8sMexGRUiyEKYeTEjz';

    protected $config = array();
    protected $errno;
    protected $error;
    private $_data;

    /**
     * @param array $options company_name, company_url, user_email[, user_password]
     */
    function __construct(array $options)
    {
        $this->config['api'] = $options;
        $this->config['db'] = include DB_CONFIG_FILE;
        $this->_checkDB();
    }

    function install()
    {
        $params = $this->config['api'];
        $params['key'] = self::GATEKEY;
        $url = self::GATEWAY . '?' . http_build_query($params);

        $ret = $this->_requestHTTP($url);
        if (!$ret) {
            throw new Exception('API请求失败');
        }
        $ret = json_decode($ret, true);
        if ($ret['ret'] == '-1') {
            throw new Exception($ret['msg']);
        }
        $data = $ret['data'];
        if (empty($data['position'])) {
            throw new Exception('广告创建失败，返回数据为空');
        }
        $this->_updateSection($data['position']);
        $this->_updateSetting($data['position']);
        return true;
    }

    private function _updateSection(array $data)
    {
        $db = $this->_connectDB();
        $stmt = $db->prepare("UPDATE cmstop_section SET origdata='', template='', data=? WHERE sectionid=?");
        foreach ($data as $row) {
            $html = $this->getAdsHtml($row);
            if ($row['section'] == 0) {
                continue;
            } else {
                $section_id = $row['section'];
                $stmt->execute(array($html, $section_id));
            }
        }
        return true;
    }

    /**
     * 更新移动版广告
     *
     * @param array $data
     * @return bool
     */
    private function _updateSetting(array $data)
    {
        $val = array(
            "mobile" => array(
                "type" => "html",
                "src" => "",
                "link" => "",
                "code" => ""
            ),
            "pad" => array(
                "type" => "html",
                "src" => "",
                "link" => "",
                "code" => ""
            )
        );
        foreach ($data as $row) {
            $html = $this->getAdsHtml($row);
            if (isset($row['setting'])) {
                if ($row['setting'] == 'ad.mobile.code') {
                    $val['mobile']['code'] = $html;
                } elseif ($row['setting'] == 'ad.pad.code') {
                    $val['pad']['code'] = $html;
                }
            }
        }

        $db = $this->_connectDB();
        $stmt = $db->prepare("UPDATE cmstop_setting SET value=? WHERE app='mobile' AND var='ad'");
        return $stmt->execute(array(json_encode($val)));
    }

    private function getAdsHtml(array $data)
    {
        static $codeTpl;
        if (empty($codeTpl)) {
            $codeTpl = '<script type="text/javascript" id="adm-[ID]">' . PHP_EOL;
            $codeTpl .= '(function() {' . PHP_EOL;
            $codeTpl .= '   window.ADMBlocks = window.ADMBlocks || [];' . PHP_EOL;
            $codeTpl .= '   ADMBlocks.push({' . PHP_EOL;
            $codeTpl .= '       id : \'[ID]\',  // 广告位id' . PHP_EOL;
            $codeTpl .= '       width : \'[WIDTH]\',  // 宽' . PHP_EOL;
            $codeTpl .= '       height : \'[HEIGHT]\',  // 高' . PHP_EOL;
            $codeTpl .= '       type : \'[TYPE]\'  // 类型' . PHP_EOL;
            $codeTpl .= '   });' . PHP_EOL;
            $codeTpl .= '   var h=document.getElementsByTagName(\'head\')[0], s=document.createElement(\'script\');' . PHP_EOL;
            $codeTpl .= '   s.async=true; s.src=\'http://ad.cmstop.cn/js/show.js\';' . PHP_EOL;
            $codeTpl .= '   h && h.insertBefore(s,h.firstChild)' . PHP_EOL;
            $codeTpl .= '})();' . PHP_EOL;
            $codeTpl .= '</script>' . PHP_EOL;
        }

        $html = $codeTpl;
        foreach ($data as $field => $val) {
            $html = str_replace('[' . strtoupper($field) . ']', $val, $html);
        }
        return $html;
    }

    private function _checkDB()
    {
        $db = $this->_connectDB();
        $query = $db->query("SELECT count(*) FROM cmstop_section");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result === false) {
            throw new PDOException('query data from cmstop_section error: ' . $query->errorInfo(), $query->errorCode());
        }
    }

    private function _connectDB()
    {
        $options = $this->config['db'];
        try {
            $dbh = new PDO($options['driver'] . ':host=' . $options['host'] . ';port=' . $options['port'] . ';dbname=' . $options['dbname'] . ';charset=' . $options['charset'], $options['username'], $options['password'], array(PDO::ATTR_PERSISTENT => ($options['pconnect'] ? true : false)));
        } catch (PDOException $e) {
            $this->errno = $e->getCode();
            $this->error = $e->getMessage();
            return false;
        }
        if ($options['driver'] == 'mysql') {
            $dbh->exec("SET character_set_connection='" . $options['charset'] . "',character_set_results='" . $options['charset'] . "',character_set_client=binary" . ($dbh->query("SELECT version()")->fetchColumn(0) > '5.0.1' ? ",sql_mode=''" : ''));
        }
        return $dbh;
    }

    private function _requestHTTP($url = '')
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 35);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);

        if (!ini_get('safe_mode') && ini_get('open_basedir') == '') {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $ret = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode != '200') {
            return false;
        } else {
            return $ret;
        }
    }
}