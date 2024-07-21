<?php

declare(strict_types=1);

namespace Dantleech\Exedoc\Adapter;

use Dantleech\Exedoc\Model\Block;
use Dantleech\Exedoc\Model\BlockFactory;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;

final class ReflectionBlockFactory implements BlockFactory
{
    /**
     * @param array<string,class-string<Block>> $blockAliasMap
     */
    public function __construct(private array $blockAliasMap = [])
    {
    }

    /**
     * @template TClass of Block
     * @param class-string<TClass> $type
     * @return TClass
     */
    public function create(string $type, array $args): Block
    {
        $reflection = new ReflectionClass($type);
        if (!$reflection->hasMethod('__construct')) {
            throw new RuntimeException(sprintf(
                'Block %s has no constructor',
                $type
            ));
        }
        $construct = $reflection->getMethod('__construct');
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
                $resolved[] = $this->cast($param, $namedArgs[$name]);
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

            $resolved[] = $this->cast($param, $positionalArgs[$pos++]);
        }

        return new $type(...$resolved);
    }

    public function fromDirective(string $blockAlias, array $args): Block
    {
        if (!isset($this->blockAliasMap[$blockAlias])) {
            throw new RuntimeException(sprintf(
                'Unknown block directive "%s", known directives: "%s"',
                $blockAlias,
                implode('", "', array_keys($this->blockAliasMap))
            ));
        }

        return $this->create($this->blockAliasMap[$blockAlias], $args);
    }

    /**
     * @param string $scalar
     * @return string|int
     */
    private function cast(ReflectionParameter $param, mixed $scalar): mixed
    {
        $type = $param->getType();

        if (!$type instanceof ReflectionNamedType) {
            return $scalar;
        }

        return match($type->getName()) {
            'string' => (string)$scalar,
            'int' => (int)$scalar,
            default => $scalar,
        };
    }
}
