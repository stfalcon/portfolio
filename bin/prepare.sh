#!/bin/sh

# init
DIR=`php -r "echo realpath(dirname(\\$_SERVER['argv'][0]));"`
cd $DIR/
rm -rf vendor
mkdir vendor
TARGET=$DIR/vendor
cd $TARGET

if [ ! -d "$DIR/vendor" ]; then
    echo "The master vendor directory does not exist"
    exit
fi

cp -r $DIR/vendor_full/* .

# Default configuration
cp $DIR/../../symfony-bootstrapper/src/skeleton/application/php/config/config* $DIR/app/config/
cp $DIR/../../symfony-bootstrapper/src/skeleton/application/yml/config/config* $DIR/app/config/
cp $DIR/../../symfony-bootstrapper/src/skeleton/application/xml/config/config* $DIR/app/config/

# Assetic
cd assetic && rm -rf tests
cd $TARGET

# Doctrine ORM
cd doctrine && rm -rf UPGRADE* build* bin tests tools lib/vendor
cd $TARGET

# Doctrine DBAL
cd doctrine-dbal && rm -rf bin build* tests lib/vendor
cd $TARGET

# Doctrine Common
cd doctrine-common && rm -rf build* tests lib/vendor
cd $TARGET

# Doctrine migrations
cd doctrine-migrations && rm -rf tests build* lib/vendor
cd $TARGET

# Doctrine data fixtures
cd doctrine-data-fixtures && rm -rf tests README* lib/vendor
cd $TARGET

# Doctrine MongoDB
cd doctrine-mongodb && rm -rf tests build* tools lib/vendor
cd $TARGET

# Doctrine MongoDB ODM
cd doctrine-mongodb-odm && rm -rf tests build* tools phpunit.xml* build* bin README* lib/vendor
cd $TARGET

# Swiftmailer
cd swiftmailer && rm -rf CHANGES README* build* docs notes test-suite tests create_pear_package.php package*
cd $TARGET

# Symfony
cd symfony && rm -rf README phpunit.xml* tests *.sh vendor
cd $TARGET

# Twig
cd twig && rm -rf AUTHORS CHANGELOG README.markdown bin doc package.xml.tpl phpunit.xml* test
cd $TARGET

# Twig Extensions
cd twig-extensions && rm -rf README doc phpunit.xml* test
cd $TARGET

# cleanup
find . -name .git | xargs rm -rf -
find . -name .gitignore | xargs rm -rf -
find . -name .gitmodules | xargs rm -rf -
find . -name .svn | xargs rm -rf -
cd $DIR
