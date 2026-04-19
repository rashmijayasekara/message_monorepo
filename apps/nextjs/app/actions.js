'use server';

import fs from 'fs';
import path from 'path';
import { revalidatePath } from 'next/cache';

const LOG_FILE = process.env.LOG_FILE || 'message_log.txt';

function ensureLogFile() {
  const dir = path.dirname(LOG_FILE);
  if (dir && dir !== '.') {
    fs.mkdirSync(dir, { recursive: true });
  }
}

export async function saveMessage(formData) {
  const message = formData.get('message');
  if (!message || !message.trim()) return;

  ensureLogFile();
  fs.appendFileSync(LOG_FILE, message.trim() + '\n', 'utf8');
  revalidatePath('/');
}

export async function getMessages() {
  ensureLogFile();
  if (!fs.existsSync(LOG_FILE)) return [];

  const content = fs.readFileSync(LOG_FILE, 'utf8');
  return content.split('\n').filter((line) => line.trim() !== '');
}
