import React from "react";

export default function Mascot404({ theme = "clean" }) {
    const isCyber = theme === "cyberpunk";

    return (
        <div style={styles.wrapper}>
            <svg
                width="160"
                height="160"
                viewBox="0 0 200 200"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                style={styles.svgFloat}
            >
                {/* Space helmet & visor */}
                <circle cx="100" cy="90" r="50" fill={isCyber ? "#1e293b" : "#f1f5f9"} stroke={isCyber ? "#06b6d4" : "#94a3b8"} strokeWidth="4" />
                <ellipse cx="100" cy="88" rx="35" ry="25" fill={isCyber ? "#0f172a" : "#1e293b"} stroke={isCyber ? "#22d3ee" : "#38bdf8"} strokeWidth="3" />
                
                {/* Visor reflection */}
                <ellipse cx="90" cy="80" rx="12" ry="6" fill={isCyber ? "#22d3ee" : "#60a5fa"} opacity="0.6" transform="rotate(-15 90 80)" />

                {/* Astronaut body / suit */}
                <rect x="75" y="138" width="50" height="42" rx="12" fill={isCyber ? "#1e293b" : "#e2e8f0"} stroke={isCyber ? "#06b6d4" : "#94a3b8"} strokeWidth="4" />
                
                {/* Oxygen pack tubes */}
                <path d="M68 145 Q50 155 60 175" stroke={isCyber ? "#f43f5e" : "#ef4444"} strokeWidth="4" fill="none" strokeLinecap="round" />
                <path d="M132 145 Q150 155 140 175" stroke={isCyber ? "#f43f5e" : "#ef4444"} strokeWidth="4" fill="none" strokeLinecap="round" />

                {/* Chest badge */}
                <circle cx="90" cy="155" r="4" fill={isCyber ? "#22c55e" : "#10b981"} />
                <rect x="100" y="153" width="16" height="4" rx="2" fill={isCyber ? "#38bdf8" : "#64748b"} />

                {/* Satellite / Lost 404 flag */}
                <line x1="140" y1="120" x2="165" y2="90" stroke={isCyber ? "#f43f5e" : "#cbd5e1"} strokeWidth="3" />
                <rect x="155" y="80" width="30" height="18" rx="3" fill={isCyber ? "#f43f5e" : "#dc2626"} />
                <text x="170" y="93" fill="white" fontSize="10" fontWeight="bold" textAnchor="middle" fontFamily="sans-serif">404</text>

                {/* Stars */}
                <circle cx="30" cy="40" r="2" fill={isCyber ? "#22d3ee" : "#fbbf24"} />
                <circle cx="170" cy="35" r="3" fill={isCyber ? "#f43f5e" : "#fbbf24"} />
                <circle cx="45" cy="160" r="2.5" fill={isCyber ? "#38bdf8" : "#94a3b8"} />
                <circle cx="165" cy="170" r="2" fill={isCyber ? "#22d3ee" : "#94a3b8"} />
            </svg>
            <style>{`
                @keyframes floatAnimation {
                    0%, 100% { transform: translateY(0px) rotate(0deg); }
                    50% { transform: translateY(-10px) rotate(3deg); }
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
    svgFloat: {
        animation: "floatAnimation 4s ease-in-out infinite",
    },
};
