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
# @param URL of the git remote (e.g. https://github.com/doctrine/doctrine2.git)
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

# Assetic
install_git assetic https://github.com/kriswallsmith/assetic.git

# Symfony
install_git symfony https://github.com/symfony/symfony.git

# Doctrine ORM
install_git doctrine https://github.com/doctrine/doctrine2.git

# Doctrine Data Fixtures Extension
install_git doctrine-data-fixtures https://github.com/doctrine/data-fixtures.git

# Doctrine DBAL
install_git doctrine-dbal https://github.com/doctrine/dbal.git

# Doctrine Common
install_git doctrine-common https://github.com/doctrine/common.git

# Doctrine migrations
install_git doctrine-migrations https://github.com/doctrine/migrations.git

# Doctrine MongoDB
#install_git doctrine-mongodb https://github.com/doctrine/mongodb.git

# Doctrine MongoDB
#install_git doctrine-mongodb-odm https://github.com/doctrine/mongodb-odm.git

# Swiftmailer
install_git swiftmailer https://github.com/swiftmailer/swiftmailer.git origin/4.1

# Twig
install_git twig https://github.com/fabpot/Twig.git v1.0.0

# Twig Extensions
install_git twig-extensions https://github.com/fabpot/Twig-extensions.git

# Imagine
install_git imagine https://github.com/avalanche123/Imagine.git

# Doctrine Extensions
install_git doctrine-extensions https://github.com/l3pp4rd/DoctrineExtensions.git

# ZF
mkdir -p $VENDOR/zf/library
svn co http://framework.zend.com/svn/framework/standard/tags/release-1.11.5/library/ $VENDOR/zf/library

# ZF2
install_git zf2 https://github.com/zendframework/zf2.git


# FunctionalTestBundle
mkdir -p $BUNDLES/Liip
cd $BUNDLES/Liip
install_git FunctionalTestBundle https://github.com/liip/FunctionalTestBundle.git

# MenuBundle
mkdir -p $BUNDLES/Knplabs/Bundle
cd $BUNDLES/Knplabs/Bundle
install_git MenuBundle https://github.com/knplabs/MenuBundle.git

# ZendCacheBundle
mkdir -p $BUNDLES/Bundle
cd $BUNDLES/Bundle
install_git ZendCacheBundle https://github.com/knplabs/ZendCacheBundle.git

# SensioFrameworkExtraBundle
mkdir -p $BUNDLES/Sensio/Bundle
cd $BUNDLES/Sensio/Bundle
install_git FrameworkExtraBundle https://github.com/sensio/SensioFrameworkExtraBundle.git

# SecurityExtraBundle
mkdir -p $BUNDLES/JMS
cd $BUNDLES/JMS
install_git SecurityExtraBundle https://github.com/schmittjoh/SecurityExtraBundle.git

# DoctrineExtensionsBundle
mkdir -p $BUNDLES/Stof
cd $BUNDLES/Stof
install_git DoctrineExtensionsBundle https://github.com/stof/DoctrineExtensionsBundle.git

# DoctrineFixturesBundle
mkdir -p $VENDOR/symfony/src/Symfony/Bundle/
cd $VENDOR/symfony/src/Symfony/Bundle/
install_git DoctrineFixturesBundle https://github.com/symfony/DoctrineFixturesBundle.git

# Update the bootstrap files
$DIR/bin/build_bootstrap.php

# Update assets
$DIR/app/console assets:install --symlink $DIR/web/
