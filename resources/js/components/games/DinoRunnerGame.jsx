import React, { useEffect, useRef, useState } from "react";

export default function DinoRunnerGame({ onExit, theme = "clean" }) {
    const canvasRef = useRef(null);
    const [score, setScore] = useState(0);
    const [highScore, setHighScore] = useState(() => {
        return parseInt(localStorage.getItem("error_dino_high") || "0", 10);
    });
    const [gameOver, setGameOver] = useState(false);
    const [gameStarted, setGameStarted] = useState(false);

    const isCyber = theme === "cyberpunk";

    useEffect(() => {
        const canvas = canvasRef.current;
        if (!canvas) return;
        const ctx = canvas.getContext("2d");

        let animationFrameId;
        let currentScore = 0;
        let isJumping = false;
        let jumpVelocity = 0;
        const gravity = 0.65;

        // Player Astronaut
        const player = {
            x: 40,
            y: 120,
            width: 24,
            height: 32,
            groundY: 120,
        };

        // Obstacles (Asteroids / Space Rocks)
        let obstacles = [];
        let frameCount = 0;

        function spawnObstacle() {
            const height = 24 + Math.random() * 16;
            obstacles.push({
                x: canvas.width + 20,
                y: player.groundY + (32 - height),
                width: 18,
                height: height,
            });
        }

        function handleJump() {
            if (!player.isJumping) {
                player.isJumping = true;
                jumpVelocity = -11;
            }
        }

        function onKeyDown(e) {
            if (e.code === "Space" || e.code === "ArrowUp") {
                e.preventDefault();
                handleJump();
            }
        }

        window.addEventListener("keydown", onKeyDown);

        function gameLoop() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Ground line
            ctx.strokeStyle = isCyber ? "#06b6d4" : "#94a3b8";
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(0, player.groundY + 32);
            ctx.lineTo(canvas.width, player.groundY + 32);
            ctx.stroke();

            // Stars in background
            ctx.fillStyle = isCyber ? "#22d3ee" : "#cbd5e1";
            for (let i = 0; i < 6; i++) {
                const sx = (frameCount * 0.5 + i * 60) % canvas.width;
                ctx.fillRect(canvas.width - sx, 20 + (i * 15) % 80, 2, 2);
            }

            // Player physics
            if (player.isJumping) {
                player.y += jumpVelocity;
                jumpVelocity += gravity;
                if (player.y >= player.groundY) {
                    player.y = player.groundY;
                    player.isJumping = false;
                    jumpVelocity = 0;
                }
            }

            // Draw Player (Astronaut Runner)
            ctx.fillStyle = isCyber ? "#38bdf8" : "#2563eb";
            ctx.fillRect(player.x, player.y, player.width, player.height);
            // Visor
            ctx.fillStyle = isCyber ? "#f43f5e" : "#f59e0b";
            ctx.fillRect(player.x + 12, player.y + 6, 8, 8);

            // Update obstacles
            frameCount++;
            if (frameCount % 85 === 0) {
                spawnObstacle();
            }

            for (let i = 0; i < obstacles.length; i++) {
                const obs = obstacles[i];
                obs.x -= 4.2;

                // Draw asteroid
                ctx.fillStyle = isCyber ? "#ec4899" : "#dc2626";
                ctx.fillRect(obs.x, obs.y, obs.width, obs.height);

                // Collision detection
                if (
                    player.x < obs.x + obs.width &&
                    player.x + player.width > obs.x &&
                    player.y < obs.y + obs.height &&
                    player.y + player.height > obs.y
                ) {
                    setGameOver(true);
                    if (currentScore > highScore) {
                        setHighScore(currentScore);
                        localStorage.setItem("error_dino_high", currentScore.toString());
                    }
                    return; // Stop animation loop
                }
            }

            // Filter passed obstacles
            obstacles = obstacles.filter((obs) => obs.x + obs.width > 0);

            // Increase score
            currentScore += 1;
            setScore(Math.floor(currentScore / 5));

            animationFrameId = requestAnimationFrame(gameLoop);
        }

        animationFrameId = requestAnimationFrame(gameLoop);

        return () => {
            cancelAnimationFrame(animationFrameId);
            window.removeEventListener("keydown", onKeyDown);
        };
    }, [gameOver]);

    function restartGame() {
        setGameOver(false);
        setScore(0);
    }

    return (
        <div style={containerStyle(isCyber)}>
            <div style={headerStyle}>
                <span style={{ fontWeight: "bold", fontSize: "14px" }}>
                    🚀 404 Space Jumper
                </span>
                <div style={{ fontSize: "12px" }}>
                    Score: <strong>{score}</strong> | High: <strong>{highScore}</strong>
                </div>
            </div>

            <div style={{ position: "relative" }}>
                <canvas
                    ref={canvasRef}
                    width={340}
                    height={160}
                    style={canvasStyle(isCyber)}
                    onClick={() => {
                        const evt = new KeyboardEvent("keydown", { code: "Space" });
                        window.dispatchEvent(evt);
                    }}
                />

                {gameOver && (
                    <div style={gameOverOverlay}>
                        <p style={{ margin: "0 0 8px", fontWeight: "bold", fontSize: "15px" }}>
                            💥 Crash! Game Over
                        </p>
                        <p style={{ margin: "0 0 12px", fontSize: "13px" }}>
                            Final Score: <strong>{score}</strong>
                        </p>
                        <button onClick={restartGame} style={primaryBtn(isCyber)}>
                            🔄 Play Again
                        </button>
                    </div>
                )}
            </div>

            <div style={footerStyle}>
                <span style={{ fontSize: "11px", opacity: 0.8 }}>
                    💡 Tap or press Space / Up to jump
                </span>
                <button onClick={onExit} style={exitBtn}>
                    ✖ Close Game
                </button>
            </div>
        </div>
    );
}

const containerStyle = (isCyber) => ({
    background: isCyber ? "#090d16" : "#f1f5f9",
    border: isCyber ? "1px solid #06b6d4" : "1px solid #cbd5e1",
    borderRadius: "12px",
    padding: "12px",
    maxWidth: "360px",
    margin: "15px auto",
    color: isCyber ? "#e2e8f0" : "#1e293b",
    boxShadow: isCyber ? "0 0 15px rgba(6, 182, 212, 0.25)" : "0 4px 12px rgba(0,0,0,0.06)",
});

const headerStyle = {
    display: "flex",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: "8px",
};

const canvasStyle = (isCyber) => ({
    display: "block",
    background: isCyber ? "#020617" : "#ffffff",
    borderRadius: "8px",
    border: isCyber ? "1px solid #1e293b" : "1px solid #e2e8f0",
    cursor: "pointer",
    width: "100%",
});

const gameOverOverlay = {
    position: "absolute",
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    background: "rgba(0, 0, 0, 0.78)",
    color: "#ffffff",
    display: "flex",
    flexDirection: "column",
    justifyContent: "center",
    alignItems: "center",
    borderRadius: "8px",
};

const footerStyle = {
    display: "flex",
    justifyContent: "space-between",
    alignItems: "center",
    marginTop: "8px",
};

const primaryBtn = (isCyber) => ({
    background: isCyber ? "#06b6d4" : "#2563eb",
    color: "#fff",
    border: "none",
    padding: "6px 14px",
    borderRadius: "6px",
    cursor: "pointer",
    fontWeight: "bold",
    fontSize: "12px",
});

const exitBtn = {
    background: "transparent",
    border: "none",
    color: "#ef4444",
    cursor: "pointer",
    fontSize: "11px",
    fontWeight: "bold",
};
