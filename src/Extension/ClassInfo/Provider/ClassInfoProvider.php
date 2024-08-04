<?php

namespace DTL\Docbot\Extension\ClassInfo\Provider;

use DTL\Docbot\Article\ArticleProvider;
use DTL\Docbot\Article\ArticleSource;
use DTL\Docbot\Article\Articles;
use DTL\Docbot\Tests\Unit\Extension\ClassInfo\Util\ClassNameParser;
use ReflectionClass;
use RuntimeException;
use Symfony\Component\Finder\Finder;

/**
 * @implements ArticleProvider<ClassInfoSource>
 */
final class ClassInfoProvider implements ArticleProvider
{
    public static function for(): string
    {
        return ClassInfoSource::class;
    }

    public function provide(ArticleSource $source): Articles
    {
        $finder = new Finder();
        $finder->in($source->path);
        $finder->name('*.php');
        $articles = [];

        foreach ($finder as $file) {
            $className = ClassNameParser::parse($file->getPathname());
            if (null === $className) {
                continue;
            }

            if (!class_exists($className)) {
                throw new RuntimeException(sprintf(
                    'Found class named `%s` in `%s` but it cannot be autoloaded',
                    $className,
                    $file->getPathname()
                ));
            }

            $reflection = new ReflectionClass($className);

            if ($source->instanceof) {
                if (!$reflection->implementsInterface($source->instanceof) || !$reflection->isSubclassOf($source->instanceof)) {
                    continue;
                }
            }

            $classInfo = new ClassInfo($reflection);
            $article = ($source->builder)($classInfo);
            if (null === $article) {
                continue;
            }
            $articles[] = $article;

        }

        return new Articles($articles);
    }
}
