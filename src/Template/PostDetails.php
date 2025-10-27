<?php

namespace silverorange\DevTest\Template;

use silverorange\DevTest\Context;

class PostDetails extends Layout
{
    protected function renderPage(Context $context): string
    {
        return <<<HTML
            <div class="post-container">
                <h1 class="post-title">{$context->title}</h1>
                <div class="post-meta">
                    by {$context->author} {$context->created_at}
                </div>
                <div class="post-body">
                    {$context->content}
                </div>

                <a href="/posts" class="back-link">&larr; Back to all posts</a>
            </div>
            HTML;
    }
}
