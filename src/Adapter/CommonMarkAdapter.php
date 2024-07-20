<?php

namespace Dantleech\Exedoc\Adapter;

use Dantleech\Exedoc\Block\CreateFileBlock;
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
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Inline\Text as LeagueText;
use League\CommonMark\Node\Node;
use League\CommonMark\Parser\MarkdownParser;
use RuntimeException;

final class CommonMarkAdapter
{
    public function __construct(private MarkdownParser $markdownParser, private BlockFactory $factory)
    {
    }

    public static function create(): self
    {
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        return new self(new MarkdownParser($environment), new ReflectionBlockFactory());
    }

    public function parse(string $markdown): Block
    {
        $ast = $this->markdownParser->parse($markdown);

        return $this->traverse($ast, $markdown);
    }

    private function traverse(Node $node, string &$markdown): Block
    {
        if ($node instanceof Document) {
            $blocks = [];
            foreach ($node->children() as $child) {
                $blocks[] = $this->traverse($child, $markdown);
            }
            return new SectionBlock('Root', $blocks);
        }

        if ($node instanceof Heading) {
            $blocks = [];
            foreach ($node->children() as $child) {
                $blocks[] = $this->traverse($child, $markdown);
            }
            return new SectionBlock('Root', $blocks);
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
        return implode("\n", array_slice($lines, $start - 1, $end - $start + 1));
    }

    private function parseDirective(FencedCode $node, string $markdown): Block
    {
        $args = $node->getInfoWords();
        if ($args <= 1) {
            return new TextBlock($this->grabLineRange($markdown, $node));
        }

        $content = $node->getLiteral();
        $language = array_shift($args);
        $task = array_shift($args);
        $args['language'] = $language;
        $args['content'] = $content;

        return match($task) {
            'createFile' => $this->factory->create(CreateFileBlock::class, $args),
            default => throw new RuntimeException(sprintf(
                'Do not know how to task: %s',
                $task
            )),
        };
    }
}
