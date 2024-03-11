import React from 'react'
import ReactDOM from 'react-dom/client'
import './index.css'
import { RouterProvider} from "react-router-dom";
import AllUsersPage from "./pages/AllUsersPage/allUsersPage.jsx";


ReactDOM.createRoot(document.getElementById('root')).render(
    <React.StrictMode>
        <AllUsersPage/>
    </React.StrictMode>,
)
