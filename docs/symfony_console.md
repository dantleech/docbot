Creating a Console Application
==============================

Welcome to the Symfony Console tutorial.

In this tutorial we will create a new Symfony Console application.

First we need to create a composer JSON file so our source code can be loaded:

Create the following file at `composer.json`:

```json
{
    "name": "acme/example",
    "autoload": {
        "psr-4": {
            "Acme\\": "src/"
        }
    }
}
```

Use composer to require the latest Symfony Console component:

```shell
$ composer require symfony/console
./composer.json has been updated
Running composer update symfony/console
Loading composer repositories with package information
Updating dependencies
Lock file operations: 9 installs, 0 updates, 0 removals
  - Locking psr/container (2.0.2)
  - Locking symfony/console (v7.1.3)
  - Locking symfony/deprecation-contracts (v3.5.0)
  - Locking symfony/polyfill-ctype (v1.30.0)
  - Locking symfony/polyfill-intl-grapheme (v1.30.0)
  - Locking symfony/polyfill-intl-normalizer (v1.30.0)
  - Locking symfony/polyfill-mbstring (v1.30.0)
  - Locking symfony/service-contracts (v3.5.0)
  - Locking symfony/string (v7.1.3)
Writing lock file
Installing dependencies from lock file (including require-dev)
Package operations: 9 installs, 0 updates, 0 removals
    0 [>---------------------------]    0 [->--------------------------]
  - Installing symfony/polyfill-mbstring (v1.30.0): Extracting archive
  - Installing symfony/polyfill-intl-normalizer (v1.30.0): Extracting archive
  - Installing symfony/polyfill-intl-grapheme (v1.30.0): Extracting archive
  - Installing symfony/polyfill-ctype (v1.30.0): Extracting archive
  - Installing symfony/string (v7.1.3): Extracting archive
  - Installing symfony/deprecation-contracts (v3.5.0): Extracting archive
  - Installing psr/container (2.0.2): Extracting archive
  - Installing symfony/service-contracts (v3.5.0): Extracting archive
  - Installing symfony/console (v7.1.3): Extracting archive
 0/9 [>---------------------------]   0%
 9/9 [============================] 100%
Generating autoload files
8 packages you are using are looking for funding.
Use the `composer fund` command to find out more!
No security vulnerability advisories found.
Using version ^7.1 for symfony/console

```

Create the following file at `src/Console/HelloCommand.php`:

```php
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
```

Now we need to run it, but how? We need to create an executable script:

Create the following file at `bin/hello`:

```php
#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;
use Acme\Console\HelloCommand;

require __DIR__ . '/../vendor/autoload.php';

$application = new Application();
$application->add(new HelloCommand());
$application->run();
```

We'll need to make the command executable:

```shell
$ chmod a+x bin/hello

```

Now we can run our command:

```shell
$ ./bin/hello hello
Hello

```

Great!

