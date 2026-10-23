import React from "react";

export default function Mascot403({ theme = "clean" }) {
    const isCyber = theme === "cyberpunk";

    return (
        <div style={styles.wrapper}>
            <svg
                width="160"
                height="160"
                viewBox="0 0 200 200"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                style={styles.svgShield}
            >
                {/* Security Shield Backdrop */}
                <path
                    d="M100 20 L160 50 C160 115 100 170 100 170 C100 170 40 115 40 50 Z"
                    fill={isCyber ? "#1e1b4b" : "#fef2f2"}
                    stroke={isCyber ? "#ec4899" : "#ef4444"}
                    strokeWidth="4"
                />

                {/* Cyber padlock body */}
                <rect
                    x="75"
                    y="85"
                    width="50"
                    height="42"
                    rx="8"
                    fill={isCyber ? "#0f172a" : "#dc2626"}
                    stroke={isCyber ? "#06b6d4" : "#b91c1c"}
                    strokeWidth="3"
                />

                {/* Padlock Shackle */}
                <path
                    d="M85 85 V65 C85 55 115 55 115 65 V85"
                    stroke={isCyber ? "#22d3ee" : "#7f1d1d"}
                    strokeWidth="6"
                    strokeLinecap="round"
                    fill="none"
                />

                {/* Security Keyhole & Laser Eye */}
                <circle cx="100" cy="102" r="6" fill={isCyber ? "#22d3ee" : "#ffffff"} />
                <path d="M97 106 L95 118 H105 L103 106 Z" fill={isCyber ? "#22d3ee" : "#ffffff"} />

                {/* Scanning Laser Beam */}
                <line x1="60" y1="105" x2="140" y2="105" stroke={isCyber ? "#f43f5e" : "#ef4444"} strokeWidth="2" strokeDasharray="4 2" opacity="0.8" />

                {/* Warning Cross Icons */}
                <circle cx="100" cy="145" r="4" fill={isCyber ? "#f43f5e" : "#ef4444"} />
                <text x="100" y="160" fill={isCyber ? "#ec4899" : "#991b1b"} fontSize="10" fontWeight="bold" textAnchor="middle" fontFamily="sans-serif">LOCKED</text>
            </svg>
            <style>{`
                @keyframes pulseShield {
                    0%, 100% { transform: scale(1); filter: drop-shadow(0 0 4px rgba(239, 68, 68, 0.4)); }
                    50% { transform: scale(1.03); filter: drop-shadow(0 0 12px rgba(236, 72, 153, 0.7)); }
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
    svgShield: {
        animation: "pulseShield 3s ease-in-out infinite",
    },
};
