StfalconPortfolioBundle
=============

The StfalconPortfolioBundle adds some basic support for your portfolio. You can create categories and projects
This bundle requires configured SonataAdminBundle with ORM support for administration, VichUploaderBundle
for images in projects, and StofDoctrineExtensionsBundle for timestamps.

Studio stfalcon.com use this bundle for portfolio http://stfalcon.com/portfolio/web-development

Features include:
- Projects and categories can be stored (with Doctrine ORM)
- Administrating with SonataAdminBundle
- Pagination of projects (with KnpPaginatorBundle)
- Breadcrumbs (with KnpMenuBundle)
- Upload images (with VichUploaderBundle) and resize it to thumbnails (with AvalancheImagineBundle)
- PHPUnit tests

Installation
------------

All the installation instructions are located in [documentation](https://github.com/stfalcon/PortfolioBundle/blob/master/Resources/doc/index.md).

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

About
-----

PageBundle is a [stfalcon](https://github.com/stfalcon) initiative.

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/stfalcon/PortfolioBundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.
