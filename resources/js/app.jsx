// resources/js/app.jsx
import React from "react";
import ReactDOM from "react-dom/client";
import Error404 from "./pages/Error404";
import Error403 from "./pages/Error403";
import Error500 from "./pages/Error500";

const container = document.getElementById("app");

if (container) {
    const page = container.dataset.page;
    const root = ReactDOM.createRoot(container);

    switch (page) {
        case "404":
            root.render(<Error404 />);
            break;
        case "403":
            root.render(<Error403 />);
            break;
        case "500":
            root.render(<Error500 />);
            break;
        default:
            root.render(<Error500 />);
    }
}