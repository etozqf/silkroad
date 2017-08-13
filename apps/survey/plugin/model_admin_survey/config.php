<?php
return array(
	'question'=>array('after_get', 'after_ls'),
    'qrcode'=>array('html_write', 'after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete', 'before_move', 'after_move'),
	'html'=>array('html_write', 'after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete', 'before_move', 'after_move'),
	'model_admin_survey_addon'=>array('after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete'),
    'reference'=>array('after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_move'),
    'content_version'=>array('after_edit'),
);