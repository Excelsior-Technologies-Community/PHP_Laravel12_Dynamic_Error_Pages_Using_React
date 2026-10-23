import React, { useState } from "react";

export default function ErrorReportModal({ statusCode = 404, theme = "clean" }) {
    const [isOpen, setIsOpen] = useState(false);
    const [message, setMessage] = useState("");
    const [email, setEmail] = useState("");
    const [submitting, setSubmitting] = useState(false);
    const [ticketId, setTicketId] = useState(null);
    const [errorMsg, setErrorMsg] = useState("");
    const [copied, setCopied] = useState(false);

    const isCyber = theme === "cyberpunk";

    async function handleSubmit(e) {
        e.preventDefault();
        setSubmitting(true);
        setErrorMsg("");

        const diagnosticData = {
            viewport: `${window.innerWidth}x${window.innerHeight}`,
            referrer: document.referrer || "Direct / Bookmark",
            platform: navigator.platform || "Unknown",
            language: navigator.language || "en",
            timestamp: new Date().toISOString(),
        };

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";

            const response = await fetch("/api/error-report", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json",
                },
                body: JSON.stringify({
                    status_code: statusCode,
                    url: window.location.href,
                    user_message: message,
                    user_email: email,
                    diagnostic_data: diagnosticData,
                }),
            });

            const data = await response.json();

            if (response.ok && data.success) {
                setTicketId(data.ticket_id);
            } else {
                setErrorMsg(data.message || "Unable to send report. Please try again.");
            }
        } catch (err) {
            setErrorMsg("Network error occurred while submitting report.");
        } finally {
            setSubmitting(false);
        }
    }

    function resetModal() {
        setIsOpen(false);
        setTicketId(null);
        setMessage("");
        setEmail("");
        setErrorMsg("");
        setCopied(false);
    }

    return (
        <div style={{ marginTop: "15px" }}>
            {/* Trigger Button */}
            <button onClick={() => setIsOpen(true)} style={triggerBtn(isCyber)}>
                🚨 Report Issue to Webmaster
            </button>

            {/* Modal Backdrop */}
            {isOpen && (
                <div style={modalBackdrop}>
                    <div style={modalCard(isCyber)}>
                        <div style={modalHeader(isCyber)}>
                            <h3 style={{ margin: 0, fontSize: "17px", fontWeight: "bold" }}>
                                🚨 Incident Diagnostic Report
                            </h3>
                            <button onClick={resetModal} style={closeBtn(isCyber)}>
                                ✕
                            </button>
                        </div>

                        {!ticketId ? (
                            <form onSubmit={handleSubmit} style={{ marginTop: "14px" }}>
                                <div style={{ marginBottom: "12px", textAlign: "left" }}>
                                    <label style={labelStyle(isCyber)}>Failing URL</label>
                                    <input
                                        type="text"
                                        readOnly
                                        value={typeof window !== "undefined" ? window.location.href : ""}
                                        style={inputReadOnly(isCyber)}
                                    />
                                </div>

                                <div style={{ marginBottom: "12px", textAlign: "left" }}>
                                    <label style={labelStyle(isCyber)}>
                                        What happened? *
                                    </label>
                                    <textarea
                                        required
                                        rows={3}
                                        value={message}
                                        onChange={(e) => setMessage(e.target.value)}
                                        placeholder="Describe what you were clicking or looking for..."
                                        style={textareaStyle(isCyber)}
                                    />
                                </div>

                                <div style={{ marginBottom: "14px", textAlign: "left" }}>
                                    <label style={labelStyle(isCyber)}>
                                        Your Email (Optional, for resolution alerts)
                                    </label>
                                    <input
                                        type="email"
                                        value={email}
                                        onChange={(e) => setEmail(e.target.value)}
                                        placeholder="user@example.com"
                                        style={inputStyle(isCyber)}
                                    />
                                </div>

                                <div style={diagBadge(isCyber)}>
                                    ⚡ Diagnostics (Viewport, OS & Referrer) will be automatically captured to assist debugging.
                                </div>

                                {errorMsg && (
                                    <p style={{ color: "#ef4444", fontSize: "12px", margin: "8px 0" }}>
                                        {errorMsg}
                                    </p>
                                )}

                                <div style={modalFooter}>
                                    <button type="button" onClick={resetModal} style={cancelBtn(isCyber)}>
                                        Cancel
                                    </button>
                                    <button type="submit" disabled={submitting} style={submitBtn(isCyber)}>
                                        {submitting ? "Transmitting..." : "Submit Incident Report"}
                                    </button>
                                </div>
                            </form>
                        ) : (
                            <div style={{ textAlign: "center", padding: "15px 0" }}>
                                <div style={{ fontSize: "38px", marginBottom: "8px" }}>✅</div>
                                <h4 style={{ margin: "0 0 6px", fontSize: "16px", fontWeight: "bold" }}>
                                    Incident Registered!
                                </h4>
                                <p style={{ fontSize: "13px", opacity: 0.85, margin: "0 0 14px" }}>
                                    Our development team has received your telemetry report.
                                </p>

                                <div style={ticketCard(isCyber)}>
                                    <span style={{ fontSize: "11px", textTransform: "uppercase", letterSpacing: "1px" }}>
                                        Your Incident Ticket ID
                                    </span>
                                    <div style={{ fontSize: "20px", fontWeight: "bold", margin: "4px 0", letterSpacing: "2px" }}>
                                        {ticketId}
                                    </div>
                                    <button
                                        type="button"
                                        onClick={() => {
                                            navigator.clipboard.writeText(ticketId);
                                            setCopied(true);
                                            setTimeout(() => setCopied(false), 2000);
                                        }}
                                        style={copyBtn(isCyber)}
                                    >
                                        {copied ? "Copied!" : "📋 Copy Ticket ID"}
                                    </button>
                                </div>

                                <button onClick={resetModal} style={doneBtn(isCyber)}>
                                    Done
                                </button>
                            </div>
                        )}
                    </div>
                </div>
            )}
        </div>
    );
}

const triggerBtn = (isCyber) => ({
    background: "transparent",
    border: isCyber ? "1px solid #f43f5e" : "1px solid #e2e8f0",
    color: isCyber ? "#f43f5e" : "#64748b",
    padding: "7px 14px",
    borderRadius: "8px",
    cursor: "pointer",
    fontSize: "12px",
    fontWeight: "600",
    transition: "all 0.2s",
});

const modalBackdrop = {
    position: "fixed",
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    backgroundColor: "rgba(0, 0, 0, 0.65)",
    display: "flex",
    justifyContent: "center",
    alignItems: "center",
    zIndex: 9999,
    padding: "16px",
};

const modalCard = (isCyber) => ({
    background: isCyber ? "#090d16" : "#ffffff",
    color: isCyber ? "#e2e8f0" : "#1e293b",
    border: isCyber ? "1px solid #06b6d4" : "1px solid #e2e8f0",
    borderRadius: "14px",
    width: "100%",
    maxWidth: "420px",
    padding: "20px",
    boxShadow: isCyber ? "0 0 25px rgba(6, 182, 212, 0.3)" : "0 10px 25px rgba(0,0,0,0.15)",
});

const modalHeader = (isCyber) => ({
    display: "flex",
    justifyContent: "space-between",
    alignItems: "center",
    borderBottom: isCyber ? "1px solid #1e293b" : "1px solid #f1f5f9",
    paddingBottom: "10px",
});

const closeBtn = (isCyber) => ({
    background: "none",
    border: "none",
    color: isCyber ? "#94a3b8" : "#64748b",
    fontSize: "16px",
    cursor: "pointer",
});

const labelStyle = (isCyber) => ({
    display: "block",
    fontSize: "12px",
    fontWeight: "bold",
    marginBottom: "4px",
    color: isCyber ? "#94a3b8" : "#475569",
});

const inputReadOnly = (isCyber) => ({
    width: "100%",
    padding: "7px 10px",
    borderRadius: "6px",
    border: isCyber ? "1px solid #1e293b" : "1px solid #e2e8f0",
    background: isCyber ? "#020617" : "#f8f9fa",
    color: isCyber ? "#64748b" : "#64748b",
    fontSize: "12px",
    boxSizing: "border-box",
});

const textareaStyle = (isCyber) => ({
    width: "100%",
    padding: "8px 10px",
    borderRadius: "6px",
    border: isCyber ? "1px solid #06b6d4" : "1px solid #cbd5e1",
    background: isCyber ? "#020617" : "#ffffff",
    color: isCyber ? "#f8fafc" : "#1e293b",
    fontSize: "13px",
    boxSizing: "border-box",
    fontFamily: "inherit",
    outline: "none",
});

const inputStyle = (isCyber) => ({
    width: "100%",
    padding: "8px 10px",
    borderRadius: "6px",
    border: isCyber ? "1px solid #334155" : "1px solid #cbd5e1",
    background: isCyber ? "#020617" : "#ffffff",
    color: isCyber ? "#f8fafc" : "#1e293b",
    fontSize: "13px",
    boxSizing: "border-box",
    fontFamily: "inherit",
    outline: "none",
});

const diagBadge = (isCyber) => ({
    background: isCyber ? "#0f172a" : "#f1f5f9",
    color: isCyber ? "#38bdf8" : "#475569",
    fontSize: "11px",
    padding: "8px",
    borderRadius: "6px",
    marginBottom: "12px",
    textAlign: "left",
});

const modalFooter = {
    display: "flex",
    justifyContent: "flex-end",
    gap: "8px",
    marginTop: "12px",
};

const cancelBtn = (isCyber) => ({
    background: "transparent",
    border: isCyber ? "1px solid #334155" : "1px solid #cbd5e1",
    color: isCyber ? "#94a3b8" : "#64748b",
    padding: "7px 14px",
    borderRadius: "6px",
    cursor: "pointer",
    fontSize: "12px",
});

const submitBtn = (isCyber) => ({
    background: isCyber ? "#06b6d4" : "#2563eb",
    border: "none",
    color: "#fff",
    padding: "7px 16px",
    borderRadius: "6px",
    cursor: "pointer",
    fontSize: "12px",
    fontWeight: "bold",
});

const ticketCard = (isCyber) => ({
    background: isCyber ? "#020617" : "#f8fafc",
    border: isCyber ? "1px solid #22c55e" : "1px solid #e2e8f0",
    borderRadius: "10px",
    padding: "12px",
    marginBottom: "15px",
    color: isCyber ? "#22c55e" : "#15803d",
});

const copyBtn = (isCyber) => ({
    background: isCyber ? "#1e293b" : "#e2e8f0",
    border: "none",
    color: isCyber ? "#22c55e" : "#1e293b",
    padding: "4px 10px",
    borderRadius: "4px",
    cursor: "pointer",
    fontSize: "11px",
    marginTop: "4px",
});

const doneBtn = (isCyber) => ({
    background: isCyber ? "#06b6d4" : "#2563eb",
    border: "none",
    color: "#fff",
    padding: "8px 24px",
    borderRadius: "6px",
    cursor: "pointer",
    fontSize: "13px",
    fontWeight: "bold",
});
