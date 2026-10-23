import React, { useEffect, useRef, useState } from "react";

const GRID_SIZE = 15;
const CELL_SIZE = 18;

export default function SnakeGame({ onExit, theme = "clean" }) {
    const canvasRef = useRef(null);
    const [score, setScore] = useState(0);
    const [highScore, setHighScore] = useState(() => {
        return parseInt(localStorage.getItem("error_snake_high") || "0", 10);
    });
    const [gameOver, setGameOver] = useState(false);
    const isCyber = theme === "cyberpunk";

    const directionRef = useRef({ x: 1, y: 0 });
    const snakeRef = useRef([
        { x: 5, y: 7 },
        { x: 4, y: 7 },
        { x: 3, y: 7 },
    ]);
    const foodRef = useRef({ x: 10, y: 7 });

    function spawnFood() {
        foodRef.current = {
            x: Math.floor(Math.random() * GRID_SIZE),
            y: Math.floor(Math.random() * GRID_SIZE),
        };
    }

    useEffect(() => {
        function handleKeyDown(e) {
            const dir = directionRef.current;
            if (e.key === "ArrowUp" && dir.y === 0) {
                directionRef.current = { x: 0, y: -1 };
                e.preventDefault();
            } else if (e.key === "ArrowDown" && dir.y === 0) {
                directionRef.current = { x: 0, y: 1 };
                e.preventDefault();
            } else if (e.key === "ArrowLeft" && dir.x === 0) {
                directionRef.current = { x: -1, y: 0 };
                e.preventDefault();
            } else if (e.key === "ArrowRight" && dir.x === 0) {
                directionRef.current = { x: 1, y: 0 };
                e.preventDefault();
            }
        }

        window.addEventListener("keydown", handleKeyDown);
        return () => window.removeEventListener("keydown", handleKeyDown);
    }, []);

    useEffect(() => {
        if (gameOver) return;

        const canvas = canvasRef.current;
        if (!canvas) return;
        const ctx = canvas.getContext("2d");

        const interval = setInterval(() => {
            const snake = [...snakeRef.current];
            const head = {
                x: snake[0].x + directionRef.current.x,
                y: snake[0].y + directionRef.current.y,
            };

            // Wall collision
            if (head.x < 0 || head.x >= GRID_SIZE || head.y < 0 || head.y >= GRID_SIZE) {
                setGameOver(true);
                return;
            }

            // Self collision
            for (let segment of snake) {
                if (segment.x === head.x && segment.y === head.y) {
                    setGameOver(true);
                    return;
                }
            }

            snake.unshift(head);

            // Food collision (Server Packet collected)
            if (head.x === foodRef.current.x && head.y === foodRef.current.y) {
                setScore((prev) => {
                    const newScore = prev + 10;
                    if (newScore > highScore) {
                        setHighScore(newScore);
                        localStorage.setItem("error_snake_high", newScore.toString());
                    }
                    return newScore;
                });
                spawnFood();
            } else {
                snake.pop();
            }

            snakeRef.current = snake;

            // Render
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Draw grid background
            ctx.fillStyle = isCyber ? "#020617" : "#ffffff";
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Draw Server Packet (Food)
            ctx.fillStyle = "#22c55e";
            ctx.fillRect(
                foodRef.current.x * CELL_SIZE + 2,
                foodRef.current.y * CELL_SIZE + 2,
                CELL_SIZE - 4,
                CELL_SIZE - 4
            );

            // Draw Snake (Data Stream)
            snake.forEach((part, i) => {
                ctx.fillStyle = i === 0 ? (isCyber ? "#f43f5e" : "#ef4444") : (isCyber ? "#06b6d4" : "#3b82f6");
                ctx.fillRect(
                    part.x * CELL_SIZE + 1,
                    part.y * CELL_SIZE + 1,
                    CELL_SIZE - 2,
                    CELL_SIZE - 2
                );
            });
        }, 130);

        return () => clearInterval(interval);
    }, [gameOver, isCyber, highScore]);

    function restart() {
        snakeRef.current = [
            { x: 5, y: 7 },
            { x: 4, y: 7 },
            { x: 3, y: 7 },
        ];
        directionRef.current = { x: 1, y: 0 };
        spawnFood();
        setScore(0);
        setGameOver(false);
    }

    return (
        <div style={containerStyle(isCyber)}>
            <div style={headerStyle}>
                <span style={{ fontWeight: "bold", fontSize: "14px" }}>
                    🐍 Server Packet Recovery Snake
                </span>
                <div style={{ fontSize: "12px" }}>
                    Packets: <strong>{score}</strong> | High: <strong>{highScore}</strong>
                </div>
            </div>

            <div style={{ position: "relative", display: "flex", justifyContent: "center" }}>
                <canvas
                    ref={canvasRef}
                    width={GRID_SIZE * CELL_SIZE}
                    height={GRID_SIZE * CELL_SIZE}
                    style={canvasStyle(isCyber)}
                />

                {gameOver && (
                    <div style={gameOverOverlay}>
                        <p style={{ margin: "0 0 6px", fontWeight: "bold", fontSize: "14px" }}>
                            ⚠️ Server Packet Collision!
                        </p>
                        <p style={{ margin: "0 0 10px", fontSize: "12px" }}>
                            Reboot Score: <strong>{score}</strong>
                        </p>
                        <button onClick={restart} style={primaryBtn(isCyber)}>
                            🔄 Reboot & Retry
                        </button>
                    </div>
                )}
            </div>

            {/* Mobile / Click D-Pad */}
            <div style={dpadWrapper}>
                <button
                    onClick={() => {
                        if (directionRef.current.y === 0) directionRef.current = { x: 0, y: -1 };
                    }}
                    style={dpadBtn(isCyber)}
                >
                    ▲
                </button>
                <div style={{ display: "flex", gap: "6px" }}>
                    <button
                        onClick={() => {
                            if (directionRef.current.x === 0) directionRef.current = { x: -1, y: 0 };
                        }}
                        style={dpadBtn(isCyber)}
                    >
                        ◀
                    </button>
                    <button
                        onClick={() => {
                            if (directionRef.current.y === 0) directionRef.current = { x: 0, y: 1 };
                        }}
                        style={dpadBtn(isCyber)}
                    >
                        ▼
                    </button>
                    <button
                        onClick={() => {
                            if (directionRef.current.x === 0) directionRef.current = { x: 1, y: 0 };
                        }}
                        style={dpadBtn(isCyber)}
                    >
                        ▶
                    </button>
                </div>
            </div>

            <div style={footerStyle}>
                <span style={{ fontSize: "11px", opacity: 0.8 }}>
                    💡 Collect green data packets to restore server
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
    border: isCyber ? "1px solid #f43f5e" : "1px solid #cbd5e1",
    borderRadius: "12px",
    padding: "12px",
    maxWidth: "340px",
    margin: "15px auto",
    color: isCyber ? "#e2e8f0" : "#1e293b",
    boxShadow: isCyber ? "0 0 15px rgba(244, 63, 94, 0.25)" : "0 4px 12px rgba(0,0,0,0.06)",
});

const headerStyle = {
    display: "flex",
    justifyContent: "space-between",
    alignItems: "center",
    marginBottom: "8px",
};

const canvasStyle = (isCyber) => ({
    display: "block",
    borderRadius: "8px",
    border: isCyber ? "1px solid #334155" : "1px solid #e2e8f0",
});

const gameOverOverlay = {
    position: "absolute",
    top: 0,
    left: 0,
    right: 0,
    bottom: 0,
    background: "rgba(0, 0, 0, 0.82)",
    color: "#ffffff",
    display: "flex",
    flexDirection: "column",
    justifyContent: "center",
    alignItems: "center",
    borderRadius: "8px",
};

const dpadWrapper = {
    display: "flex",
    flexDirection: "column",
    alignItems: "center",
    gap: "4px",
    marginTop: "8px",
};

const dpadBtn = (isCyber) => ({
    background: isCyber ? "#1e293b" : "#e2e8f0",
    color: isCyber ? "#22d3ee" : "#334155",
    border: "none",
    width: "36px",
    height: "28px",
    borderRadius: "4px",
    cursor: "pointer",
    fontSize: "12px",
    fontWeight: "bold",
});

const footerStyle = {
    display: "flex",
    justifyContent: "space-between",
    alignItems: "center",
    marginTop: "8px",
};

const primaryBtn = (isCyber) => ({
    background: isCyber ? "#f43f5e" : "#2563eb",
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
