import React from "react";
import { Navigate } from "react-router";
import { routes } from "../consts/routes.js";

const ProtectedRoute = (props) => {
    const { element, required } = props;
    const isAuth = localStorage.getItem('token') !== null;
    const role = localStorage.getItem('role');

    if (!isAuth) {
        return <Navigate to={routes.login()} />;
    }

    if (required && !(role === 'dean' || role === 'admin')) {
        return <Navigate to={routes.root()} />;
    }

    return element;
}

export default ProtectedRoute;
