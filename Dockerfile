FROM php:8.1-cli

WORKDIR /app

COPY . .

RUN chmod +x start.sh

EXPOSE 8080

CMD ["bash", "start.sh"]
