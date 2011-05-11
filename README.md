Движок портфолио web-студии stfalcon.com
========================================

Установка
---------

Очередность действий такая:
* Копируем проект с репозитория.
* В консоли идем в папку с проектом.
* Запускаем `bin/vendors.sh`. Этот скрипт автоматически загрузит все необходимые дополнительные библиотеки в папку vendor проекта (вместе с zf2).
* Запускаем `bin/build_bootstrap.php`
* В файлах `app/config/config.yml` и `app/config/config_dev.yml` меняем параметры коннекта к БД на свои:
###
    # Doctrine Configuration
    doctrine:
        dbal:
            driver:   pdo_mysql
            host:     localhost
            dbname:   stfalcon_com_development
            user:     root
            password: null
            charset:  UTF8
* В консоли набираем `app/console doctrine:database:create` и `app/console doctrine:migration:migrate`