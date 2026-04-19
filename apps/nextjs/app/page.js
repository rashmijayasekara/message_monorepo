import { saveMessage, getMessages } from './actions';

export const dynamic = 'force-dynamic';

export default async function Home() {
  const messages = await getMessages();

  return (
    <main>
      <h1>Message Logger</h1>

      <form action={saveMessage} style={{ marginBottom: '30px' }}>
        <div style={{ marginBottom: '10px' }}>
          <label htmlFor="message" style={{ display: 'block', marginBottom: '6px', fontWeight: 'bold' }}>
            Message:
          </label>
          <input
            id="message"
            name="message"
            type="text"
            required
            placeholder="Enter your message..."
            style={{
              width: '100%',
              padding: '8px',
              fontSize: '16px',
              border: '1px solid #ccc',
              borderRadius: '4px',
              boxSizing: 'border-box',
            }}
          />
        </div>
        <button
          type="submit"
          style={{
            padding: '8px 20px',
            fontSize: '16px',
            backgroundColor: '#0070f3',
            color: 'white',
            border: 'none',
            borderRadius: '4px',
            cursor: 'pointer',
          }}
        >
          Submit
        </button>
      </form>

      <h2>Logged Messages</h2>
      {messages.length === 0 ? (
        <p style={{ color: '#666' }}>No messages yet.</p>
      ) : (
        <ul style={{ paddingLeft: '20px' }}>
          {messages.map((msg, i) => (
            <li key={i} style={{ marginBottom: '8px' }}>
              {msg}
            </li>
          ))}
        </ul>
      )}
    </main>
  );
}
