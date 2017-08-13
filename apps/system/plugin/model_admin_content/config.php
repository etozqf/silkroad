<?php
return array(
    'log'=>array('after_add', 'after_edit', 'after_delete', 'after_remove', 'after_restore', 'after_approve', 'after_pass', 'after_reject', 'after_copy', 'after_reference', 'after_move', 'after_islock', 'after_lock', 'after_unlock', 'after_publish', 'after_unpublish'),
    'source'=>array('before_add', 'before_edit', 'after_get', 'before_ls', 'after_ls'),
    'tags'=>array('before_add', 'before_edit', 'after_add', 'after_edit', 'after_get', 'after_copy', 'after_reference', 'after_pass', 'after_unpublish', 'after_remove', 'after_publish', 'after_restore'),
    'property'=>array('after_get', 'after_add', 'after_edit'),
    'workflow'=>array('before_add', 'before_approve', 'before_pass', 'after_pass', 'before_reject', 'after_reject'),
    'related'=>array('after_add', 'after_edit', 'after_get'),
    'section'=>array('after_remove', 'after_unpublish'),
    'pv'=>array('after_ls', 'after_get'),
    'place'=>array('after_get', 'after_publish', 'after_delete', 'after_remove'),
    'updatecate'=>array('after_add', 'before_edit', 'after_edit', 'after_publish', 'after_unpublish', 'after_remove', 'after_resotre', 'after_pass', 'after_copy', 'before_move', 'after_move', 'after_reference'),
	'comment'=>array('after_add', 'after_edit'),
	'field' => array('after_add', 'after_edit', 'after_delete'),
    'model_admin_content_addon' => array('after_add', 'after_edit', 'after_copy', 'after_pass', 'after_publish'),
    'mobile' => array('after_edit', 'after_delete', 'after_remove', 'after_restore', 'after_publish', 'after_unpublish'),
    'qrcodepic' => array('after_get'),
);