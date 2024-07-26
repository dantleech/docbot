DTL Docbot
==========

Docbot generates up-to-date and accurate documentation by **executing** the
documentation.

How it works
------------

Essentially:

- You provide an article containing a set of delarative blocks.
- The blocks are executed output is captured.
- The docuementation is rendered to a format of your choice (e.g. markdown).

Features
--------

- Create and update files.
- Execute shell commands and capture the output.
- Apply assertions on the result of an executed block.
- Add your own extensions to provide custom blocks.
- Easily customise the output format to suit your project (e.g. Markdown,
  Hugo, RsT, HTML, whatever).
- Interact with web pages and capture screenshots (_planned_).

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
Now let's run generate some docs!
```shell
$ ../bin/docbot execute docs
Docbot 0.x by Daniel Leech
Workspace: /home/daniel/www/dantleech/exedoc/workspace/workspace

Hello World
===========

Executing article:

=> Section "Running a shell command" with 5 blocks

Rendering article:

Written 401 bytes to /home/daniel/www/dantleech/exedoc/workspace/docs/hello_world.md
```

