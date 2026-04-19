FROM python:3.12-slim

WORKDIR /app

ENV PYTHONDONTWRITEBYTECODE=1 \
    PYTHONUNBUFFERED=1 \
    PORT=5000 \
    LOG_FILE=/data/message_log.txt

RUN addgroup --system appgroup && \
    adduser --system --ingroup appgroup appuser && \
    mkdir -p /data && \
    chown appuser:appgroup /data

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY --chown=appuser:appgroup . .

USER appuser

EXPOSE 5000

VOLUME ["/data"]

CMD ["gunicorn", "--bind", "0.0.0.0:5000", "--workers", "2", "app:app"]
