<?php

class plugin_mobile_comment extends object
{
    protected $_model;

    /**
     * @var model_topic
     */
    protected $_topic;

    function __construct(&$model)
    {
        $this->_model = $model;
        $this->_topic = loader::model('topic', 'comment');
    }

    function after_get()
    {
        if (!is_array($this->_model->data)) return;

        if ($this->_model->data['referenceid']) {
            $refcontent = table('content', $this->_model->data['referenceid']);
            $this->_model->data['topicid'] = intval($refcontent['topicid']);
            $this->_model->data['allowcomment'] = intval($refcontent['allowcomment']);
        } else {
            $topicid = intval($this->_model->data['topicid']);
        }

        if (!$topicid) return;

        $comments = table('comment_topic', $topicid, 'comments');
        $this->_model->data['comments'] = $comments ? $comments : 0;
    }

    function before_add()
    {
        if (!$this->_model->data['referenceid']) {
            if (!isset($this->_model->data['allowcomment'])) {
                $setting = setting('mobile', 'comment');
                $this->_model->data['allowcomment'] = $setting['open'] ? 1 : 0;
            }
        }
    }

    function after_add()
    {
        $contentid = intval($this->_model->contentid);
        if (!$contentid) return;
        $content = table('mobile_content', $contentid);
        if (!$content) return;
        if ($content['status'] != 6) return;
        if (!$content['allowcomment']) return;

        if (!$this->_model->referenceid) {
            $this->_create_topic($content, 0);
        }
    }

    function after_edit()
    {
        $this->_update();
    }

    function after_pass()
    {
        $this->_update();
    }

    function after_publish()
    {
        $this->_update();
    }

    function after_unpublish()
    {
        $this->_update();
    }

    protected function _update()
    {
        $contentid = intval($this->_model->contentid);
        if (!$contentid) return;
        $content = table('mobile_content', $contentid);
        if (!$content) return;

        if (!$this->_model->referenceid) {
            if ($content['allowcomment'] && $content['status'] == 6) {
                if ($content['topicid']) {
                    $this->_topic->enable($content['topicid']);
                } else {
                    $this->_create_topic($content, 0);
                }
            } else {
                if ($content['topicid']) {
                    $this->_topic->disable($content['topicid']);
                }
            }
        }
    }

    protected function _create_topic($content, $disabled)
    {
        $topicid = $this->_topic->add(
            $content['title'],
            $content['url'],
            $content['description'],
            $content['thumb'],
            $disabled
        );
        if (!$topicid
            || !factory::db()->update(
                "UPDATE `#table_mobile_content` SET `topicid` = ? WHERE `contentid` = ?",
                array($topicid, intval($content['contentid']))
            )
        ) {
            throw new Exception('创建评论话题失败');
        }
    }
}