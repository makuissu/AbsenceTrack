FROM php:8.1-cli

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /app

COPY . .

RUN chmod +x start.sh setup-db.sh

# Import database on first run
RUN bash setup-db.sh || true

EXPOSE 8080

CMD ["bash", "start.sh"]
