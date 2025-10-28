<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;

class PostIndex extends Layout
{
    protected function renderPage(Context $context): string
    {

        $post_data = '';
        foreach ($context->data as $postData) {
            /** @var array{post_id:string,title:string,author_name:string} $postData */
            $post = (object) $postData;
            
            $post_data .= <<<HTML
                        <a class="post-card" href="/posts/{$post->id}">
                            <div class="post-meta"> 
                            <span class="post-title">{$post->title}</span>
                            </div>
                            <div class="author-pill"> By {$post->author}</div>
                           
                        </a>
            
                        HTML;
        }

        return <<<HTML
                <div class="container">
                    <h1 class="page-title">Published Posts</h1>

                    <div class="posts-list">
                        {$post_data}
                    </div>
                </div>
            HTML;

        //return $post_data;
    }
}
