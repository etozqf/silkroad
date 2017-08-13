<?php
return array(
    'qrcode'=>array('html_write', 'after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete', 'before_move', 'after_move'),
    'html'=>array('html_write', 'after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete', 'before_move', 'after_move'),
    'videolist'=>array('after_add', 'after_edit', 'before_delete'),
    'search'=>array('after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_delete'),
    'model_admin_video_addon'=>array('after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_delete'),
    'reference'=>array('after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_move'),
    'parser' => array('before_add', 'before_edit'),
    'content_version'=>array('after_edit'),
);