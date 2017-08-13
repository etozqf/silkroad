<?php

class plugin_model_admin_eventlive_post_video extends object
{
    private $_model;

    /** @var model_admin_vms */
    private $_vms;

    public function __construct(&$model)
    {
        $this->_model = $model;
        $this->_vms = loader::model('admin/vms', 'video');
    }

    public function before_add()
    {
        $this->parse_video();
    }

    public function before_edit()
    {
        $this->parse_video();
    }

    protected function parse_video()
    {
        $data = value($this->_model->data, 'video');
        if (empty($data)) {
            return;
        }
        if (!is_array($data)) {
            $data = json_decode($data, true);
        }
        if (empty($data)) {
            return;
        }

        $video = value($data, 'video');
        if (empty($video)) {
            $this->_model->data['video'] = '';
            return;
        }

        if (!preg_match('#^\[ctvideo\]([a-z0-9]+)\[\/ctvideo\]$#', $video, $matches)) {
            $data['video'] = trim_uploadurl($data['video']);
            $this->_model->data['video'] = $data;
            return;
        }

        $info = $this->_vms->info_by_file($matches[1]);
        if ($info) {
            $info = json_decode($info, true);
        }
        if (!$info || empty($info['data'])) {
            $this->_model->data['video'] = '';
            return;
        }

        $data['vid'] = value($info['data'], 'vid');
        $data['thumb'] = value($info['data'], 'pic');
        $data['duration'] = value($info['data'], 'duration');
        $data['file'] = value(value($info['data'], 'file'), 'sd');

        $data = array_filter($data, 'strlen');
        $this->_model->data['video'] = $data;
    }
}