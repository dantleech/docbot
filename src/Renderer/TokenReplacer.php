<?php

namespace DTL\Docbot\Renderer;

use DTL\Docbot\Renderer\Error\RenderException;
use ReflectionClass;

final class TokenReplacer
{
    public function replace(string $subject, object $context): string
    {
        $properties = $this->propertyNames($context);
        $res = preg_match_all('{%([^%]+)%}', $subject, $matches);
        if (0 === $res) {
            return $subject;
        }
        $tokens = $matches[0];
        $diff = array_diff($matches[1], $properties);

        if (count($diff) > 0) {
            throw new RenderException(sprintf(
                'String uses unknown token(s) "%s", valid token(s): "%s"',
                implode('", "', $diff),
                implode('", "', $properties)
            ));
        }

        return str_replace($matches[0], array_map(function (string $property) use ($context) {
            return $context->$property;
        }, $matches[1]), $subject);
    }

    /**
     * @return list<string>
     */
    private function propertyNames(object $context): array
    {
        $reflection = new ReflectionClass($context);
        $names = [];
        foreach ($reflection->getProperties() as $property) {
            if (!$property->isPublic()) {
                continue;
            }
            $names[] = $property->getName();
        }

        return $names;
    }
}
