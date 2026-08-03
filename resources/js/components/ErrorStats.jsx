import { useEffect, useState } from "react";

export default function ErrorStats() {

    const [stats, setStats] = useState(null);

    useEffect(() => {

        fetch("/error-stats")
            .then(response => response.json())
            .then(data => setStats(data))
            .catch(error => console.log(error));

    }, []);


    if (!stats) {
        return null;
    }


    return (
        <div style={styles.container}>

            <h4>Error Statistics</h4>

            <p>
                Total Errors: <strong>{stats.total}</strong>
            </p>

            <p>
                404 Errors: <strong>{stats.error404}</strong>
            </p>

            <p>
                403 Errors: <strong>{stats.error403}</strong>
            </p>

            <p>
                500 Errors: <strong>{stats.error500}</strong>
            </p>

        </div>
    );
}


const styles = {

    container: {

        marginTop: 25,
        padding: 15,
        background: "#f8f9fa",
        borderRadius: 8,
        fontSize: 14

    }

};