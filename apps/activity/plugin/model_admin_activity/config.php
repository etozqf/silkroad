<?php
return array(
    'qrcode'=>array('html_write', 'after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete', 'before_move', 'after_move'),
    'html'=>array('html_write', 'after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete', 'before_move', 'after_move'),
    'search'=>array('after_add', 'after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_delete'),
    'fields'=>array('html_write','before_add','before_edit','after_get'),
    'model_admin_activity_addon'=>array('after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'before_delete'),
    'reference'=>array('after_edit', 'after_publish', 'after_unpublish', 'after_restore', 'after_pass', 'after_remove', 'after_move'),
    'content_version'=>array('after_edit'),
);