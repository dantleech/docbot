<?php

namespace DTL\Docbot\Extension\Core\Renderer;

use DTL\Docbot\Article\Article;
use DTL\Docbot\Article\ArticleRenderer;
use DTL\Docbot\Article\RenderedArticle;
use Twig\Environment;

final class TwigRenderer implements ArticleRenderer
{
    public function __construct(
        private Environment $environment,
        private TwigBlockRenderer $renderer,
        private string $format,
    ) {
    }

    public function render(Article $article): RenderedArticle
    {
        $template = $this->environment->load(sprintf('%s.twig', $this->format));
        $content = $this->renderer->render($template->unwrap(), $article);

        return RenderedArticle::from(sprintf(
            '%s.%s',
            $article->id,
            $this->format
        ), $content);
    }
}
