<?php

namespace DTL\Docbot\Dispatcher;

use Closure;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * @internal
 */
final class ClosureListenerProvider implements ListenerProviderInterface
{
    /**
     * @param Closure(object):iterable<callable> $closure
     */
    public function __construct(private Closure $closure)
    {
    }

    /**
     * @return iterable<ListenerProviderInterface>
     */
    public function getListenersForEvent($event): iterable
    {
        yield from ($this->closure)($event);
    }
}
