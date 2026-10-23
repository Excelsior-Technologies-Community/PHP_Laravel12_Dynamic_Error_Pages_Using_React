import React from "react";

export default function ThemeSwitcher({ currentTheme, onChangeTheme }) {
    const themes = [
        { id: "clean", label: "☀️ Clean" },
        { id: "cyberpunk", label: "👾 Cyberpunk" },
        { id: "glass", label: "🪟 Glass" },
    ];

    return (
        <div style={containerStyle(currentTheme)}>
            <span style={{ fontSize: "11px", fontWeight: "bold", marginRight: "6px", opacity: 0.7 }}>
                Theme:
            </span>
            {themes.map((t) => {
                const isActive = currentTheme === t.id;
                return (
                    <button
                        key={t.id}
                        type="button"
                        onClick={() => onChangeTheme(t.id)}
                        style={btnStyle(isActive, currentTheme)}
                    >
                        {t.label}
                    </button>
                );
            })}
        </div>
    );
}

const containerStyle = (theme) => ({
    display: "inline-flex",
    alignItems: "center",
    padding: "3px 6px",
    borderRadius: "20px",
    background: theme === "cyberpunk" ? "rgba(15, 23, 42, 0.85)" : "rgba(241, 245, 249, 0.9)",
    border: theme === "cyberpunk" ? "1px solid #1e293b" : "1px solid #e2e8f0",
    marginBottom: "14px",
    boxShadow: "0 2px 6px rgba(0,0,0,0.05)",
});

const btnStyle = (isActive, currentTheme) => {
    if (isActive) {
        return {
            background: currentTheme === "cyberpunk" ? "#06b6d4" : "#2563eb",
            color: "#ffffff",
            border: "none",
            borderRadius: "14px",
            padding: "3px 10px",
            fontSize: "11px",
            fontWeight: "bold",
            cursor: "pointer",
            marginRight: "3px",
            boxShadow: currentTheme === "cyberpunk" ? "0 0 8px rgba(6, 182, 212, 0.5)" : "none",
        };
    }
    return {
        background: "transparent",
        color: currentTheme === "cyberpunk" ? "#94a3b8" : "#64748b",
        border: "none",
        borderRadius: "14px",
        padding: "3px 8px",
        fontSize: "11px",
        cursor: "pointer",
        marginRight: "3px",
    };
};
