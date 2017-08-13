<?php
$member_plugin = array();

$member_plugin['otherlogin'] = array('del_bind');

if(UCENTER == 'ucenter')
{
	$member_plugin['ucenter'] = array('after_password', 'after_force_password', 'before_new_add',
		'before_new_edit','before_delete','before_login','after_login', 'after_logout');
}

return $member_plugin;