<?php
define('RUN_CMSTOP', true);
require '../../../../cmstop.php';
$cmstop = new cmstop('admin');

/**
 * 推送消息格式
 * version：协议版本号；uint32_t，目前为1
 * dataNum：本次推送评论条数；uint32_t
 * checkToken：加密token，合作方进行验证，防止攻击
 * dataInfo：dataInfo为数组形式，每一个元素为一条评论。每一条评论的字段见下：
 * * opType: 操作类型:ADD(发表评论)/DEL(删除评论)；string
 * * msgType: 消息类型，普通微博消息（1）和私信（2）、对话（3）
 * * originUrl: 来源URL，唯一标识来源文档/视频等；string
 * * tweeterId: 评论微博ID，唯一标识该评论，如 http://t.qq.com/p/t/abcdef ；string
 * * rootTweeterId:根微博ID，即转发源文档/视频到腾讯微博时的微博ID；string
 * * userId:微博用户名；string
 * * userNick:微博用户昵称；string
 * * serAvatar:用户头像url（在该url后面加上 /20 /30 /40 /50 /100 返回相应大小的图片）；string
 * * pubTime: 评论时间；unix时间 time_t
 * * content:评论内容；string
 */
class MCI_Tencent_Weibo {
	protected $comment, $comment_topic, $comment_data, $verify_token, $raw_post, $log, $identity, $identity_id, $state;

	function __construct()
	{
		$this->identity = 'tencent_weibo';
		$this->comment = loader::model('admin/comment', 'comment');
		$this->comment_topic = loader::model('topic', 'comment');
		$this->log = new log();
		$this->log->set_options(array(
				'filename' => 'mci/'.date('Y-m').'.log'
			));

		foreach (table('comment_source') as $item)
		{
			if ($this->identity == $item['identity'])
			{
				$this->identity_id = $item['sourceid'];
				$this->state = $item['state'];
				foreach (json_decode($item['params'], 1) as $param)
				{
					if ($param['id'] == 'verify_token')
					{
						$this->verify_token = $param['value'];
					}
				}
				break;
			}
		}
	}

	public function main()
	{
		// 检测是否暂停推送接收
		$this->state or $this->response(true);
		
		$this->raw_post = file_get_contents('php://input');
		$data = json_decode($this->raw_post, true);

		if (count($data['dataInfo']) < 1)
			$this->response(false, 'data is null');

		if (!$this->_verify($data['dataInfo'][0]['tweeterId'], $data['checkToken']))
			$this->response(false, 'verifiy failed');

		$result = $this->handle($data['dataInfo'][0]);
		$message = $result ? 'success' : 'operation failed: ' . preg_replace("#\r\n#", '', $this->comment->error());
		// 无论入库是否成功都返回腾讯success
		$this->response(true, $message);
	}

	/*
	 * 记录日志
	 */
	protected function log($message)
	{
		$message = "\r\n" . $message . "\r\n" . $this->raw_post . "\r\n";
		$this->log->append($message, log::INFO);
	}

	/*
	 * 推送数据处理(插入comment)
	 */ 
	protected function handle($data)
	{
		if ($data['opType'] == 'ADD')
		{
			if ($data['msgType'] != 1)
			{
				$this->comment->error = 'not a normal tweet';
				return false;	// 只将普通微博转为评论
			}
			$url = $this->_format_url($data['originUrl']);
			$topicid = value($this->comment_topic->get_by_url($url, 'topicid'), 'topicid');
			if (!$topicid)
			{
				$this->comment->error = 'topicid is not exists';
				return false;
			}
			$data = $this->_parser($data);
			return $this->comment->add($topicid, $data);
		}
		$this->comment->error = 'do nothing in delete';
		return false;
	}

	/*
	 * 回包并中断
	 */
	protected function response($result, $message='')
	{
		empty($message) || $this->log($message);
		$ret = array('result' => $result ? 'success' : 'fault');
		exit(json_encode($ret));
	}

	/*
	 * 身份校验
	 */
	private function _verify($tweeter_id, $checkToken)
	{
		return md5($tweeter_id . $this->verify_token) == $checkToken;
	}

	/*
	 * 格式化URL
	 */
	private function _format_url($url)
	{
		// 去hash
		$pos = strpos($url, '#');
		if ($pos)
			$url = substr($url, 0, $pos);

		return $url;
	}

	/*
	 * 获得用户微博页面
	 */
	private function _get_status_url($userid)
	{
		return "http://t.qq.com/$userid";
	}

	/*
	 * 获得微博内容页面
	 */
	private function _get_weibo_url($weiboid)
	{
		return "http://t.qq.com/p/t/$weiboid";
	}

	/*
	 * 解析推送格式
	 *
	 * @param
	 * array(
	 *		tweeterId: 评论微博ID，唯一标识该评论，如 http://t.qq.com/p/t/abcdef ；string
	 *		userId:微博用户名；string
	 *		userNick:微博用户昵称；string
	 *		serAvatar:用户头像url（在该url后面加上 /20 /30 /40 /50 /100 返回相应大小的图片）；string
	 *		pubTime: 评论时间；unix时间 time_t
	 *		content:评论内容；string
	 * )
	 *
	 * @return
	 * array(
	 *		content
	 *		sourceid
	 *		sourceinfo
	 *		created
	 * )
	 */
	private function _parser($data)
	{
		$ret = array();
		$ret['content'] = $data['content'];
		$ret['created'] = $data['pubTime'];
		$ret['sourceid'] = $this->identity_id;
		$ret['sourceinfo'] = array(
			'status_url' => $this->_get_status_url($data['userId']),
			'weibo_url' => $this->_get_weibo_url($data['tweeterId']),
			'nick' => $data['userNick'],
			'avatar' => $data['serAvatar'].'/100'
		);
		$ret['sourceinfo'] = json_encode($ret['sourceinfo']);
		return $ret;
	}
}

$mci = new MCI_Tencent_Weibo();
$mci->main();