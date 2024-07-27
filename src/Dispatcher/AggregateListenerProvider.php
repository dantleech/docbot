<?php

namespace DTL\Docbot\Dispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @internal
 */
final class AggregateListenerProvider implements ListenerProviderInterface
{
    /**
     * @param list<ListenerProviderInterface> $providers
     */
    public function __construct(private array $providers)
    {
    }

    /**
     * @return iterable<ListenerProviderInterface>
     */
    public function getListenersForEvent($event): iterable
    {
        foreach ($this->providers as $provider) {
            foreach ($provider->getListenersForEvent($event) as $provider) {
                yield $provider;
            }
        }
    }
}
