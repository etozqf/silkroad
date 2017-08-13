<?php
return array(
    'special'=>array('after_add', 'after_edit', 'after_pass', 'after_publish', 'after_unpublish', 'after_restore', 'before_delete'),
    'special_topic' => array('after_add', 'after_edit'),
	'search' => array('after_add', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_delete'),
	'model_admin_special_addon' => array('after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_delete'),
    'reference'=>array('after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_move'),
    'content_version'=>array('after_edit'),
);
