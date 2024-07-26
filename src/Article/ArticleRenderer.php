<?php

namespace DTL\Docbot\Article;

interface ArticleRenderer
{
    public function render(Article $article): RenderedArticle;
}
