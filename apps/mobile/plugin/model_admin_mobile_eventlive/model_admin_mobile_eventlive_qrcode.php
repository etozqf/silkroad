<?php

class plugin_model_admin_mobile_eventlive_qrcode extends object
{
    private $_model, $_qrcode;

    public function __construct(&$model)
    {
        $this->_model = $model;
        $this->_qrcode = loader::model('admin/qrcode', 'system');
    }

    public function after_add()
    {
        $this->create_qrcode();
    }

    public function after_edit()
    {
        $this->create_qrcode();
    }

    private function create_qrcode()
    {
        $contentid = $this->_model->data['contentid'];
        $content = table('mobile_content', $contentid);
        if (!empty($content['qrcode'])) {
            return;
        }

        // 生成
        $short = $this->_qrcode->short($content['url']);
        $params = array(
            'str' => $content['url'],
            'size' => 7,
            'filename' =>'mobile/' . $short . '-' . 7
        );
        $qrcode = $this->_qrcode->generate($params);
        if (!$qrcode) {
            return;
        }

        // 入库
        $qrcode = preg_replace('#^'.preg_quote(UPLOAD_URL).'#', '', $qrcode);
        factory::db()->update("UPDATE #table_mobile_content SET qrcode = ? WHERE contentid = ?", array(
            $qrcode, $contentid
        ));
    }
}