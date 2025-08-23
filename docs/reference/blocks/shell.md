Block: `shell`
==============

Class: `DTL\Docbot\Extension\Core\Block\ShellBlock`
Parameters:
- `content`: `string`
- `assertExitCode`: `?int`
- `cwd`: `??string`
- `env`: `??array`
- `stdout`: `?bool`
- `stderr`: `?bool`

This block will execute a command on the shell
within the workspace directory.

The output contains the stdout and stderr and can
be validated when this block is nested within an
assertion block.

