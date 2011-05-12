#!/bin/sh

DIR=`php -r "echo dirname(dirname(realpath('$0')));"`
VENDOR="$DIR/vendor"
BUNDLES=$VENDOR/bundles

# initialization
if [ "$1" = "--reinstall" -o "$2" = "--reinstall" ]; then
    rm -rf $VENDOR
fi

# just the latest revision
CLONE_OPTIONS=''
if [ "$1" = "--min" -o "$2" = "--min" ]; then
    CLONE_OPTIONS='--depth 1'
fi

mkdir -p "$VENDOR" && cd "$VENDOR"

##
# @param destination directory (e.g. "doctrine")
# @param URL of the git remote (e.g. http://github.com/doctrine/doctrine2.git)
# @param revision to point the head (e.g. origin/HEAD)
#
install_git()
{
    INSTALL_DIR=$1
    SOURCE_URL=$2
    REV=$3

    echo "======================================================================"
    echo "> Installing/Updating " $INSTALL_DIR

    if [ -z $REV ]; then
        REV=origin/HEAD
    fi

    if [ ! -d $INSTALL_DIR ]; then
        git clone $CLONE_OPTIONS $SOURCE_URL $INSTALL_DIR
    fi

    cd $INSTALL_DIR
    git fetch origin
    git reset --hard $REV
    cd ..
}

# Symfony
#install_git symfony http://github.com/symfony/symfony.git v2.0.0BETA1
install_git symfony http://github.com/symfony/symfony.git

# Doctrine ORM
install_git doctrine http://github.com/doctrine/doctrine2.git

# Doctrine DBAL
install_git doctrine-dbal http://github.com/doctrine/dbal.git

# Doctrine Common
install_git doctrine-common http://github.com/doctrine/common.git

# Doctrine Migrations
install_git doctrine-migrations http://github.com/doctrine/migrations.git

# Doctrine Data Fixtures Extension
install_git doctrine-data-fixtures http://github.com/doctrine/data-fixtures.git

# Doctrine Extensions
install_git doctrine-extensions http://github.com/l3pp4rd/DoctrineExtensions.git

# Twig
install_git twig http://github.com/fabpot/Twig.git v1.0.0

# Twig Extensions
install_git twig-extensions http://github.com/fabpot/Twig-extensions.git

# Monolog
install_git monolog http://github.com/Seldaek/monolog.git

# Imagine
install_git imagine http://github.com/avalanche123/Imagine.git

# ZF
mkdir -p $VENDOR/zf/library
svn co http://framework.zend.com/svn/framework/standard/tags/release-1.11.5/library/ $VENDOR/zf/library

# ZF2
install_git zf2 http://github.com/zendframework/zf2.git


# FunctionalTestBundle
mkdir -p $BUNDLES/Liip
cd $BUNDLES/Liip
install_git FunctionalTestBundle http://github.com/liip/FunctionalTestBundle.git

# MenuBundle
mkdir -p $BUNDLES/Knplabs/Bundle
cd $BUNDLES/Knplabs/Bundle
install_git MenuBundle http://github.com/knplabs/MenuBundle.git

# MenuBundle
mkdir -p $BUNDLES/Knplabs/Bundle
cd $BUNDLES/Knplabs/Bundle
install_git PaginatorBundle http://github.com/knplabs/PaginatorBundle.git

# ZendCacheBundle
mkdir -p $BUNDLES/Bundle
cd $BUNDLES/Bundle
install_git ZendCacheBundle http://github.com/knplabs/ZendCacheBundle.git

# SensioFrameworkExtraBundle
mkdir -p $BUNDLES/Sensio/Bundle
cd $BUNDLES/Sensio/Bundle
install_git FrameworkExtraBundle http://github.com/sensio/SensioFrameworkExtraBundle.git

# DoctrineMigrationsBundle
mkdir -p $VENDOR/symfony/src/Symfony/Bundle/
cd $VENDOR/symfony/src/Symfony/Bundle/
install_git DoctrineMigrationsBundle http://github.com/symfony/DoctrineMigrationsBundle.git

# DoctrineFixturesBundle
mkdir -p $VENDOR/symfony/src/Symfony/Bundle/
cd $VENDOR/symfony/src/Symfony/Bundle/
install_git DoctrineFixturesBundle http://github.com/symfony/DoctrineFixturesBundle.git

# DoctrineExtensionsBundle
mkdir -p $BUNDLES/Stof
cd $BUNDLES/Stof
install_git DoctrineExtensionsBundle http://github.com/stof/DoctrineExtensionsBundle.git


# Update the bootstrap files
$DIR/bin/build_bootstrap.php

# Update assets
$DIR/app/console assets:install --symlink $DIR/web/
