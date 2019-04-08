FROM debian:wheezy
ENV NODE_VERSION 0.10.33
RUN rm /etc/apt/sources.list
RUN touch /etc/apt/sources.list
RUN echo  "deb http://archive.debian.org/debian wheezy main" >> /etc/apt/sources.list
RUN echo "deb http://archive.debian.org/debian-security wheezy/updates main" >> /etc/apt/sources.list
RUN apt-get update && gpg --keyserver keys.gnupg.net --recv-key 89DF5277 && gpg -a --export 89DF5277 | apt-key add -
RUN apt-get install -y wget curl ca-certificates procps locales zip apt-transport-https git sudo && rm -rf /var/lib/apt/lists/*
RUN curl -O https://www.dotdeb.org/dotdeb.gpg && sudo apt-key add dotdeb.gpg
RUN echo 'deb http://packages.dotdeb.org wheezy all' > /etc/apt/sources.list.d/php.list
RUN apt-get update && apt-get install -y php-pear php5 php5-cli php5-common php5-curl php5-dev php5-gd php5-imagick php5-imap php5-intl php5-mcrypt php5-apcu php5-sqlite php5-fpm php5-xdebug php5-mysqlnd && rm -rf /var/lib/apt/lists/*
RUN wget https://repo.percona.com/apt/percona-release_0.1-4.wheezy_all.deb -O /tmp/percona.deb && dpkg -i /tmp/percona.deb && rm -rf /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y percona-server-client-5.6 php5-mysql && rm -rf /var/lib/apt/lists/*
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
RUN mkdir /var/www
RUN cd /tmp && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN cd /tmp && php -r "if (hash_file('SHA384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN cd /tmp && php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN cd /tmp && php -r "unlink('composer-setup.php');"
RUN curl -SLO https://nodejs.org/dist/v$NODE_VERSION/node-v$NODE_VERSION-linux-x64.tar.gz && \
    tar -xvzf node-v$NODE_VERSION-linux-x64.tar.gz -C / --strip-components=1 && \
    rm /node-v$NODE_VERSION-linux-x64.tar.gz
RUN npm install -g uglify-js
RUN npm install -g grunt
RUN npm install -g less@1.7.5
USER www-data
WORKDIR /app
CMD sudo /usr/local/bin/start
