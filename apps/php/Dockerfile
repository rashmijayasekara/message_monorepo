FROM php:8.3-fpm-alpine

ENV LOG_FILE=/data/message_log.txt

RUN addgroup -S appgroup && \
    adduser -S appuser -G appgroup && \
    mkdir -p /data && \
    chown appuser:appgroup /data

WORKDIR /var/www/html

COPY --chown=appuser:appgroup public/ ./public/
COPY --chown=appuser:appgroup api/ ./api/

USER appuser

VOLUME ["/data"]

EXPOSE 9000
