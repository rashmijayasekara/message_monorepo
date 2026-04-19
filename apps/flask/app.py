import os
from flask import Flask, render_template, request, redirect, url_for, jsonify

app = Flask(__name__)

LOG_FILE = os.getenv("LOG_FILE", "message_log.txt")

def ensure_log_dir():
    log_dir = os.path.dirname(LOG_FILE)
    if log_dir:
        os.makedirs(log_dir, exist_ok=True)

def read_messages():
    if not os.path.exists(LOG_FILE):
        return []
    with open(LOG_FILE, 'r') as f:
        return [line.strip() for line in f if line.strip()]

def append_message(message):
    ensure_log_dir()
    with open(LOG_FILE, 'a') as f:
        f.write(message.strip() + '\n')


@app.route('/', methods=['GET', 'POST'])
def index():
    if request.method == 'POST':
        message = request.form.get('message', '').strip()
        if message:
            append_message(message)
        return redirect(url_for('index'))

    return render_template('index.html', messages=read_messages())


@app.get('/api/messages')
def api_get_messages():
    return jsonify({"messages": read_messages()})

@app.post('/api/messages')
def api_post_message():
    data = request.get_json(silent=True) or {}
    message = data.get('message', '').strip()
    if not message:
        return jsonify({"error": "message field is required"}), 400
    append_message(message)
    return jsonify({"status": "ok", "message": message}), 201


if __name__ == '__main__':
    app.run(
        host="0.0.0.0",
        port=int(os.getenv("PORT", 5000)),
        debug=os.getenv("DEBUG", "False") == "True"
    )
