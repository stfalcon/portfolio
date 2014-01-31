Getting Started With StfalconPortfolioBundle
==================================

Simple bundle for site portfolio

## Prerequisites

This version of the bundle requires:

1. Symfony >= 2.1
2. LiipFunctionalTestBundle for testing (optional)
3. DoctrineFixturesBundle for fixtures (optional)
4. SonataAdminBundle for administering
5. VichUploaderBundle for uploads
6. StofDoctrineExtensionsBundle for timestamps
7. KnpPaginatorBundle for automate pagination
8. AvalancheImagineBundle for easy image manipulation support for Symfony2

## Installation

Installation is a quick 4 step process:

1. Add StfalconPortfolioBundle in your composer.json
2. Enable the bundle
3. Import bundle routing
4. Configure bundle
5. Update your database schema

### Step 1: Add StfalconPortfolioBundle in your composer.json

```js
{
    "require": {
        "stfalcon/portfolio-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update stfalcon/portfolio-bundle
```

Composer will install the bundle to your project's `vendor/stfalcon` directory.

### Step 2: Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Stfalcon\Bundle\PortfolioBundle\StfalconPortfolioBundle(),

	// for use VichUploaderBundle
	new Vich\UploaderBundle\VichUploaderBundle(),

        // for use KnpMenuBundle
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),

        // for use KnpPaginatorBundle
        new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),

        // for use StofDoctrineExtensionsBundle
        new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

        // for use SonataAdminBundle
        new Sonata\BlockBundle\SonataBlockBundle(),
        new Sonata\AdminBundle\SonataAdminBundle(),
        new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
        new Sonata\jQueryBundle\SonatajQueryBundle(),
        
        new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
    );
}
```

### Step 3: Import StfalconPortfolioBundle routing

Now that you have installed and activated the bundle, all that is left to do is
to set the StfalconPortfolioBundle and SonataAdminBundle routings.

In YAML:

``` yaml
# app/config/routing.yml
_stfalcon_portfolio:
    resource: "@StfalconPortfolioBundle/Resources/config/routing.yml"
```
[See more info about routing in SonataAdminBundle](https://github.com/sonata-project/SonataAdminBundle/blob/master/Resources/doc/reference/getting_started.rst#step-1-define-sonataadminbundle-routes)



Add following lines to your config file:

In YAML:

``` yaml
# app/config/config.yml
# Sonata Configuration
sonata_block:
    default_contexts: [cms]
    blocks:
        sonata.admin.block.admin_list:
            contexts:   [admin]

# DoctrineExtensionsBundle
stof_doctrine_extensions:
    default_locale: en_US
    orm:
        default:
            timestampable: true

vich_uploader:
    db_driver: orm
    mappings:
        project_image:
            upload_destination: %kernel.root_dir%/../web/uploads/portfolio/projects
            namer: stfalcon_portfolio.namer.project
```

### Step 4: Update your database schema

Now that the bundle is configured, the last thing you need to do is update your
database schema because you have added a two new entities, the `Project` and the `Category`.

Run the following command.

``` bash
$ php app/console doctrine:schema:update --force
$ php app/console assets:install
```
At this point you can already access the admin dashboard by visiting the url: http://yoursite.local/admin/dashboard.
[Getting started with SonataAdminBundle](http://sonata-project.org/bundles/admin/2-0/doc/reference/getting_started.html)

Now that you have completed the installation and configuration of the StfalconPortfolioBundle!
