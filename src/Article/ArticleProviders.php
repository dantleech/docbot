<?php

namespace DTL\Docbot\Article;

use RuntimeException;

final class ArticleProviders
{
    /**
     * @var array<class-string<ArticleSource>,ArticleProvider<ArticleSource>>
     */
    private array $providers = [];

    /**
     * @param list<ArticleProvider<covariant ArticleSource>> $providers
     */
    public function __construct(array $providers)
    {
        foreach ($providers as $provider) {
            $this->providers[$provider::for()] = $provider;
        }
    }

    /**
     * @return ArticleProvider<ArticleSource>
     */
    public function forSource(ArticleSource $source): ArticleProvider
    {
        if (!isset($this->providers[$source::class])) {
            throw new RuntimeException(sprintf(
                'No provider available for articles source of type: `%s`, supported sources: `%s`',
                $source::class, implode('`, `', array_keys($this->providers))
            ));
        }

        return $this->providers[$source::class];
    }
}
