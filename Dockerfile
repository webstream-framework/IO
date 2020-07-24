FROM php:7.4-cli
LABEL maintainer Ryuichi Tanaka <mapserver2007@gmail.com>

RUN apt-get update && apt-get install -y \
  git

RUN pecl install xdebug-2.9.6 && \
  docker-php-ext-enable xdebug

RUN rm -rf /var/lib/apt/lists/* && rm -rf /tmp/pear && rm -rf /tmp/* && \
  apt-get clean -y && apt-get autoclean -y

WORKDIR /workspace
