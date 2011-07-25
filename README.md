It's engine for portfolio of web-studio stfalcon.com
========================================

1) Download
--------------------------------

### Clone the git Repository

	$ git clone git://github.com/stfalcon/portfolio.git .

2) Installation
---------------

### a) Check your System Configuration

Before you begin, make sure that your local system is properly configured
for Symfony2. To do this, execute the following:

	$ ./app/check.php 

If you get any warnings or recommendations, fix these now before moving on.

### b) Initialize and update Submodules

	$ git submodule init
	$ git submodule update

### c) Install the Vendor Libraries

    $ ./bin/vendors install

Note that you **must** have git installed and be able to execute the `git`
command to execute this script.

### d) Change DBAL settings, create DB, update it and load fixtures

Change DBAL setting in `app/config/config.yml`, `app/config/config_dev.yml` or 
`app/config/config_test.yml`. After that execute the following:

    $ ./console doctrine:database:create
    $ ./console doctrine:migrations:migrate
    $ ./console doctrine:fixtures:load

You can set environment `test` for command if you add `--env=test` to it.

### e) Install Assets

    $ ./console assets:install web
