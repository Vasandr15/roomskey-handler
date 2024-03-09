import React from 'react';
import { Modal, Button } from 'antd';

// Rename your component to avoid conflict with the 'Modal' from 'antd'
const CustomModal = ({ open, setOpen }) => {
    const handleCancel = () => {
        setOpen(false);
    };

    const customFooter = [
        <Button key="back" onClick={handleCancel}>
            Cancel
        </Button>,
    ];
    return (
        <>
            <Modal
                title="Кому"
                open={open}
                onCancel={handleCancel}
                footer={customFooter}
            >

            </Modal>
        </>
    );
};

export default CustomModal;
