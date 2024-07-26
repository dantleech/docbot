<?php

use DTL\Docbot\Extension\Core\Block\CreateFileBlock;
use DTL\Docbot\Extension\Core\Block\ShellBlock;
use DTL\Docbot\Article\Article;

return Article::create('symfony_console', 'Creating a Console Application', [
    'Welcome to the Symfony Console tutorial.',
    'In this tutorial we will create a new Symfony Console application.',
    'First we need to create a composer JSON file so our source code can be loaded:',
    new CreateFileBlock('composer.json', language: 'json', content: <<<'JSON'
        {
            "name": "acme/example",
            "autoload": {
                "psr-4": {
                    "Acme\\": "src/"
                }
            }
        }
        JSON
    ),
    'Use composer to require the latest Symfony Console component:',
    new ShellBlock('composer require symfony/console'),
    'Create a console command:',
    new CreateFileBlock('src/Console/HelloCommand.php', language: 'php', content: <<<'PHP'
        <?php

        namespace Acme\Console;

        use Symfony\Component\Console\Attribute\AsCommand;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputArgument;
        use Symfony\Component\Console\Input\InputInterface;
        use Symfony\Component\Console\Output\OutputInterface;

        #[AsCommand(name: 'hello', description: 'Say hello')]
        final class HelloCommand extends Command
        {
            protected function execute(InputInterface $input, OutputInterface $output): int
            {
                $output->writeln('Hello');
                return 0;
            }
        }
        PHP
    ),
    'Now we need to run it, but how? We need to create an executable script:',
    new CreateFileBlock('bin/hello', language: 'php', content: <<<'PHP'
        #!/usr/bin/env php
        <?php

        use Symfony\Component\Console\Application;
        use Acme\Console\HelloCommand;

        require __DIR__ . '/../vendor/autoload.php';

        $application = new Application();
        $application->add(new HelloCommand());
        $application->run();
        PHP
    ),
    'We\'ll need to make the command executable:',
    new ShellBlock('chmod a+x bin/hello'),
    'Now we can run our command:',
    new ShellBlock('./bin/hello'),
]);
