import React from 'react';
import { RouterProvider } from "react-router-dom";
import { router } from "./router.jsx";
import HeaderSection from "../components/Header/Header.jsx";
import { Layout } from "antd";

const { Content } = Layout;

export const App = () => (

    <RouterProvider router={router}/>
);
