<?php

namespace Dantleech\Exedoc\Model;

interface Parser
{
    public function parse(string $markdown): Article;
}
