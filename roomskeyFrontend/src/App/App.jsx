import React from 'react'
import '../index.css'
import {RouterProvider} from "react-router-dom";
import {router} from "./router.jsx";


export const App = () => <RouterProvider router={router} />

