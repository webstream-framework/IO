FROM php:7.0.9-alpine
MAINTAINER Ryuichi Tanaka <mapserver2007@gmail.com>

RUN apk add --update git build-base \
  && rm -rf /var/cache/apk/*

RUN curl -s http://getcomposer.org/installer | php && mv -f composer.phar /usr/bin/composer \
  && chmod +x /usr/bin/composer \
  && composer self-update

RUN git clone https://github.com/webstream-framework/IO.git workspace \
  && cd workspace \
  && composer install

WORKDIR /workspace
