<?php

namespace Dantleech\Exedoc\Adapter;

use Dantleech\Exedoc\Model\Article;
use Dantleech\Exedoc\Model\Parser;
use Dantleech\Exedoc\Model\Block;
use Dantleech\Exedoc\Model\BlockFactory;
use Dantleech\Exedoc\Model\Block\SectionBlock;
use Dantleech\Exedoc\Model\Block\TextBlock;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\Inline\AbstractStringContainer;
use League\CommonMark\Node\Inline\Text as LeagueText;
use League\CommonMark\Node\Node;
use League\CommonMark\Parser\MarkdownParser;
use RuntimeException;

final class CommonMarkAdapter implements Parser
{
    public function __construct(private MarkdownParser $markdownParser, private BlockFactory $factory)
    {
    }

    public static function create(?ReflectionBlockFactory $factory = null): self
    {
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        return new self(new MarkdownParser($environment), $factory ?: new ReflectionBlockFactory());
    }

    public function parse(string $markdown): Article
    {
        $document = $this->markdownParser->parse($markdown);
        $blocks = [];
        foreach ($document->children() as $child) {
            $block = $this->traverse($child, $markdown);
            $blocks[] = $block;
        }
        return new Article($blocks);
    }

    private function traverse(Node $node, string &$markdown): Block
    {
        if ($node instanceof Heading) {
            $text = [];
            foreach ($node->children() as $child) {
                if (!$child instanceof AbstractStringContainer) {
                    continue;
                }
                $text[] = $child->getLiteral();
            }
            return new SectionBlock(implode('', $text), []);
        }

        if ($node instanceof LeagueText) {
            return new TextBlock($node->getLiteral());
        }

        if ($node instanceof FencedCode) {
            return $this->parseDirective($node, $markdown);
        }

        // try and handle anything else (e.g. tables, lists etc) as a text block
        if ($node instanceof AbstractBlock) {
            return new TextBlock($this->grabLineRange($markdown, $node));
        }

        throw new RuntimeException(sprintf(
            'Do not know how to: %s',
            $node::class
        ));
    }

    private function grabLineRange(string $markdown, AbstractBlock $block): string
    {
        $start = $block->getStartLine();
        $end = $block->getEndLine();

        if (!$start) {
            return '';
        }
        if (!$end) {
            return '';
        }
        $lines = explode("\n", $markdown);
        return trim(implode("\n", array_slice($lines, $start - 1, $end - $start + 1)));
    }

    private function parseDirective(FencedCode $node, string $markdown): Block
    {
        $args = $node->getInfoWords();
        if ($args <= 1) {
            return new TextBlock($this->grabLineRange($markdown, $node));
        }

        $content = $node->getLiteral();
        $language = array_shift($args);
        $directive = array_shift($args);
        $args['language'] = $language;
        $args['content'] = $content;

        return $this->factory->fromDirective($directive, $args);
    }
}
