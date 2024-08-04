<?php

namespace DTL\Docbot\Tests\Unit\Extension\ClassInfo\Provider;

use DTL\Docbot\Article\Article;
use DTL\Docbot\Extension\ClassInfo\Provider\ClassInfo;
use DTL\Docbot\Extension\ClassInfo\Provider\ClassInfoProvider;
use DTL\Docbot\Extension\ClassInfo\Provider\ClassInfoSource;
use DTL\Docbot\Extension\Core\Block\TextBlock;
use DTL\Docbot\Tests\Unit\Extension\ClassInfo\Provider\Example\Cat;
use DTL\Docbot\Tests\Unit\IntegrationTestCase;

final class ClassInfoProviderTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        $this->workspace()->clean();
    }

    public function testProvideClasses(): void
    {
        $articles = (new ClassInfoProvider())->provide(new ClassInfoSource(
            path: __DIR__ . '/Example',
            instanceof: Cat::class,
            builder: function (ClassInfo $info): Article {
                return new Article($info->reflection->getShortName(), $info->reflection->getShortName(), [
                    new TextBlock((string)$info->reflection->getDocComment()),
                ]);
            },
        ));

        self::assertCount(2, $articles);
    }
}
