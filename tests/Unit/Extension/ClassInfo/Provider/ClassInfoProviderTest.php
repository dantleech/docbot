<?php

namespace DTL\Docbot\Tests\Unit\Extension\ClassInfo\Provider;

use DTL\Docbot\Article\Article;
use DTL\Docbot\Article\Block;
use DTL\Docbot\Extension\ClassInfo\Provider\ClassInfo;
use DTL\Docbot\Extension\ClassInfo\Provider\ClassInfoProvider;
use DTL\Docbot\Extension\ClassInfo\Provider\ClassInfoSource;
use DTL\Docbot\Extension\Core\Block\TextBlock;
use DTL\Docbot\Tests\Unit\IntegrationTestCase;

final class ClassInfoProviderTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        $this->workspace()->clean();
    }

    public function testProvideClasses(): void
    {
        $this->workspace()->createFile('src/SomeFoo.php', <<<'PHP'
            <?php

            namespace Bar;

            interface Cat {};

            /** Doc1 */
            class SomeFoo implements Cat {
            }
            PHP
        );
        $this->workspace()->createFile('src/SomeBar.php', <<<'PHP'
            <?php

            namespace Bar;

            /** Doc2 */
            class SomeBar implements Cat {
            }
            PHP
        );
        $this->workspace()->createFile('src/NotCat.php', <<<'PHP'
            <?php

            namespace Bar;

            class NotCat {
            }
            PHP
        );

        require $this->workspace()->path('src/SomeFoo.php');
        require $this->workspace()->path('src/SomeBar.php');
        require $this->workspace()->path('src/NotCat.php');

        $articles = (new ClassInfoProvider())->provide(new ClassInfoSource(
            path: $this->workspace()->path('src'),
            instanceof: \Bar\Cat::class,
            builder: function (ClassInfo $info): Article {
                return new Article($info->reflection->getShortName(), $info->reflection->getShortName(), [
                    new TextBlock((string)$info->reflection->getDocComment()),
                ]);
            },
        ));

        self::assertCount(2, $articles);
    }
}

