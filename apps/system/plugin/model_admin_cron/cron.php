<?php
class plugin_cron extends object 
{
	private $model;
	private $log;
	
	public function __construct($model)
	{
		$this->model = $model;
	}
	
	//输出过滤,时间戳转字符串格式等
	public function out_filter()
	{
		$data = & $this->model->data;
		if($data['name']) 
		{
			$m = 1;
			$data = array($data);
		}
		$timeKeys = array('time','starttime','endtime','nextrun','lastrun');//时间戳的转换
		$arrKeys = array('day','weekday','hour','minute');					//数组与字符串转换
		foreach ($data as &$item)
		{
			if(!$this->model->isStamp) 
			{
				foreach ($timeKeys as $k)
				{
					if($item[$k] == 0)
					{
						$item[$k] = '';
						continue;
					}
					$item[$k] < 1200000000 || $item[$k] = date('Y-m-d H:i:s', $item[$k]);
				}
			}
			
			if($this->model->isArr)
			{
				//非空字符串转数组
				foreach ($arrKeys as $k)
				{
					if(is_array($item[$k])) continue;
					if(strlen($item[$k]) > 0)
					{
						$item[$k] = explode(',', $item[$k]);
					}
					else
					{
						$item[$k] = array();
					}
				}
			}
			
			$item['disabled'] = $item['disabled'] ? '失效' : '正常';
			$item['modeStr'] = $this->model->modeMap[$item['mode']];
			$item['interval'] || $item['interval'] = '';
			$item['times'] || $item['times'] = '';
			$item['type'] = $item['type'] == 'system' ? '系统任务' : '自定义任务';
		}
		$m && $data = $data[0];
	}
	
	//输出时加入tips提示
	public function tip_add()
	{
		$data = & $this->model->data;
		if($data['name']) 
		{
			$m = 1;
			$data = array($data);
		}
		//加提示信息
		foreach ($data as &$item)
		{
			$item['tips'] = "<label>任务名称</label>：{$item['name']}<br/>
				<label>任务类型</label>：{$item['type']}<br/>
            	<label>App</label>：{$item['app']}<br/>
            	<label>Controller</label>：{$item['controller']}<br/>
            	<label>Action</label>：{$item['action']}<br/>
           ";
			if ($item['mode'] == 1)
			{
				$item['tips'] .= "<label>运行时间</label>：".$item['time']."<br/>\n";
			}
			else 
			{
				!$item['starttime'] || $item['tips'] .= "<label>任务起始时间</label>：".$item['starttime']."<br/>\n";
				!$item['endtime'] || $item['tips'] .= "<label>任务结束时间</label>：".$item['endtime']."<br/>\n";
				if ($item['mode'] == 2)
				{
					$item['tips'] .= "<label>运行间隔</label>：{$item['interval']}分钟<br/>\n";
					!$item['times'] || $item['tips'] .= "<label>最大次数</label>：{$item['times']}<br/>\n";
				}
				elseif ($item['mode'] == 3)
				{
					!$item['day'] || $item['tips'] .= "<label>每月运行日</label>：{$item['day']}<br/>";
					!$item['weekday'] || $item['tips'] .= "<label>每周运行日</label>：{$item['weekday']}<br/>\n";
					!$item['hour'] || $item['tips'] .= "<label>运行时段</label>：{$item['hour']}<br/>\n";
					!$item['minute'] || $item['tips'] .= "<label>运行分钟段</label>：{$item['minute']}\n";
				}
			}
			$item['tips'] .= "<div style='clear: both;'></div>";
		}
		$m && $data = $data[0];
	}
	
	//输入过滤
	public function input_filter()
	{
		$data = & $this->model->data;

		if(!$data['type']) $data['type'] = 'system';
		if($data['checkTime'] !== 0) $data['checkTime'] = 1;
		if($data['mode'] != 3) {
			unset($data['day']);
			unset($data['weekday']);
			unset($data['hour']);
			unset($data['minute']);
		}
		if($data['mode'] == 1 && !$data['time'])
		{
			$this->model->pluginError = '运行时间不能为空';
			return false;
		}

		if(!$data['checkTime']) return true;
		
		//转时间戳
		$keys = array('time','starttime','endtime','nextrun','lastrun');
		foreach ($keys as $k)
		{
			if($data[$k] > 1200000000) continue;
			$data[$k] && $data[$k] = strtotime($data[$k]);
		}

		//时间验证
		if($data['mode'] == 1 && $data['time'] < TIME)
		{
			$this->model->pluginError = '运行时间不能早于当前时间';
			return false;
		}
		
		if($data['mode'] != 1 && $data['endtime'])
		{
			if($data['endtime'] < TIME)
			{
				$this->model->pluginError = '结束时间不能早于当前时间';
				return false;
			}
			if($data['endtime'] <= $data['starttime'])
			{
				$this->model->pluginError = '结束时间必须晚于开始时间';
				return false;
			}
		}
	}
	
	//统一转字符串,准备入库
	public function before_save()
	{
		$data = & $this->model->data;
		$key = array('day'=>'日期','weekday'=>'星期','hour'=>'时段','minute'=>'分钟段');
		foreach ($key as $k => $v)
		{
			if(is_array($data[$k])) 
			{
				$data[$k] = implode(',', $data[$k]);
			}
			if ($data[$k] && !preg_match('#^[\d\,\ ]*$#', $data[$k])) {
				$this->model->pluginError = $v."必须是以逗号分隔的整数或数组";
				return false;
			}
		}
	}
}