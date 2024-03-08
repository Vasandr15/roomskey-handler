import React from 'react';
import { Outlet } from 'react-router-dom';
import HeaderSection from '../components/Header/Header.jsx'

const LayoutWithHeader = () => (
    <>
        <HeaderSection />
        <Outlet />
    </>
);

export default LayoutWithHeader;
