import React from "react";

export default function Mascot500({ theme = "clean" }) {
    const isCyber = theme === "cyberpunk";

    return (
        <div style={styles.wrapper}>
            <svg
                width="160"
                height="160"
                viewBox="0 0 200 200"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                style={styles.svgServer}
            >
                {/* Smoke clouds */}
                <ellipse cx="80" cy="35" rx="14" ry="10" fill={isCyber ? "#475569" : "#cbd5e1"} opacity="0.7" />
                <ellipse cx="105" cy="25" rx="18" ry="12" fill={isCyber ? "#64748b" : "#94a3b8"} opacity="0.6" />
                <ellipse cx="125" cy="35" rx="12" ry="8" fill={isCyber ? "#475569" : "#cbd5e1"} opacity="0.7" />

                {/* Server Rack Body */}
                <rect x="50" y="55" width="100" height="120" rx="10" fill={isCyber ? "#0f172a" : "#1e293b"} stroke={isCyber ? "#f59e0b" : "#475569"} strokeWidth="4" />

                {/* Rack Unit 1 */}
                <rect x="60" y="68" width="80" height="24" rx="4" fill={isCyber ? "#1e293b" : "#334155"} />
                <circle cx="70" cy="80" r="3" fill="#22c55e" />
                <circle cx="80" cy="80" r="3" fill="#ef4444" className="flash-led" />
                <circle cx="90" cy="80" r="3" fill="#3b82f6" />
                <line x1="105" y1="80" x2="132" y2="80" stroke="#64748b" strokeWidth="3" strokeLinecap="round" />

                {/* Rack Unit 2 (Faulty / Overheated) */}
                <rect x="60" y="102" width="80" height="24" rx="4" fill={isCyber ? "#27121b" : "#450a0a"} stroke="#ef4444" strokeWidth="2" />
                <circle cx="70" cy="114" r="3.5" fill="#ef4444" />
                <circle cx="80" cy="114" r="3.5" fill="#ef4444" />
                <line x1="95" y1="114" x2="132" y2="114" stroke="#ef4444" strokeWidth="2" strokeDasharray="3 3" />

                {/* Electric Spark */}
                <path d="M145 95 L160 110 L152 112 L165 125" stroke={isCyber ? "#facc15" : "#eab308"} strokeWidth="3" fill="none" strokeLinecap="round" />

                {/* Rack Unit 3 */}
                <rect x="60" y="136" width="80" height="24" rx="4" fill={isCyber ? "#1e293b" : "#334155"} />
                <circle cx="70" cy="148" r="3" fill="#f59e0b" />
                <circle cx="80" cy="148" r="3" fill="#22c55e" />
                <line x1="95" y1="148" x2="132" y2="148" stroke="#64748b" strokeWidth="3" strokeLinecap="round" />

                {/* Warning sign */}
                <polygon points="100,85 106,97 94,97" fill="#facc15" />
            </svg>
            <style>{`
                @keyframes rumbleServer {
                    0%, 100% { transform: translateY(0px); }
                    25% { transform: translateY(-2px) rotate(-0.5deg); }
                    75% { transform: translateY(1px) rotate(0.5deg); }
                }
            `}</style>
        </div>
    );
}

const styles = {
    wrapper: {
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        marginBottom: "15px",
    },
    svgServer: {
        animation: "rumbleServer 1.5s ease-in-out infinite",
    },
};
