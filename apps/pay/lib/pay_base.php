<?php
/**
 * ֧��ƽ̨������Ϣ��ʼ��
 *
 * @author liuyuan
 * @copyright 2010 (c) CmsTop
 * @date 2011/04/27
 * @version $Id$
 * @function set_config ��д�÷�����ʼ����Ӧ������Ϣ���ɲο�alipay
 * @todo URL ��֧���ɹ����������ת��ַ��Ŀǰ���������
 * return_url��ҳ����תͬ��֪ͨ��notify_url���������첽֪ͨ���Ǳ��ز���
 */

abstract class pay_base
{
	protected $config = array();
	abstract protected function set_config($forminfo);
	final public function init_config($platform)
	{
		$this->config = unserialize($platform['setting']);
	}
}