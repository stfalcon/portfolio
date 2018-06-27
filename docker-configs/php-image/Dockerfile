FROM debian:wheezy
ENV NODE_VERSION 0.10.33
RUN apt-get update && apt-get install -y wget curl ca-certificates procps locales zip apt-transport-https git sudo
RUN wget https://www.dotdeb.org/dotdeb.gpg && sudo apt-key add dotdeb.gpg
RUN echo 'deb http://packages.dotdeb.org wheezy all' > /etc/apt/sources.list.d/php.list
RUN apt-get update && apt-get install -y php-pear php5 php5-cli php5-common php5-curl php5-dev php5-gd php5-imagick php5-imap php5-intl php5-mcrypt php5-apcu php5-sqlite php5-fpm php5-xdebug php5-mysqlnd
RUN wget https://repo.percona.com/apt/percona-release_0.1-4.wheezy_all.deb -O /tmp/percona.deb
RUN dpkg -i /tmp/percona.deb && rm /tmp/percona.deb && apt-get update
RUN apt-get install -y percona-server-client-5.6 php5-mysql
ADD configs/xdebug.ini /etc/php5/mods-available/xdebug.ini
ADD configs/xdebug-cli /usr/local/bin/xdebug-cli
RUN mkdir /.macos_conigs
ADD configs/mac_xdebug.ini /.macos_conigs/mac_xdebug.ini
ADD configs/mac_xdebug-cli /.macos_conigs/mac_xdebug-cli
RUN chmod a+x /usr/local/bin/xdebug-cli
ADD configs/www.conf /etc/php5/fpm/pool.d/www.conf
ADD configs/start /usr/local/bin/start
RUN chmod a+x /usr/local/bin/start
RUN echo 'www-data ALL=(ALL) NOPASSWD: ALL' > /etc/sudoers.d/10_www_data
RUN mkdir /app && chown www-data:www-data /app
RUN cd /tmp && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN cd /tmp && php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN cd /tmp && php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN cd /tmp && php -r "unlink('composer-setup.php');"
RUN curl -SLO https://nodejs.org/dist/v$NODE_VERSION/node-v$NODE_VERSION-linux-x64.tar.gz
RUN sudo tar -xvzf node-v$NODE_VERSION-linux-x64.tar.gz -C / --strip-components=1
RUN sudo npm install -g grunt
RUN sudo npm install -g less
RUN mkdir /var/www
USER www-data
WORKDIR /app
CMD sudo /usr/local/bin/start
