# Document Rendering

## Context

We need to be able to render documents in different formats and allow users to
customise the rendering to suite their particular project and any
customisations they may have made.

For example we supply defaults for Markdown and RsT, but users may be using
Hugo and may want to additionally augment their markdown documentation with
Hugo Markdown documents.

## Decision

Use a single Twig template with `{% block block_name %}` secctions named after
blocks.

This will allow single files to be used to provide fulfil rendering
requirements for any particular format and also provide a convenient way of
bootstrapping an entire "theme".

We can also easily provide fallback mechanisms and user-supplied templates to
accomodate custom blocks.

## Consequences

Using Twig instead of PHP may introduce some constraints for better or worse
and will add a layer of complexity.

Using Twig also destroys type safety, although arguably it's not critical here
and the loss is mitigated by tests.

