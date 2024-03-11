import React from "react";
import {Navigate} from "react-router";
import {routes} from "../consts/routes.js";
import {Outlet} from "react-router-dom";

const ProtectedRoute = (isLoggedIn) => {
    return(
        <>
            {isLoggedIn ? <Outlet/> : <Navigate to={routes.login()}/>}
        </>
    )
}

export default ProtectedRoute;