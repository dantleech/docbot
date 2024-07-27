<?php

namespace DTL\Docbot\Tests\Unit\Article;

use DTL\Docbot\Article\BlockDataBuffer;
use DTL\Docbot\Article\MainBlockExecutor;
use DTL\Docbot\Dispatcher\AggregateListenerProvider;
use DTL\Docbot\Dispatcher\ClosureListenerProvider;
use DTL\Docbot\Dispatcher\EventDispatcher;
use DTL\Docbot\Event\BlockPostExecute;
use DTL\Docbot\Event\BlockPreExecute;
use DTL\Docbot\Tests\Unit\Article\Example\ExampleBlock;
use DTL\Docbot\Tests\Unit\Article\Example\ExampleExecutor;
use Generator;
use PHPUnit\Framework\TestCase;

final class MainBlockExecutorTest extends TestCase
{
    public function testDisaptchesEventBeforeAndAfter(): void
    {
        $events = [];
        $dispatcher = new EventDispatcher(new AggregateListenerProvider([
            new ClosureListenerProvider(function (object $event) use (&$events): Generator {
                $events[] = $event;
                yield function (): void {};
            }),
        ]));
        $exampleBlock = new ExampleBlock();
        $buffer = new BlockDataBuffer();
        /** @phpstan-ignore-next-line */
        (new MainBlockExecutor([
            new ExampleExecutor(),
        ], $buffer, $dispatcher))->execute($exampleBlock);

        self::assertCount(2, $events);
        self::assertInstanceOf(BlockPreExecute::class, $events[0]);
        self::assertSame($exampleBlock, $events[0]->block);
        /** @phpstan-ignore-next-line */
        self::assertInstanceOf(BlockPostExecute::class, $events[1]);
        self::assertSame($exampleBlock, $events[1]->block);
        self::assertSame($buffer->fetch($exampleBlock), $events[1]->data);

    }
}
