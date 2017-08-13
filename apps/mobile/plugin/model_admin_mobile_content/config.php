<?php
return array(
    'category' => array('after_add', 'after_edit', 'after_get'),
    'mobile_comment' => array(
        'after_get', 'before_add', 'after_add', 'after_edit',
        'after_pass', 'after_publish', 'after_unpublish'
    ),
    'related' => array('before_add', 'after_add', 'after_edit', 'after_get'),
    'reference' => array('after_get'),
    'slider' => array('after_search', 'after_edit'),
    'stat' => array(
        'after_get', 'after_add', 'after_delete'
    ),
    'log' => array(
        'after_add', 'after_edit', 'after_quickedit', 'after_remove', 'after_restore', 'after_delete',
        'after_pass', 'after_publish', 'after_unpublish', 'after_stick', 'after_unstick', 'after_bumpup',
    ),
    'addon' => array('after_add', 'after_edit', 'after_copy'),
    'sorttime' => array(
        'after_add', 'after_edit', 'after_quickedit', 'after_remove', 'after_restore', 'after_delete',
        'after_pass', 'after_publish', 'after_unpublish', 'after_stick', 'after_unstick', 'after_bumpup',
    ),
    'model_admin_mobile_content_qrcode' => array('after_get'),
);