<?php

class plugin_videolist extends object
{
	private $video, $videolist_data;
	
	public function __construct($video)
	{
		$this->video = $video;
        $this->videolist_data = loader::model('admin/videolist_data', 'video');
	}
	
	public function after_add()
	{
		if($this->video->contentid && $this->video->data['listid'])
        {
            $this->updata($this->video->contentid, $this->video->data['listid']);
        }
	}
	
	public function after_edit()
	{
        $old_listid = isset($_POST['old_listid']) ? intval($_POST['old_listid']) : 0;
        if($old_listid == $this->video->data['listid'])
        {
            return false;
        }
        if($this->video->contentid && $old_listid)
        {
            $this->videolist_data->delete($this->video->contentid, $old_listid, true);
        }
        if($this->video->contentid && $this->video->data['listid'])
        {
            $this->updata($this->video->contentid, $this->video->data['listid']);
        }
	}

    public function before_delete()
    {
        if($this->video->contentid)
        {
            $videoinfo = $this->video->get($this->video->contentid);
            if($videoinfo['listid'])
            {
                $this->videolist_data->delete($this->video->contentid, $videoinfo['listid'], true);
            }
        }
    }

    private function updata($contentid=0, $listid=0)
    {
        $this->videolist_data->add(array(
            'contentid'=>$contentid,
            'listid'=>$listid,
            'sort'=>0
        ), true);
    }
}