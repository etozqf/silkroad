<?php
return array(
    'filterword'=>array('before_add', 'before_edit'),
    'pagebreak'=>array('before_add', 'before_edit', 'after_get'),
    'qrcode' => array('html_write', 'after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete', 'after_copy', 'before_move', 'after_move'),
    'html'=>array('html_write', 'after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete', 'after_copy', 'before_move', 'after_move'),
    'search'=>array('after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_delete'),
    'contribution_log'=>array('after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_delete'),
	'version' => array('after_edit'),
    'reference'=>array('after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_move'),
    'content_version' => array('after_edit'),
    'itemtype' => array('after_add','after_edit'),
);