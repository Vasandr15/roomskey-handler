import React, {useState} from "react";
import {Button, Collapse, Space} from "antd";
import KeyCardChild from "./KeyCardChild.jsx";
import CustomModal from "../Modal/Modal.jsx";

export default function KeyCard({ isCollapsed }) {
    const child = <KeyCardChild />;
    const [isModalOpen, setIsModalOpen] = useState(false);

    const renderExtraButton = () => {
        if (isCollapsed) {
            return <Button type="primary" onClick={() => setIsModalOpen(true)}>Передать ключ</Button>;
        }
        return null;
    };
    return (
        <Space direction="vertical" style={{ marginBottom: "30px" }}>
            <Collapse
                className="collapseCard"
                size="large"
                activeKey={isCollapsed ? [] : ["1"]}
                items={[
                    {
                        key: "1",
                        label: "Ключ 2-218",
                        children: child,
                        extra: renderExtraButton()
                    },
                ]}
            />
            <CustomModal open={isModalOpen} setOpen={setIsModalOpen} />
        </Space>

    );
}
