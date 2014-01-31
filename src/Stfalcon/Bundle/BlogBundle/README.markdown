StfalconBlogBundle
=============

The StfalconBlogBundle adds some basic support for blogs and tags.
This bundle requires configured SonataAdminBundle with ORM support for administration
and StofDoctrineExtensionsBundle for timestamps

Studio stfalcon.com use this bundle for blog http://stfalcon.com/blog

Features include:
- Posts & Tags can be stored via Doctrine ORM
- Pagination of posts and tags
- Administrating via SonataAdminBundle
- Syntax highlighting with geshi. Wrap code blocks with  ```<pre lang="LANGUAGE" line="1"> ... </pre>``` where "LANGUAGE" is a [Geshi](http://qbnz.com/highlighter/) supported language syntax.
- Cut is available with <!--more--> tag
- PHPUnit tests
- Disqus comments widget


Installation
------------

All the installation instructions are located in [documentation](https://github.com/stfalcon/BlogBundle/blob/master/Resources/doc/index.md).

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

About
-----

StfalconBlogBundle is a [stfalcon](https://github.com/stfalcon) initiative.

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/stfalcon/BlogBundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.
