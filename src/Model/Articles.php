<?php

namespace Dantleech\Exedoc\Model;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<Article>
 */
final class Articles implements IteratorAggregate
{
    /**
     * @param Article[] $articles
     */
    public function __construct(private array $articles)
    {
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->articles);
    }

}
