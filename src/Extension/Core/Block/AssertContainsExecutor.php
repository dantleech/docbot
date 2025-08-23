<?php

namespace DTL\Docbot\Extension\Core\Block;

use DTL\Docbot\Article\Articles;
use DTL\Docbot\Article\Block;
use DTL\Docbot\Article\BlockData;
use DTL\Docbot\Article\BlockExecutor;
use DTL\Docbot\Article\Error\AssertionFailed;
use DTL\Docbot\Article\MainBlockExecutor;
use ReflectionClass;
use RuntimeException;

/**
 * @implements BlockExecutor<AssertContainsBlock>
 */
final class AssertContainsExecutor implements BlockExecutor
{
    public static function for(): string
    {
        return AssertContainsBlock::class;
    }

    public function execute(MainBlockExecutor $executor, Articles $articles, Block $block): BlockData
    {
        $data = $executor->execute($articles, $block->block);

        if (!property_exists($data, $block->path)) {
            throw new RuntimeException(sprintf(
                'Property `%s` does not exist on data class `%s` - known properties: %s',
                $block->path,
                $data::class,
                implode(', ', $this->properties($data::class)),
            ));
        }

        $haystack = $data->{$block->path};

        foreach ($block->needle as $needle) {
            if (str_contains($haystack, $needle)) {
                continue;
            }
            throw new AssertionFailed(sprintf(
                'expected %s#%s to contain `%s` but it contained `%s`',
                $data::class,
                $block->path,
                $needle,
                trim($haystack),
            ));
        }

        return $data;
    }

    /**
     * @param class-string $string
     * @return string[]
     */
    private function properties(string $string): array
    {
        $reflection = new ReflectionClass($string);
        $names = [];
        foreach ($reflection->getProperties() as $property) {
            if (!$property->isPublic()) {
                continue;
            }
            $names[] = $property->getName();
        }
        return $names;
    }
}
