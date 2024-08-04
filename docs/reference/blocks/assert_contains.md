Block: `assert_contains`
========================

Class: `DTL\Docbot\Extension\Core\Block\AssertContainsBlock`
Parameters:
- `block`: `DTL\Docbot\Article\Block`
- `path`: `string`
- `needle`: `string`

Assert that the value at the given path on the given blocks _output_ contains a string.

This can be used to assert, for example, that a shell output's stdout contains a specific string.

