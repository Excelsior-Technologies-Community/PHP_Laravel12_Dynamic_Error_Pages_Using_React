import React, { useState } from "react";
import Mascot500 from "../components/mascots/Mascot500";
import SnakeGame from "../components/games/SnakeGame";
import ErrorReportModal from "../components/ErrorReportModal";
import ThemeSwitcher from "../components/ThemeSwitcher";
import ErrorExtras from "../components/ErrorExtras";
import ErrorStats from "../components/ErrorStats";

export default function Error500() {
    const [theme, setTheme] = useState(() => {
        return localStorage.getItem("error_page_theme") || "clean";
    });
    const [playGame, setPlayGame] = useState(false);

    function handleThemeChange(newTheme) {
        setTheme(newTheme);
        localStorage.setItem("error_page_theme", newTheme);
    }

    return (
        <div style={getContainerStyle(theme)}>
            <div style={getBoxStyle(theme)}>
                {/* Theme Switcher Header */}
                <div style={{ display: "flex", justifyContent: "flex-end" }}>
                    <ThemeSwitcher currentTheme={theme} onChangeTheme={handleThemeChange} />
                </div>

                {!playGame ? (
                    <>
                        {/* Smoking Server Rack Mascot */}
                        <Mascot500 theme={theme} />

                        <h1 style={getTitleStyle(theme)}>500</h1>
                        <p style={getMessageStyle(theme)}>Server Core Malfunction</p>
                        <p style={getDescStyle(theme)}>
                            Our backend services suffered an unexpected exception. Our automated telemetry radar has flagged the issue for immediate developer triage.
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
                                🕹️ Play Server Snake
                            </button>
                        </div>

                        {/* 1-Click Incident Report Modal */}
                        <ErrorReportModal statusCode={500} theme={theme} />

                        {/* Random Tips & Auto Redirect */}
                        <ErrorExtras theme={theme} />

                        {/* Live Telemetry Radar */}
                        <ErrorStats theme={theme} />
                    </>
                ) : (
                    <div>
                        <SnakeGame onExit={() => setPlayGame(false)} theme={theme} />
                        <button
                            type="button"
                            onClick={() => setPlayGame(false)}
                            style={backBtn(theme)}
                        >
                            ← Back to 500 Details
                        </button>
                    </div>
                )}
            </div>
        </div>
    );
}

function getContainerStyle(theme) {
    if (theme === "cyberpunk") {
        return {
            display: "flex",
            justifyContent: "center",
            alignItems: "center",
            minHeight: "100vh",
            backgroundColor: "#050811",
            backgroundImage: "radial-gradient(#1e1b4b 1px, transparent 1px)",
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
            background: "linear-gradient(135deg, #f59e0b 0%, #dc2626 100%)",
            padding: "20px",
            fontFamily: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
            transition: "all 0.3s ease",
        };
    }
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
            boxShadow: "0 0 35px rgba(245, 158, 11, 0.25), inset 0 0 15px rgba(239, 68, 68, 0.1)",
            border: "1px solid #f59e0b",
        };
    }
    if (theme === "glass") {
        return {
            textAlign: "center",
            background: "rgba(255, 255, 255, 0.88)",
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
            color: "#f59e0b",
            margin: "0",
            lineHeight: "1",
            textShadow: "0 0 20px rgba(245, 158, 11, 0.6)",
            letterSpacing: "4px",
        };
    }
    if (theme === "glass") {
        return {
            fontSize: "80px",
            fontWeight: "900",
            color: "#b45309",
            margin: "0",
            lineHeight: "1",
            letterSpacing: "2px",
        };
    }
    return {
        fontSize: "80px",
        fontWeight: "800",
        color: "#d97706",
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
    background: theme === "cyberpunk" ? "#f59e0b" : "#d97706",
    color: "#ffffff",
    padding: "10px 20px",
    borderRadius: "8px",
    fontWeight: "bold",
    fontSize: "14px",
    border: "none",
    cursor: "pointer",
    boxShadow: theme === "cyberpunk" ? "0 0 15px rgba(245, 158, 11, 0.5)" : "none",
});

const gameBtn = (theme) => ({
    background: theme === "cyberpunk" ? "rgba(245, 158, 11, 0.15)" : "#f8fafc",
    color: theme === "cyberpunk" ? "#f59e0b" : "#0f172a",
    border: theme === "cyberpunk" ? "1px solid #f59e0b" : "1px solid #cbd5e1",
    padding: "10px 16px",
    borderRadius: "8px",
    fontWeight: "bold",
    fontSize: "14px",
    cursor: "pointer",
});

const backBtn = (theme) => ({
    background: "transparent",
    border: "none",
    color: theme === "cyberpunk" ? "#f59e0b" : "#d97706",
    fontWeight: "bold",
    fontSize: "13px",
    cursor: "pointer",
    marginTop: "10px",
});
