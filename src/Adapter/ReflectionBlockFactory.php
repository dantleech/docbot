<?php

namespace Dantleech\Exedoc\Adapter;

use Dantleech\Exedoc\Model\Block;
use Dantleech\Exedoc\Model\BlockFactory;
use ReflectionClass;
use RuntimeException;

final class ReflectionBlockFactory implements BlockFactory
{
    public function create(string $type, array $args): Block
    {
        $reflection = new ReflectionClass($type);
        $construct = $reflection->getMethod('__construct');
        if (!$construct) {
            throw new RuntimeException(sprintf(
                'Block %s has no constructor',
                $type
            ));
        }
        $params = $construct->getParameters();

        $namedArgs = [];
        $positionalArgs = [];
        foreach ($args as $name => $arg) {
            if (is_string($name)) {
                $namedArgs[$name] = $arg;
                continue;
            }
            $positionalArgs[] = $arg;
        }

        $resolved = [];
        $pos = 0;
        foreach ($params as $param) {
            $name = $param->getName();
            if (isset($namedArgs[$name])) {
                $resolved[] = $namedArgs[$name];
                continue;
            }
            if (!isset($positionalArgs[$pos]) && false === $param->isOptional()) {
                throw new RuntimeException(sprintf(
                    'Parameter `%s` is required for action: %s',
                    $param->getName(),
                    $type
                ));
            }

            if (!isset($positionalArgs[$pos])) {
                continue;
            }

            $resolved[] = $positionalArgs[$pos++];
        }

        return new $type(...$resolved);
    }
}
