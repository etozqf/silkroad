<?php

class plugin_log extends object 
{
	private $model;
	private $log;
	
	public function __construct($model)
	{
		$this->model = $model;
		$this->log = loader::model('admin/cron_log', 'system');
	}
	
	//删除任务后附带删除日志
	public function after_delete()
	{
		$where = $this->model->where;
		if(is_int($where))
		{
			$where = "cronid = $where";
		}
		else
		{
			!is_array($where) || $where = implode(',', $where);
			$where = "cronid IN ($where)";
		}
		$this->log->delete($where);
	}
}