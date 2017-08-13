<?php
return array(
	'model_admin_vote_option'   => array('before_add', 'after_add', 'before_edit', 'after_edit', 'after_get'),
    'qrcode'                    => array('html_write', 'after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete', 'before_move', 'after_move'),
	'model_admin_vote_html'     => array('html_write', 'after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete', 'before_move', 'after_move'),
	'model_admin_vote_addon'    => array('after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete'),
    'reference'                 => array('after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_move'),
    'content_version'=>array('after_edit'),
);