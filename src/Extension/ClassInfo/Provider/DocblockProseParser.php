<?php

namespace DTL\Docbot\Extension\ClassInfo\Provider;

final class DocblockProseParser
{
    public static function parse(string $docblock): string
    {
        $lines = explode("\n", $docblock);
        $prose = [];

        foreach ($lines as $line) {
            if (preg_match('{\*/\s*$}', $line)) {
                continue;
            }

            if (preg_match('{@[a-z-]+}', $line)) {
                continue;
            }
            $prose[] = preg_replace('{\s*(/\*\*\s?|\*\s?)}', '', $line);
        }

        return trim(implode("\n", $prose));
    }
}
