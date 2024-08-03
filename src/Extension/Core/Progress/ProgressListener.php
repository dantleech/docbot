<?php

namespace DTL\Docbot\Extension\Core\Progress;

use DTL\Docbot\Article\Article;
use DTL\Docbot\Event\BlockPreExecute;
use Psr\EventDispatcher\ListenerProviderInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ProgressListener implements ListenerProviderInterface
{
    public function __construct(private OutputInterface $output)
    {
    }

    /**
     * @return iterable<callable>
     */
    public function getListenersForEvent($event): iterable
    {
        if ($event instanceof BlockPreExecute) {
            yield function (BlockPreExecute $event): void {
                $this->output->writeln(sprintf(
                    '[<%s>%16s</>] %s',
                    $event->block instanceof Article ? 'info' : 'comment',
                    $event->block::name(),
                    ucfirst($this->oneline($event->block->describe())),
                ));
            };
        }
    }

    private function oneline(string $text): string
    {
        $text = str_replace("\n", ' ', $text);
        if (mb_strlen($text) > 100) {
            $text = mb_substr($text, 0, 99) . '…';
        }
        return $text;
    }
}
