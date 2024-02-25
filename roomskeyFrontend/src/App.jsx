import {Route, Router, Routes} from "react-router";
import {routes} from './consts/routes.js';
import NotFoundPage from "./pages/NotFoundPage.jsx";
import RequestsPage from "./pages/RequestsPage.jsx";
import {BrowserRouter} from "react-router-dom";
export default function App() {
    return (
        <>
            <BrowserRouter>
                <Routes>
                    <Route path="requests" element={<RequestsPage/>}></Route>
                    <Route path="*" element={<NotFoundPage/>}></Route>
                </Routes>
            </BrowserRouter>
        </>
    );
}