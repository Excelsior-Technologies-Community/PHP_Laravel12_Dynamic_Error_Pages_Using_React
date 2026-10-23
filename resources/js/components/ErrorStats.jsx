import React, { useEffect, useState } from "react";

export default function ErrorStats({ theme = "clean" }) {
    const [stats, setStats] = useState(null);
    const isCyber = theme === "cyberpunk";

    useEffect(() => {
        fetch("/error-stats")
            .then((res) => res.json())
            .then((data) => setStats(data))
            .catch(() => {});
    }, []);

    if (!stats) return null;

    return (
        <div style={containerStyle(isCyber)}>
            <div style={titleStyle(isCyber)}>
                <span style={radarDot}></span> Live System Telemetry
            </div>

            <div style={gridStyle}>
                <div style={statBox(isCyber)}>
                    <div style={statVal(isCyber)}>{stats.total}</div>
                    <div style={statLabel(isCyber)}>Total Visits</div>
                </div>
                <div style={statBox(isCyber)}>
                    <div style={statVal(isCyber)}>{stats.error404}</div>
                    <div style={statLabel(isCyber)}>404 Missing</div>
                </div>
                <div style={statBox(isCyber)}>
                    <div style={statVal(isCyber)}>{stats.error403}</div>
                    <div style={statLabel(isCyber)}>403 Denied</div>
                </div>
                <div style={statBox(isCyber)}>
                    <div style={statVal(isCyber)}>{stats.error500}</div>
                    <div style={statLabel(isCyber)}>500 Exceptions</div>
                </div>
            </div>
        </div>
    );
}

const containerStyle = (isCyber) => ({
    marginTop: "20px",
    padding: "12px",
    background: isCyber ? "rgba(2, 6, 23, 0.75)" : "#f8fafc",
    border: isCyber ? "1px solid #1e293b" : "1px solid #e2e8f0",
    borderRadius: "10px",
    textAlign: "center",
});

const titleStyle = (isCyber) => ({
    fontSize: "11px",
    fontWeight: "bold",
    textTransform: "uppercase",
    letterSpacing: "1px",
    color: isCyber ? "#38bdf8" : "#64748b",
    marginBottom: "8px",
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
    gap: "6px",
});

const radarDot = {
    width: "6px",
    height: "6px",
    borderRadius: "50%",
    background: "#22c55e",
    display: "inline-block",
};

const gridStyle = {
    display: "grid",
    gridTemplateColumns: "repeat(4, 1fr)",
    gap: "6px",
};

const statBox = (isCyber) => ({
    background: isCyber ? "#0f172a" : "#ffffff",
    padding: "6px 2px",
    borderRadius: "6px",
    border: isCyber ? "1px solid #1e293b" : "1px solid #f1f5f9",
});

const statVal = (isCyber) => ({
    fontSize: "14px",
    fontWeight: "bold",
    color: isCyber ? "#e2e8f0" : "#1e293b",
});

const statLabel = (isCyber) => ({
    fontSize: "9px",
    color: isCyber ? "#64748b" : "#94a3b8",
    textTransform: "uppercase",
});
