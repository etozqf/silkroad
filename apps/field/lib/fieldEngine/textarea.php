<?php
class fieldEngine_textarea extends fieldEngine
{
	public function _render($field)
	{
		$field['setting'] = unserialize($field['setting']);
		return $this->_genHtml($field);
	}

	public function _addView($pid)
	{
		$this->view->assign('pid', $pid);
		$this->view->display('field/textarea/add');
	}

	public function _editView($fid)
	{
		$field = $this->field->get($fid);
		$field['setting'] = unserialize($field['setting']);

		$this->view->assign($field);
		$this->view->assign('pid', $field['projectid']);
		$this->view->display('field/textarea/edit');
	}

	public function _genData($setting, $fid)
	{
		return '<textarea name="field['.$fid.']['.$setting['var'].']" style="width:'.$setting['width'].'px;height:'.$setting['height'].'px;">'.$setting['defaultvalue'].'</textarea>';
	}

	public function _genEditData($field, $value)
	{
		$fid = $field['fieldid'];
		$setting = unserialize($field['setting']);

		$textarea = '<textarea name="field['.$fid.']['.$setting['var'].']" style="width:'.$setting['width'].'px;height:'.$setting['height'].'px;">'.$value[$setting['var']].'</textarea>';
		return array('name' => $setting['fieldname'], 'field' => $textarea);
	}
}