import {createBrowserRouter} from "react-router-dom";
import {routes} from "../consts/routes.js";
import MainPage from "../pages/MainPage/MainPage.jsx";
import Login from "../pages/LoginPage/Login.jsx";
import RegistrationForm from "../pages/RegistrationPage/RegisterPage.jsx";
import ProfilePage from "../pages/ProfilePage/ProfilePage.jsx";
import AllUsersPage from "../pages/AllUsersPage/allUsersPage.jsx";
import NotFoundPage from "../pages/NotFoundPage.jsx";
import React from "react";
import RequestsPage from "../pages/RequestsPage/RequestsPage.jsx";
import LayoutWithHeader from "./LayoutWithHeader.jsx";
import KeysPage from "../pages/KeysPage/KeysPage.jsx";
import ProtectedRoute from "./ProtectedRoute.jsx";

export const router = createBrowserRouter([
    {
        path: '/',
        element: <LayoutWithHeader/>,
        children:[
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
                element: <ProtectedRoute element={<ProfilePage/>} required={false}/>
            },
            {
                path: routes.users(),
                element: <ProtectedRoute element={<AllUsersPage/>} required={true}/>
            },
            {
                path: routes.requests(),
                element: <ProtectedRoute element={<RequestsPage/>} required={true}/>
            },
            {
                path: routes.keys(),
                element: <ProtectedRoute element={<KeysPage/>} required={true}/>
            },
            {
                path: '*',
                element: <NotFoundPage/>
            }
        ]
    }
])