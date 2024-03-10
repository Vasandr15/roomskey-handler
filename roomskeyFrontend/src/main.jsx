import React from 'react'
import ReactDOM from 'react-dom/client'
import './index.css'
import { RouterProvider} from "react-router-dom";
import ProfilePage from "./pages /ProfilePage/ProfilePage.jsx";


ReactDOM.createRoot(document.getElementById('root')).render(
    <React.StrictMode>
        <ProfilePage/>
    </React.StrictMode>,
)
