<?php

namespace DTL\Docbot\Extension\ClassInfo\Provider;

use ReflectionClass;

final class ClassInfo
{
    /**
     * @param ReflectionClass<object> $reflection
     */
    public function __construct(public ReflectionClass $reflection)
    {
    }

    public function shortName(): string
    {
        return $this->reflection->getShortName();
    }

    public function docblockProse(): string
    {
        return DocblockProseParser::parse((string)$this->reflection->getDocComment());
    }

}
