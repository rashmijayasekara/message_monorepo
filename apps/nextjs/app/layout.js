export const metadata = {
  title: 'Message Logger',
  description: 'A simple message logging application',
};

export default function RootLayout({ children }) {
  return (
    <html lang="en">
      <body style={{ fontFamily: 'Arial, sans-serif', maxWidth: '600px', margin: '40px auto', padding: '0 20px' }}>
        {children}
      </body>
    </html>
  );
}
