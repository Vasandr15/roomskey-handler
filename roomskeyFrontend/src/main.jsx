import React from 'react'
import ReactDOM from 'react-dom/client'
import './index.css'
import { RouterProvider} from "react-router-dom";
import RegistrationForm from "./pages/RegistrationPage/RegisterPage.jsx";


ReactDOM.createRoot(document.getElementById('root')).render(
    <React.StrictMode>
        <RegistrationForm/>
    </React.StrictMode>,
)
