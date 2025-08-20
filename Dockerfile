FROM php:8.2-cli

RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends libsqlite3-dev pkg-config; \
	docker-php-ext-install pdo_sqlite; \
	rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . /var/www/html

EXPOSE 7418

CMD ["php", "-S", "0.0.0.0:7418", "-t", "/var/www/html"]
