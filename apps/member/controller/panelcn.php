<?php

class controller_panelcn extends member_controller_abstract
{

	private $member;
	// public $userid;

	function __construct($app)
	{
		parent::__construct($app);
		
		$session = factory::session();
		$session->start();

		if (!$this->_userid) {
			$this->redirect(url('member/index/login_cn'));
			exit;
		}
		$this->member = loader::model('member_front');

	}

	function index() {
		$this->profile();

	}

	function profile() {
		
		
		if ($this->is_post()) {
			$data = $_POST;
			$data['birthday'] = implode('-', $_POST['birthday']); //生日
			$data = htmlspecialchars_deep($data);
			if (!$this->member->update_detail($data, "`userid`={$this->_userid}")) {
				$result = array('state' => false, 'error' => $this->member->error());
			} else {
				$result = array('state' => true, 'message' => 'Save success');
			}
			echo $this->json->encode($result);
		} else {
			
			$data = $this->member->getProfile($this->_userid);
			$this->template->assign('_userid',$this->_userid); 
			$this->template->assign('member', $data);
			$this->template->display('cn/member/panel/profile.html');
		}
	}

	function password() {
		
		if ($this->is_post()) {
			if (!$this->_validate_password_cn()) {
				$result = array('state' => false, 'error' => $this->error);
			} else {
				if ($this->member->password_cn($this->_userid, $_POST['password'], $_POST['last_password'])) {
					$result = array('state' => true, 'message' => 'Password modification success');
				} else {
					$result = array('state' => false, 'error' => $this->member->error());
				}
			}
			echo $this->json->encode($result);
		} else {
			$member = $this->member->get($userid);

			$this->template->assign('member', $member);
			$this->template->display('cn/member/panel/password.html');
		}
	}

	function email() {
		
		if ($this->is_post()) {
			if (!$this->_validate_email_cn()) {
				$result = array('state' => false, 'error' => $this->error);
			} else {
				if ($this->member->email_cn($this->_userid, $_POST['password'], $_POST['email'])) {
					$result = array('state' => true, 'message' => 'E-mail Successful modification');
				} else {
					$result = array('state' => false, 'error' => $this->member->error());
				}
			}
			echo $this->json->encode($result);
		} else {
			$this->template->display('cn/member/panel/email.html');
		}
	}

	function avatar() {
		
		if ($_FILES['photo']) {
			import('attachment.upload');
			list($photo_path, $rename) = $this->member->set_photo_path($this->_userid);
			$upload = new upload(UPLOAD_PATH . 'avatar/' . $photo_path, 'gif|jpg|jpeg', 2048);
			if (!$photo = $upload->execute('photo', $rename . '.jpg')) {
				$this->showmessage($upload->error());
			} else {
				//删除旧的缩略图
				$old_thumbs = glob(UPLOAD_PATH . 'avatar/' . $photo_path . '/*_' . $rename . '.jpg');
				if (!empty($old_thumbs)) {
					foreach ($old_thumbs as $v) {
						@unlink($v);
					}
				}
				$this->member->set_field('avatar', '1', $memberid);
			}
		}
		$this->template->assign('_userid',$this->_userid); 
		$this->template->display('cn/member/panel/avatar.html');
	}
	
	//私有验证
	private function _validate_password_cn() {
		if (empty($_POST['last_password'])) {
			$this->error = 'Must provide the original password';
			return false;
		}

		if ($_POST['password'] == '' || $_POST['password_check'] == '') {
			$this->error = 'The new password must be filled in';
			return false;
		}
		if ($_POST['password'] != $_POST['password_check']) {
			$this->error = 'New passwords are not consistent';
			return false;
		}
		return true;
	}

	private function _validate_email_cn() {
		if (empty($_POST['password'])) {
			$this->error = 'Provide login password';
			return false;
		}
		if ($_POST['email'] == '' || $_POST['email_check'] == '') {
			$this->error = 'New E-mail must be filled out';
			return false;
		}
		if ($_POST['email'] != $_POST['email_check']) {
			$this->error = 'The new E-mail is not consistent';
			return false;
		}
		return true;
	}

}