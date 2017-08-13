<?php
ini_set('error_reporting', E_ERROR | E_PARSE);
ini_set('display_errors', 1);	// 是否开启错误日志显示
define('XHPROF_DEBUG', 0);
define('LOG_ERROR', TRUE);      // 是否记录致命错误


define('DOMAIN', 'db.silkroad.news.cn');
define('ADMIN_URL', 'http://pubstage.db.silkroad.news.cn/');   //后台网址
define('APP_URL', 'http://app.db.silkroad.news.cn/');       //动态访问网址
define('IMG_URL', 'http://img.db.silkroad.news.cn/');       //公共图片、JS、CSS网址
define('UPLOAD_URL', 'http://upload.db.silkroad.news.cn/'); //附件网址
define('WWW_URL', 'http://db.silkroad.news.cn/');       //静态网页网址
define('WAP_URL', 'http://wap.db.silkroad.news.cn/');       //WAP网址
define('SPACE_URL', 'http://space.db.silkroad.news.cn/');       //专栏网址
define('API_URL', 'http://api.db.silkroad.news.cn/');       //接口网址
define('MOBILE_URL', 'http://m.db.silkroad.news.cn/');      //移动网址
define('MOBILE_PROTOCOL', 'cmstop');                //移动端注册的协议
define('LOGIN_URL', 'http://app.silkroad.news.cn/?app=system&controller=freelogin&action=login');  //登录地址
define('LOGIN_URL_EN', 'http://app.db.silkroad.news.cn/?app=member&controller=index&action=login_newcn');  //EN登录地址

define('FW_PATH', ROOT_PATH.'framework'.DS);
define('CACHE_PATH', ROOT_PATH.'data'.DS.'cache'.DS);
define('PUBLIC_PATH', ROOT_PATH.'public'.DS);
define('WWW_PATH', PUBLIC_PATH.'www'.DS);
define('UPLOAD_PATH', PUBLIC_PATH.'upload'.DS);
define('WAP_PATH', PUBLIC_PATH.'wap'.DS);
define('IMG_PATH', PUBLIC_PATH.'img'.DS);
define('MOBILE_PATH', PUBLIC_PATH.'mobile'.DS);


/*允许上传的文件类型 ，开始和末尾不加|，*代表任意格式  */
//define('UPLOAD_FILE_EXTS','gif|jpg|jpeg|bmp|png|txt|zip|rar|doc|docx|xls|ppt|pdf');
define('UPLOAD_FILE_EXTS','*');

//constants
define('MALE',			1);
define('FEMALE',		2);

//message
define('MESSAGE_NEW',			1);
define('MESSAGE_READ',			2);
define('MESSAGE_REPLIED',		3);
define('MESSAGE_DELETE',		4);

//page
define('FRAG_AUTO',				1);
define('FRAG_FEED',				2);
define('FRAG_MANUL',			3);
define('FRAG_HTML',				4);

//page state
define('PAGE_LOCK',				1);
define('PAGE_UNLOCK',			1);
define('PAGE_DELETE',			1);
define('SECTION_PUBLISHED', 	1);

//explain rows
define('EXPLAIN_ROWS',          200000);
define('EXPLAIN_PUBLISHED',     240);

define('THIRD_PARTY_DOMAIN', 'http://xinhuacredit.com.cn/'); //思路征信一键登陆域名
define('THIRD_PARTY_AUTHKEY', 'abcdefghijklmnop'); //思路征信一键登陆加密秘钥

define('THIRD_SITE_AUTHKEY', '$news%cn');

