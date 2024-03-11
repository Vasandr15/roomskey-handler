import { Card, Col, Row, Typography, Select, Button, message } from 'antd';
import React, { useState } from "react";
import { roleSelection } from "../../consts/roleSelection.js";
import styles from './styles.module.css'
import {changeRole} from "../../API/changeRole.js";

const { Text } = Typography;

export default function UserCard(props) {
    const [editMode, setEditMode] = useState(false);
    const [selectedRole, setSelectedRole] = useState(props.role);
    const [messageApi, contextHolder] = message.useMessage();

    const notify = (type, message) => {
        messageApi.open({
            type: type,
            content: message,
        });
    }
    const handleRoleChange = (value) => {
        setSelectedRole(value);
    };

    const handleEditClick = () => {
        setEditMode(true);
    };

    const handleCancelClick = () =>{
        setEditMode(false);
        setSelectedRole(props.role);
    }

    const handleConfirmClick = () => {
        setEditMode(false);
        let response = changeRole(props.id, selectedRole);
        if (response){
            notify('success', 'Роль успешно обновлена')
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }
        else {
            notify('error', 'Что-то пошло не так')
        }
    };

    return (
        <>
            {contextHolder}
            <Card className={styles.userCard}>
                <Row>
                    <Col md={11}>
                        <div>
                            <p><Text strong>ФИО: </Text>{props.fullName}</p>
                            <p><Text strong>Телефон: </Text>{props.phoneNumber}</p>
                        </div>
                    </Col>
                    <Col md={11} align={'right'}>
                        <Select
                            disabled={!editMode}
                            options={roleSelection}
                            value={selectedRole}
                            onChange={handleRoleChange}
                            style={{width:'12rem'}}
                        />
                        <div style={{ marginTop: '10px' }}>
                            {editMode ? (
                                <>
                                    <Button type='primary' onClick={handleConfirmClick}>Подтвердить</Button>
                                    <Button style={{marginLeft: '10px'}} onClick={handleCancelClick}>Отменить</Button>
                                </>
                            ) : (
                                <Button onClick={handleEditClick}>Изменить роль</Button>
                            )}
                        </div>
                    </Col>
                </Row>
            </Card>
        </>
    );
};
