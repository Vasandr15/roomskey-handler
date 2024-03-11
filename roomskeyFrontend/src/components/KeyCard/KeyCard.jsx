import React, {useState} from "react";
import {Button, Collapse, Space} from "antd";
import KeyCardChild from "./KeyCardChild.jsx";
import CustomModal from "../Modal/Modal.jsx";

export default function KeyCard({ isCollapsed, data, key }) {
    const child = <KeyCardChild booked={data.bookedTime}/>;
    const [isModalOpen, setIsModalOpen] = useState(false);

    const renderExtraButton = () => {
        if (isCollapsed) {
            return <Button type="primary" onClick={() => setIsModalOpen(true)}>Передать ключ</Button>;
        }
        return null;
    };

    console.log(data)

    return (
        <Space direction="vertical" style={{ marginBottom: "30px" }}>
            <Collapse
                className="collapseCard"
                size="large"
                collapsible={isCollapsed ? "disabled" : "header"}
                items={[
                    {
                        key: key,
                        label: `${data.building}-${data.room}`,
                        children: child,
                        extra: renderExtraButton()
                    },
                ]}
            />
            <CustomModal open={isModalOpen} setOpen={setIsModalOpen} />
        </Space>

    );
}
