import { useEffect, useState } from "react";

const tips = [
    "Double-check the URL and try again.",
    "Return to the home page to continue browsing.",
    "Refresh the page if you think this is temporary.",
    "Use the navigation menu to find what you need.",
    "Contact support if the problem continues.",
];

export default function ErrorExtras() {

    const [seconds, setSeconds] = useState(5);

    const [cancelled, setCancelled] = useState(false);

    const [tip] = useState(
        tips[Math.floor(Math.random() * tips.length)]
    );

    useEffect(() => {

        if (cancelled) return;

        if (seconds <= 0) {

            window.location.href = "/";

            return;

        }

        const timer = setTimeout(() => {

            setSeconds(prev => prev - 1);

        }, 1000);

        return () => clearTimeout(timer);

    }, [seconds, cancelled]);

    return (

        <div style={styles.container}>

            <div style={styles.tipBox}>

                💡 <strong>Helpful Tip</strong>

                <p style={{ marginTop: 10 }}>{tip}</p>

            </div>

            {

                !cancelled &&

                <p style={styles.countdown}>

                    Redirecting to Home in

                    <strong> {seconds} </strong>

                    second{seconds !== 1 ? "s" : ""}

                </p>

            }

            {

                cancelled &&

                <p style={styles.cancelled}>

                    Auto Redirect Cancelled

                </p>

            }

            {

                !cancelled &&

                <button

                    style={styles.button}

                    onClick={() => setCancelled(true)}

                >

                    Cancel Redirect

                </button>

            }

        </div>

    );

}

const styles = {

    container: {

        marginTop: 25,

    },

    tipBox: {

        background: "#f8f9fa",

        padding: 15,

        borderRadius: 8,

        color: "#555",

        fontSize: 14,

        marginBottom: 20,

        border: "1px solid #ddd",

    },

    countdown: {

        color: "#444",

        marginBottom: 15,

        fontSize: 15,

    },

    cancelled: {

        color: "red",

        fontWeight: "bold",

        marginBottom: 15,

    },

    button: {

        padding: "10px 22px",

        border: "none",

        borderRadius: 8,

        background: "#343a40",

        color: "white",

        cursor: "pointer",

        fontWeight: "600",

    }

};