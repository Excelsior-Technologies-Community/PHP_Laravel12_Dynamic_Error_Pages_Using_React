import React, { useState } from "react";
import Mascot404 from "../components/mascots/Mascot404";
import DinoRunnerGame from "../components/games/DinoRunnerGame";
import ErrorReportModal from "../components/ErrorReportModal";
import ThemeSwitcher from "../components/ThemeSwitcher";
import ErrorExtras from "../components/ErrorExtras";
import ErrorStats from "../components/ErrorStats";

export default function Error404() {
    const [theme, setTheme] = useState(() => {
        return localStorage.getItem("error_page_theme") || "clean";
    });
    const [playGame, setPlayGame] = useState(false);

    function handleThemeChange(newTheme) {
        setTheme(newTheme);
        localStorage.setItem("error_page_theme", newTheme);
    }

    const isCyber = theme === "cyberpunk";
    const isGlass = theme === "glass";

    return (
        <div style={getContainerStyle(theme)}>
            <div style={getBoxStyle(theme)}>
                {/* Theme Switcher Header */}
                <div style={{ display: "flex", justifyContent: "flex-end" }}>
                    <ThemeSwitcher currentTheme={theme} onChangeTheme={handleThemeChange} />
                </div>

                {!playGame ? (
                    <>
                        {/* Animated 404 Mascot */}
                        <Mascot404 theme={theme} />

                        <h1 style={getTitleStyle(theme)}>404</h1>
                        <p style={getMessageStyle(theme)}>Lost in Digital Space</p>
                        <p style={getDescStyle(theme)}>
                            The coordinates you navigated to do not exist or have drifted into a black hole.
                        </p>

                        {/* Primary Action Buttons */}
                        <div style={buttonGroup}>
                            <a href="/" style={primaryBtn(theme)}>
                                🏠 Return Home
                            </a>
                            <button
                                type="button"
                                onClick={() => setPlayGame(true)}
                                style={gameBtn(theme)}
                            >
                                🕹️ Play Space Jumper
                            </button>
                        </div>

                        {/* 1-Click Incident Report Modal */}
                        <ErrorReportModal statusCode={404} theme={theme} />

                        {/* Random Tips & Auto Redirect */}
                        <ErrorExtras theme={theme} />

                        {/* Live Telemetry Radar */}
                        <ErrorStats theme={theme} />
                    </>
                ) : (
                    <div>
                        <DinoRunnerGame onExit={() => setPlayGame(false)} theme={theme} />
                        <button
                            type="button"
                            onClick={() => setPlayGame(false)}
                            style={backBtn(theme)}
                        >
                            ← Back to 404 Details
                        </button>
                    </div>
                )}
            </div>
        </div>
    );
}

// Styling helpers based on theme
function getContainerStyle(theme) {
    if (theme === "cyberpunk") {
        return {
            display: "flex",
            justifyContent: "center",
            alignItems: "center",
            minHeight: "100vh",
            backgroundColor: "#050811",
            backgroundImage: "radial-gradient(#0e182e 1px, transparent 1px)",
            backgroundSize: "20px 20px",
            padding: "20px",
            fontFamily: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
            transition: "all 0.3s ease",
        };
    }
    if (theme === "glass") {
        return {
            display: "flex",
            justifyContent: "center",
            alignItems: "center",
            minHeight: "100vh",
            background: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
            padding: "20px",
            fontFamily: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
            transition: "all 0.3s ease",
        };
    }
    // Clean
    return {
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        minHeight: "100vh",
        backgroundColor: "#f8f9fa",
        padding: "20px",
        fontFamily: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
        transition: "all 0.3s ease",
    };
}

function getBoxStyle(theme) {
    if (theme === "cyberpunk") {
        return {
            textAlign: "center",
            background: "rgba(10, 15, 29, 0.95)",
            color: "#f8fafc",
            padding: "35px 30px",
            borderRadius: "16px",
            width: "100%",
            maxWidth: "440px",
            boxShadow: "0 0 35px rgba(6, 182, 212, 0.25), inset 0 0 15px rgba(236, 72, 153, 0.1)",
            border: "1px solid #06b6d4",
        };
    }
    if (theme === "glass") {
        return {
            textAlign: "center",
            background: "rgba(255, 255, 255, 0.85)",
            backdropFilter: "blur(16px)",
            WebkitBackdropFilter: "blur(16px)",
            padding: "35px 30px",
            borderRadius: "20px",
            width: "100%",
            maxWidth: "440px",
            boxShadow: "0 20px 40px rgba(0, 0, 0, 0.2)",
            border: "1px solid rgba(255, 255, 255, 0.4)",
        };
    }
    // Clean
    return {
        textAlign: "center",
        background: "#ffffff",
        padding: "35px 30px",
        borderRadius: "16px",
        width: "100%",
        maxWidth: "440px",
        boxShadow: "0 10px 30px rgba(0, 0, 0, 0.08)",
        border: "1px solid #e2e8f0",
    };
}

function getTitleStyle(theme) {
    if (theme === "cyberpunk") {
        return {
            fontSize: "80px",
            fontWeight: "900",
            color: "#22d3ee",
            margin: "0",
            lineHeight: "1",
            textShadow: "0 0 20px rgba(34, 211, 238, 0.6)",
            letterSpacing: "4px",
        };
    }
    if (theme === "glass") {
        return {
            fontSize: "80px",
            fontWeight: "900",
            color: "#4f46e5",
            margin: "0",
            lineHeight: "1",
            letterSpacing: "2px",
        };
    }
    return {
        fontSize: "80px",
        fontWeight: "800",
        color: "#dc2626",
        margin: "0",
        lineHeight: "1",
    };
}

function getMessageStyle(theme) {
    return {
        fontSize: "22px",
        color: theme === "cyberpunk" ? "#f1f5f9" : "#1e293b",
        margin: "12px 0 6px",
        fontWeight: "bold",
    };
}

function getDescStyle(theme) {
    return {
        fontSize: "14px",
        color: theme === "cyberpunk" ? "#94a3b8" : "#64748b",
        margin: "0 0 20px",
        lineHeight: "1.5",
    };
}

const buttonGroup = {
    display: "flex",
    gap: "10px",
    justifyContent: "center",
    marginBottom: "8px",
    flexWrap: "wrap",
};

const primaryBtn = (theme) => ({
    display: "inline-block",
    textDecoration: "none",
    background: theme === "cyberpunk" ? "#06b6d4" : "#2563eb",
    color: "#ffffff",
    padding: "10px 20px",
    borderRadius: "8px",
    fontWeight: "bold",
    fontSize: "14px",
    border: "none",
    cursor: "pointer",
    boxShadow: theme === "cyberpunk" ? "0 0 15px rgba(6, 182, 212, 0.5)" : "none",
});

const gameBtn = (theme) => ({
    background: theme === "cyberpunk" ? "rgba(244, 63, 94, 0.15)" : "#f8fafc",
    color: theme === "cyberpunk" ? "#f43f5e" : "#0f172a",
    border: theme === "cyberpunk" ? "1px solid #f43f5e" : "1px solid #cbd5e1",
    padding: "10px 16px",
    borderRadius: "8px",
    fontWeight: "bold",
    fontSize: "14px",
    cursor: "pointer",
});

const backBtn = (theme) => ({
    background: "transparent",
    border: "none",
    color: theme === "cyberpunk" ? "#38bdf8" : "#2563eb",
    fontWeight: "bold",
    fontSize: "13px",
    cursor: "pointer",
    marginTop: "10px",
});
