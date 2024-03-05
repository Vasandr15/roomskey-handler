import {createBrowserRouter} from "react-router-dom";
import {routes} from "../consts/routes.js";
import MainPage from "../pages/MainPage/mainPage.jsx";
import Login from "../pages/LoginPage/Login.jsx";
import RegistrationForm from "../pages/RegistrationPage/RegisterPage.jsx";
import ProfilePage from "../pages/ProfilePage/ProfilePage.jsx";
import AllUsersPage from "../pages/AllUsersPage/allUsersPage.jsx";
import NotFoundPage from "../pages/NotFoundPage.jsx";
import React from "react";

export const router = createBrowserRouter([
    {
        path: routes.root(),
        element:<MainPage/>
    },
    {
        path: routes.login(),
        element: <Login/>
    },
    {
        path: routes.registration(),
        element: <RegistrationForm/>
    },
    {
        path: routes.profile(),
        element: <ProfilePage/>
    },
    {
        path: routes.users(),
        element: <AllUsersPage/>
    },
    {
        path: '*',
        element: <NotFoundPage/>
    }
])