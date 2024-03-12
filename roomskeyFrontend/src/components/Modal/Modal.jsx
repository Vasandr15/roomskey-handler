import React, { useEffect, useState } from 'react';
import {Modal, Button, Input} from 'antd';
import ModalUserCard from "../KeyCard/ModalUserCard.jsx";
import { getUsers } from "../../API/getUsers.js";
import {giveKey} from "../../API/giveKey.js";

const CustomModal = ({ open, setOpen, keyId}) => {
    const [usersData, setUsersData] = useState([]);
    const [searchQuery, setSearchQuery] = useState("");
    const [filteredUsersData, setFilteredUsersData] = useState([]);

    const handleSearchInputChange = (event) => {
        const query = event.target.value.toLowerCase();
        setSearchQuery(query);
        const filteredData = usersData.filter(user =>
            user.name.toLowerCase().includes(query)
        );
        setFilteredUsersData(filteredData);
    };

    const handleCancel = () => {
        setOpen(false);
    };

    const customFooter = [
        <Button key="back" onClick={handleCancel}>
            Cancel
        </Button>,
    ];

    useEffect(() => {
        const fetchData = async () => {
            try {
                const { users } = await getUsers();
                setUsersData(users);
            } catch (error) {
                console.error('Error fetching user data:', error);
            }
        };
        fetchData();
    }, []);

    const handleUserCardClick = (user) => {
        let response = giveKey(user.id, keyId )
        console.log('Clicked on user card:', user);
    };

    return (
        <>
            <Modal
                title="Кому"
                visible={open}
                onCancel={handleCancel}
                footer={customFooter}
            >
                <Input
                    type="text"
                    placeholder="Поиск по имени"
                    value={searchQuery}
                    onChange={handleSearchInputChange}
                    style={{marginBottom: '10px'}}
                />
                {filteredUsersData.map((user, index) => (
                    <div key={index} onClick={() => handleUserCardClick(user)}>
                        <ModalUserCard
                            name={user.name}
                            role={user.role === "public" ? "Студент" : user.role}
                        />
                    </div>
                ))}
            </Modal>
        </>
    );
};

export default CustomModal;