export default function Error500() {
  const handleRefresh = () => {
    window.location.reload();
  };

  const handleContact = () => {
    window.location.href = "mailto:support@example.com";
  };

  return (
    <div style={styles.container}>
      <div style={styles.box}>
        <h1 style={styles.title}>500</h1>
        <p style={styles.message}>Server Error</p>
        <p style={styles.description}>Something went wrong on our end. Please try again later.</p>
        <div style={styles.buttonGroup}>
          <button onClick={handleRefresh} style={styles.primaryButton}>
            Try Again
          </button>
          <button onClick={handleContact} style={styles.secondaryButton}>
            Contact Support
          </button>
        </div>
        <div style={styles.tips}>
          <h4 style={styles.tipsTitle}>What you can do:</h4>
          <ul style={styles.tipsList}>
            <li>Refresh the page</li>
            <li>Clear your browser cache</li>
            <li>Check back in a few minutes</li>
          </ul>
        </div>
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
    maxWidth: "450px",
    boxShadow: "0 10px 30px rgba(0, 0, 0, 0.1)",
    border: "1px solid #e0e0e0",
  },
  title: {
    fontSize: "120px",
    fontWeight: "700",
    color: "#17a2b8",
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
  buttonGroup: {
    display: "flex",
    flexDirection: "column",
    gap: "15px",
    marginBottom: "30px",
  },
  primaryButton: {
    padding: "12px 30px",
    backgroundColor: "#17a2b8",
    color: "white",
    border: "none",
    borderRadius: "8px",
    fontWeight: "600",
    fontSize: "16px",
    cursor: "pointer",
    transition: "all 0.3s ease",
  },
  secondaryButton: {
    padding: "12px 30px",
    backgroundColor: "transparent",
    color: "#17a2b8",
    border: "2px solid #17a2b8",
    borderRadius: "8px",
    fontWeight: "600",
    fontSize: "16px",
    cursor: "pointer",
    transition: "all 0.3s ease",
  },
  tips: {
    textAlign: "left",
    backgroundColor: "#f8f9fa",
    padding: "15px",
    borderRadius: "8px",
    marginTop: "20px",
  },
  tipsTitle: {
    margin: "0 0 10px 0",
    color: "#555",
    fontSize: "16px",
  },
  tipsList: {
    margin: "0",
    paddingLeft: "20px",
    color: "#666",
    fontSize: "14px",
    lineHeight: "1.6",
  },
};