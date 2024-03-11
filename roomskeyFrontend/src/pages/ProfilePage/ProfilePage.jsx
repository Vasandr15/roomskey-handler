import React, { useState, useEffect } from 'react';
import { Card, Row, Col, Avatar, Button, Typography, Input, Space, Form, message, Flex } from 'antd';
import { UserOutlined, EditFilled } from '@ant-design/icons';
import styles from './profile.module.css';
import { MaskedInput } from "antd-mask-input";
import { getProfile } from "../../API/getProfile.js";
import { editUser } from "../../API/editUser.js";
import { Validations } from "../../consts/validations.js";
import {logOutUser} from "../../API/logOutUser.js";
import {useNavigate} from "react-router";
import {routes} from "../../consts/routes.js";

const { Title } = Typography;
const { useForm } = Form;
const ProfilePage = () => {
    const [logOutLoading, setLoadingLogout] = useState(false);
    const [saveLoading, setLoadingSave] = useState(false);
    const [userInfo, setUserInfo] = useState(null);
    const [form] = useForm();
    const [isEditing, setIsEditing] = useState(false);
    const [messageApi, contextHolder] = message.useMessage();
    const navigate = useNavigate();
    useEffect(() => {
        const fetchData = async () => {
            try {
                const data = await getProfile();
                setUserInfo(data);
            } catch (error) {
                console.error('Error fetching profile:', error);
            }
        };
        fetchData();
    }, []);

    const logOut = async () => {
        setLoadingLogout(true);
        setTimeout(() => {
            setLoadingLogout(false);
        }, 1000);
        let response = await logOutUser();
        if (response) {

            notify('success',
                'Вы успешно вышли')
            localStorage.removeItem('token');
            localStorage.removeItem('role');
            setTimeout(() => {
                navigate(routes.login())
            }, 1000);

        }
        else {
            notify('error',
                'Не удалось выйти')
        }
    };

    const onFinish = async (values) => {
        setLoadingSave(true);
        console.log(values);
        let response = editUser(values)
        if (response) {
            notify('success', 'Данные профиля успешно обновлены');
        }
        else {
            notify('error', 'Произошла ошибка при изменеии данных');
        }
        setTimeout(() => {
            setLoadingSave(false);
            setIsEditing(false);
            setUserInfo(values);

        }, 1000);
    };

    const notify = (type, message) => {
        messageApi.open({
            type: type,
            content: message,
        });
    }

    const handleEdit = () => {
        setIsEditing(true);
        form.setFieldsValue(userInfo);
    };

    const handleCancelEdit = () =>{
        form.setFieldsValue(userInfo);
        setIsEditing(false)
    }

    return (
        <Row justify="center">
            {contextHolder}
            <Col md={13}>
                <Card className={styles.card} md={11}>
                    <Row align="middle">
                        <Col flex="100px">
                            <Avatar size={120} className={styles.avatar} icon={<UserOutlined />} />
                        </Col>
                        <Col flex="auto" style={{ marginLeft: '50px' }}>
                            {userInfo && (
                                <Form
                                    form={form}
                                    onFinish={onFinish}
                                    initialValues={userInfo}
                                    style={{ width: '100%' }}
                                    layout="vertical"
                                >
                                    <Form.Item name="name" label="Фио" rules={Validations.editNameValidation()}>
                                        <Input disabled={!isEditing} />
                                    </Form.Item>
                                    <Form.Item name="phone" label="Номер телефона" rules={Validations.phoneValidation()}>
                                        <MaskedInput mask={"+7 (000) 000-00-00"} disabled={!isEditing} />
                                    </Form.Item>
                                    <Form.Item name="email" label="Email" rules={Validations.emailValidation()}>
                                        <Input disabled={!isEditing} />
                                    </Form.Item>
                                    {isEditing ? (
                                        <Form.Item>
                                            <Button type="primary" htmlType="submit" loading={saveLoading}>Сохранить</Button>
                                            <Button style={{ marginLeft: 8 }} onClick={handleCancelEdit}>Отменить</Button>
                                        </Form.Item>
                                    ) : (
                                        <Button type="primary" onClick={handleEdit}><EditFilled /> Редактироапть</Button>
                                    )}
                                    <Flex>
                                        <Button style={{ marginTop: '10px' }} danger onClick={logOut} loading={logOutLoading}>
                                            Выйти
                                        </Button>
                                    </Flex>
                                </Form>
                            )}
                        </Col>
                    </Row>
                </Card>
            </Col>
        </Row>
    );
};

export default ProfilePage;
