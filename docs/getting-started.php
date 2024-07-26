<?php


use Dantleech\Exedoc\Extension\Core\Block\CreateFileBlock;
use Dantleech\Exedoc\Extension\Core\Block\ShellBlock;
use Dantleech\Exedoc\Extension\Core\Block\TextBlock;
use Dantleech\Exedoc\Model\Article;

return Article::create('Symfony Console', [
    new TextBlock(
        <<<'EOT'
        Welcome to the Symfony Console tutorial.

        In this tutorial we will craete a new Symfony Console application.
        EOT
    ),
    new ShellBlock('composer require symfony/console'),
    new CreateFileBlock('bin/getting-started.php', language: 'php', <<<PHP
    <?php
    use Dantleech\Exedoc\Extension\Core\Block\CreateFileBlock;
    use Dantleech\Exedoc\Extension\Core\Block\ShellBlock;
    use Dantleech\Exedoc\Extension\Core\Block\TextBlock;
    use Dantleech\Exedoc\Model\Article;

    return Article::create('Getting Started', [
    
    PHP),
]);
