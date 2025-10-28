<?php

namespace silverorange\DevTest\Controller;

use Parsedown;
use silverorange\DevTest\Model;
use silverorange\DevTest\Context;
use silverorange\DevTest\Template;

class PostDetails extends Controller
{
    
    private ?Model\Post $post = null;

    protected \PDO $db;

    /**
     * @param \PDO  $db
     * @param array<string> $params
     */
    public function __construct(\PDO $db, $params)
    {
        $this->setDatabase($db)->setParams($params);
        $this->loadData();
    }

    public function getContext(): Context
    {
        $context = new Context();

        if ($this->post === null) {
            $context->title = 'Not Found';
            $context->content = "A post with id {$this->params[0]} was not found.";
        } else {
            $context->title = $this->post->title;
            $context->author = $this->post->author;
            $context->created_at = $this->post->created_at;
             // Convert Markdown to HTML
            $parsedown = new Parsedown();
            $context->content = $parsedown->text($this->post->body);
        }

        return $context;
    }

    public function getTemplate(): Template\Template
    {
        if ($this->post === null) {
            return new Template\NotFound();
        }

        return new Template\PostDetails();
    }

    public function getStatus(): string
    {
        if ($this->post === null) {
            return $this->getProtocol() . ' 404 Not Found';
        }

        return $this->getProtocol() . ' 200 OK';
    }

    protected function loadData(): void
    {
        $this->post = Model\Post::getPostDetails($this->db, $this->params[0]);
    }
}
