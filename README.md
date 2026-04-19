# Message Logger Monorepo

A simple message logging app built in three different stacks. Each app lets you submit messages through a form and stores them in a file.

## Apps

| App | Stack | Port |
|---|---|---|
| `apps/nextjs` | Next.js 14 + Server Actions | 3000 |
| `apps/flask` | Flask + Gunicorn | 5000 |
| `apps/php` | PHP 8.3 + Nginx + PHP-FPM | 8080 |

## Getting Started

### Run all apps with Docker

```bash
docker compose up --build
```

- Next.js → http://localhost:3000
- Flask → http://localhost:5000
- PHP → http://localhost:8080

### Run a single app

```bash
docker compose up flask
docker compose up nextjs
docker compose up php-fpm php-nginx
```

### Run locally without Docker

**Next.js**
```bash
cd apps/nextjs
npm install
npm run dev
```

**Flask**
```bash
cd apps/flask
pip install -r requirements.txt
python app.py
```

**PHP**
```bash
cd apps/php
LOG_FILE=./message_log.txt php -S localhost:8080 -t public
```

## API

All three apps expose the same REST API endpoints:

```
GET  /api/messages        returns all messages as JSON
POST /api/messages        saves a new message
```

**Example:**
```bash
curl http://localhost:5000/api/messages

curl -X POST http://localhost:5000/api/messages \
  -H "Content-Type: application/json" \
  -d '{"message": "hello world"}'
```

## Environment Variables

| Variable | Default | Description |
|---|---|---|
| `LOG_FILE` | `/data/message_log.txt` | Path to the message log file |
| `PORT` | varies by app | Port the app listens on |
| `DEBUG` | `False` | Enable debug mode (Flask only) |

Messages are persisted in Docker named volumes (`nextjs_data`, `flask_data`, `php_data`) so they survive container restarts.
