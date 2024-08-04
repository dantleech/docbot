<?php

namespace DTL\Docbot\Tests\Unit\Article;

use DTL\Docbot\Article\ArticleFinder;
use DTL\Docbot\Article\ArticleProviders;
use DTL\Docbot\Article\Provider\ClosureArticleProvider;
use DTL\Docbot\Tests\Unit\IntegrationTestCase;

final class ArticleFinderTest extends IntegrationTestCase
{
    protected function setUp(): void
    {
        $this->workspace()->clean();
    }

    public function testTapArticleSources(): void
    {
        $this->workspace()->createFile('docs/docs.php', <<<'PHP'
                <?php

                use DTL\Docbot\Article\Article;
                use DTL\Docbot\Article\Articles;
                use DTL\Docbot\Article\ArticleFinder;
                use DTL\Docbot\Article\Provider\ClosureArticleSource;

                return new ClosureArticleSource(function () {
                    return new Articles([
                        new Article('foo'),
                    ]);
                });
            PHP);

        $articles = (new ArticleFinder(
            [$this->workspace()->path('docs')],
            new ArticleProviders([
                new ClosureArticleProvider(),
            ]),
        ))->find();

        self::assertCount(1, $articles);
        self::assertEquals('foo', $articles->get('foo')->id);
    }
}
