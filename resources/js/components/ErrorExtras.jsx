import React, { useEffect, useState } from "react";

const tips = [
    "Double-check the URL slug and parameters.",
    "Return to the home page to resume browsing.",
    "Refresh the page if you suspect network latency.",
    "Use our search bar to find the relevant section.",
    "Contact webmaster if this issue persists.",
];

export default function ErrorExtras({ theme = "clean" }) {
    const [seconds, setSeconds] = useState(15);
    const [cancelled, setCancelled] = useState(false);
    const [tip] = useState(() => tips[Math.floor(Math.random() * tips.length)]);

    const isCyber = theme === "cyberpunk";

    useEffect(() => {
        if (cancelled) return;

        if (seconds <= 0) {
            window.location.href = "/";
            return;
        }

        const timer = setTimeout(() => {
            setSeconds((prev) => prev - 1);
        }, 1000);

        return () => clearTimeout(timer);
    }, [seconds, cancelled]);

    return (
        <div style={styles.container}>
            <div style={tipBox(isCyber)}>
                💡 <strong>Helpful Tip</strong>
                <p style={{ margin: "6px 0 0", fontSize: "13px", opacity: 0.9 }}>{tip}</p>
            </div>

            {!cancelled ? (
                <div style={countdownWrapper}>
                    <p style={countdownText(isCyber)}>
                        Auto-redirecting Home in <strong>{seconds}</strong>s
                    </p>
                    <button
                        type="button"
                        onClick={() => setCancelled(true)}
                        style={cancelRedirectBtn(isCyber)}
                    >
                        Pause
                    </button>
                </div>
            ) : (
                <p style={cancelledText(isCyber)}>⏸️ Auto Redirect Cancelled</p>
            )}
        </div>
    );
}

const styles = {
    container: {
        marginTop: "16px",
        marginBottom: "12px",
    },
};

const tipBox = (isCyber) => ({
    background: isCyber ? "rgba(15, 23, 42, 0.7)" : "#f8fafc",
    border: isCyber ? "1px solid #1e293b" : "1px solid #e2e8f0",
    borderRadius: "10px",
    padding: "10px 14px",
    fontSize: "13px",
    color: isCyber ? "#38bdf8" : "#334155",
    textAlign: "left",
    marginBottom: "10px",
});

const countdownWrapper = {
    display: "flex",
    justifyContent: "center",
    alignItems: "center",
    gap: "10px",
};

const countdownText = (isCyber) => ({
    fontSize: "12px",
    color: isCyber ? "#94a3b8" : "#64748b",
    margin: 0,
});

const cancelRedirectBtn = (isCyber) => ({
    background: "transparent",
    border: isCyber ? "1px solid #334155" : "1px solid #cbd5e1",
    color: isCyber ? "#06b6d4" : "#2563eb",
    borderRadius: "4px",
    padding: "2px 8px",
    fontSize: "11px",
    cursor: "pointer",
});

const cancelledText = (isCyber) => ({
    fontSize: "12px",
    color: isCyber ? "#64748b" : "#94a3b8",
    margin: 0,
});
