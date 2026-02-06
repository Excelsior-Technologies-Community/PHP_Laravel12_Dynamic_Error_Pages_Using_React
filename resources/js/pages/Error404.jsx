export default function Error404() {
  return (
    <div style={styles.container}>
      <div style={styles.box}>
        <h1 style={styles.title}>404</h1>
        <p style={styles.message}>Page Not Found</p>
        <p style={styles.description}>The page you're looking for doesn't exist or has been moved.</p>
        <a href="/" style={styles.button}>Go Home</a>
      </div>
    </div>
  );
}

const styles = {
  container: {
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    minHeight: '100vh',
    backgroundColor: '#f8f9fa',
    padding: '20px',
  },
  box: {
    textAlign: "center",
    fontFamily: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
    background: "white",
    padding: "60px 40px",
    borderRadius: "16px",
    width: "100%",
    maxWidth: "400px",
    boxShadow: "0 10px 30px rgba(0, 0, 0, 0.1)",
    border: "1px solid #e0e0e0",
  },
  title: {
    fontSize: "120px",
    fontWeight: "700",
    color: "#dc3545",
    margin: "0",
    lineHeight: "1",
  },
  message: {
    fontSize: "28px",
    color: "#333",
    margin: "20px 0 10px",
    fontWeight: "600",
  },
  description: {
    fontSize: "16px",
    color: "#666",
    margin: "0 0 30px",
    lineHeight: "1.5",
  },
  button: {
    display: "inline-block",
    padding: "12px 30px",
    backgroundColor: "#007bff",
    color: "white",
    textDecoration: "none",
    borderRadius: "8px",
    fontWeight: "600",
    fontSize: "16px",
    transition: "all 0.3s ease",
    border: "none",
    cursor: "pointer",
  },
};

// Add hover effect
styles.button[':hover'] = {
  backgroundColor: "#0056b3",
  transform: "translateY(-2px)",
  boxShadow: "0 5px 15px rgba(0, 123, 255, 0.3)",
};