<?php

class plugin_nlp extends object 
{
    private $article, $nlp;

    public function __construct($article)
    {
        $this->article = $article;
        $this->nlp = factory::nlp();
    }

    public function after_add()
    {
        $this->nlp->create(array(
            'modelid' => 1,
            'contentid' => $this->article->contentid,
            'title' => $this->article->data['title'],
            'content' => $this->article->data['content']
        ));
    }

    public function after_remove()
    {
        $this->nlp->remove(array(
            'modelid' => 1,
            'contentid' => $this->article->contentid
        ));
    }
}