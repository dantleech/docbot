DTL Docbot
==========

Docbot **generates** up-to-date and accurate documentation by **executing** the
documentation.

Status
------

I've hacked this together in 8 hours.

How it works
------------

Essentially:

- You provide an article containing a set of delarative blocks.
- The blocks are executed output is captured.
- The documentation is rendered to a format of your choice (e.g. markdown).

Features
--------

- Create and update files.
- Execute shell commands and capture the output.
- Apply assertions on the result of an executed block.
- Add your own extensions to provide custom blocks.
- Easily customise the output format to suit your project (e.g. Markdown,
  Hugo, RsT, HTML, whatever).
- Depend on other documents (pre-requisites).
- Interact with web pages and capture screenshots (_planned_).
- Lots of other stuff that I haven\'t done yet.

Usage
-----

Create the following file at `docs/example.php`:

```php
<?php

use DTL\Docbot\Article\Article;
use DTL\Docbot\Extension\Core\Block\CreateFileBlock;
use DTL\Docbot\Extension\Core\Block\SectionBlock;
use DTL\Docbot\Extension\Core\Block\ShellBlock;

return Article::create('hello_world', 'Hello World', [
    new SectionBlock('Running a shell command', [
        'By default the documentation operates in a clean directory,' .
        'let\'s create a file:',
        new CreateFileBlock('hello_world.txt', language: 'text', content: 'Hello World!'),
        'Now we can execute a shell command and show the contents of that file:',
        new ShellBlock('cat hello_world.txt'),
        'Note that the output from the shell command is shown.',
    ]),
]);
```
Now let's generate some docs!
```shell
$ ../bin/docbot execute docs
Docbot 0.x by Daniel Leech
Workspace: /home/daniel/www/dantleech/exedoc/workspace/workspace

[    core_article] Article "Hello World" with 1 steps
[    core_section] Section "Running a shell command" with 5 blocks
[       core_text] By default the documentation operates in a clean directory,let's create a file:
[core_create_file] Creating text file at "hello_world.txt"
[       core_text] Now we can execute a shell command and show the contents of that file:
[      core_shell] Cat hello_world.txt
[       core_text] Note that the output from the shell command is shown.

Rendering article:

Written 402 bytes to /home/daniel/www/dantleech/exedoc/workspace/docs/hello_world.md

```
We can view the output...
``````text
# docs/hello_world.md
Hello World
===========

Running a shell command
-----------------------

By default the documentation operates in a clean directory,let's create a file:
Create the following file at `hello_world.txt`:

```text
Hello World!
```
Now we can execute a shell command and show the contents of that file:
```shell
$ cat hello_world.txt
Hello World!
```
Note that the output from the shell command is shown.


``````

Inception
---------

Oh no! It's a trap! We are stuck code inception 😾:
``````php
# ../docs/README.php
<?php

use DTL\Docbot\Article\Article;
use DTL\Docbot\Extension\Core\Block\CreateFileBlock;
use DTL\Docbot\Extension\Core\Block\SectionBlock;
use DTL\Docbot\Extension\Core\Block\ShellBlock;
use DTL\Docbot\Extension\Core\Block\ShowFileBlock;

return Article::create('../README', 'DTL Docbot', [
    <<<TEXT
    Docbot **generates** up-to-date and accurate documentation by **executing** the
    documentation.
    TEXT,
    new SectionBlock('Status', [
        'I\'ve hacked this together in 8 hours.',
    ]),
    new SectionBlock('How it works', [
        <<<TEXT
        Essentially:

        - You provide an article containing a set of delarative blocks.
        - The blocks are executed output is captured.
        - The documentation is rendered to a format of your choice (e.g. markdown).
        TEXT,
    ]),
    new SectionBlock('Features', [
        <<<TEXT
        - Create and update files.
        - Execute shell commands and capture the output.
        - Apply assertions on the result of an executed block.
        - Add your own extensions to provide custom blocks.
        - Easily customise the output format to suit your project (e.g. Markdown,
          Hugo, RsT, HTML, whatever).
        - Depend on other documents (pre-requisites).
        - Interact with web pages and capture screenshots (_planned_).
        - Lots of other stuff that I haven\'t done yet.
        TEXT
    ]),
    new SectionBlock('Usage', [
        new CreateFileBlock(path: 'docs/example.php', language: 'php', content: <<<'PHP'
            <?php

            use DTL\Docbot\Article\Article;
            use DTL\Docbot\Extension\Core\Block\CreateFileBlock;
            use DTL\Docbot\Extension\Core\Block\SectionBlock;
            use DTL\Docbot\Extension\Core\Block\ShellBlock;

            return Article::create('hello_world', 'Hello World', [
                new SectionBlock('Running a shell command', [
                    'By default the documentation operates in a clean directory,' .
                    'let\'s create a file:',
                    new CreateFileBlock('hello_world.txt', language: 'text', content: 'Hello World!'),
                    'Now we can execute a shell command and show the contents of that file:',
                    new ShellBlock('cat hello_world.txt'),
                    'Note that the output from the shell command is shown.',
                ]),
            ]);
            PHP,
        ),
        'Now let\'s generate some docs!',
        new ShellBlock('../bin/docbot execute docs'),
        'We can view the output...',
        new ShowFileBlock('docs/hello_world.md', 'text'),
    ]),
    new SectionBlock('Inception', [
        'Oh no! It\'s a trap! We are stuck code inception 😾:',
        new ShowFileBlock('../docs/README.php', 'php'),
    ]),
]);

``````

