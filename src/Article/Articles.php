<?php

namespace DTL\Docbot\Article;

use ArrayIterator;
use DTL\Docbot\Article\Block\DependsBlock;
use IteratorAggregate;
use RuntimeException;
use SplObjectStorage;
use Traversable;

/**
 * @implements IteratorAggregate<Article>
 */
final class Articles implements IteratorAggregate
{
    private const TOPSORT_MARKER_PERM = 'perm';
    private const TOPSORT_MARKER_TEMP = 'temp';

    /**
     * @var Article[]
     */
    private array $articles = [];

    /**
     * @param Article[] $articles
     */
    public function __construct(array $articles = [])
    {
        foreach ($articles as $article) {
            $this->articles[$article->id] = $article;
        }
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->articles);
    }

    /**
     * Returns a new collection of articles sorted by their dependency
     * order. Uses the depth-first search topological sorting:
     * https://en.wikipedia.org/wiki/Topological_sorting
     */
    public function indexAndSort(): self
    {
        /** @var SplObjectStorage<Article, string> */
        $markers = new SplObjectStorage();

        $sorted = [];
        do {
            // build a list of nodes without a permanent mark
            $unmarked = [];
            foreach ($this->articles as $article) {
                if (
                    $markers->offsetExists($article) &&
                    $markers->offsetGet($article) === self::TOPSORT_MARKER_PERM) {
                    continue;
                }

                $unmarked[] = $article;
            }

            // get first unmarked node
            $next = reset($unmarked);
            if ($next === false) {
                // no unmarked nodes? we're done
                break;
            }

            // perform a depth first search
            $this->topologicalVisitor($next, $markers, $sorted);
        } while (true);

        foreach ($sorted as $index => $article) {
            $article->index = $index;
        }

        return new self($sorted);
    }

    /**
     * @return string[]
     */
    public function ids(): array
    {
        return array_keys($this->articles);
    }

    public function get(string $id): Article
    {
        if (!isset($this->articles[$id])) {
            throw new RuntimeException(sprintf(
                'Unkown article: "%s", known articles: "%s"',
                $id,
                implode('", "', array_keys($this->articles)),
            ));
        }

        return $this->articles[$id];
    }

    /**
     * @return list<Article>
     */
    public function toArray(): array
    {
        return array_values($this->articles);
    }

    /**
     * @param SplObjectStorage<Article, string> $markers
     * @param list<Article> $sorted
     */
    private function topologicalVisitor(Article $article, SplObjectStorage $markers, array &$sorted): void
    {
        // check to see if this node has been marked
        if ($markers->offsetExists($article)) {
            $marker = $markers->offsetGet($article);

            // if the marker is permanent, then we already traversed this node
            // and we're done with it.
            if ($marker === self::TOPSORT_MARKER_PERM) {
                return;
            }

            // if temporary then we've got a cycle
            if ($marker === self::TOPSORT_MARKER_TEMP) {
                throw new RuntimeException(sprintf(
                    'Dependency cycle found in articles while visiting article "%s"',
                    $article->id,
                ));
            }
        }

        // mark it as temporary, if we see it again we've got a cycle
        $markers->offsetSet($article, self::TOPSORT_MARKER_TEMP);

        // traverse the dependencies
        foreach ($this->dependenciesFor($article) as $dep) {
            $this->topologicalVisitor($dep, $markers, $sorted);
        }

        // replace the temporary flag with the permanent one.
        // we've traversed the node and it's "done"
        $markers->offsetSet($article, self::TOPSORT_MARKER_PERM);
        $sorted[] = $article;
    }

    /**
     * @return list<Article>
     */
    private function dependenciesFor(Article $article): array
    {
        $deps = [];
        foreach ($article->blocks as $block) {
            if ($block instanceof DependsBlock) {
                $deps[] = $this->get($block->id);
            }
        }

        return $deps;
    }
}
