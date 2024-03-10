import React, {useState, useEffect} from 'react';
import {Card, Row, Col, Avatar, Button, Typography, Input, Space} from 'antd';
import {UserOutlined, EditFilled} from '@ant-design/icons';
import styles from './profile.module.css';
import {MaskedInput} from "antd-mask-input";
import {getProfile} from "../../API/getProfile.js";

const {Title} = Typography;

localStorage.setItem("token", '7beec77a-d2e8-4f92-abec-07150ab4494c')
const ProfilePage = () => {
    const [loading, setLoading] = useState(false);
    const [phone, setPhone] = useState('');
    const [email, setEmail] = useState('');
    const [fullName, setFullName] = useState('');
    const [isEditingPhone, setIsEditingPhone] = useState(false);
    const [isEditingEmail, setIsEditingEmail] = useState(false);
    const [isEditingFullName, setIsEditingFullName] = useState(false);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const userInfo = await getProfile();
                setPhone(userInfo.phone);
                setEmail(userInfo.email);
                setFullName(userInfo.name);
            } catch (error) {
                console.error('Error fetching profile:', error);
            }
        };
        fetchData();
    }, []);

    const setButtonLoading = () => {
        setLoading(true);
        setTimeout(() => {
            setLoading(false);
        }, 1000);
    };

    const handlePhoneEdit = () => {
        setIsEditingPhone(!isEditingPhone);
    };

    const handleFullNameEdit = () => {
        setIsEditingFullName(!isEditingFullName)
    }
    const handleEmailEdit = () => {
        setIsEditingEmail(!isEditingEmail);
    };

    const handlePhoneChange = (e) => {
        setPhone(e.target.value);
    };

    const handleEmailChange = (e) => {
        setEmail(e.target.value);
    };
    const handleFullNameChange = (e) => {
        setFullName(e.target.value);
    };
    return (
        <Row justify="center">
            <Col md={13}>
                <Card className={styles.card} md={11}>
                    <Row align="middle">
                        <Col flex="100px">
                            <Avatar size={120} className={styles.avatar} icon={<UserOutlined/>}/>
                        </Col>
                        <Col flex="auto" style={{marginLeft: '50px'}}>
                            <Row>
                                {isEditingFullName ?
                                    <Space.Compact>
                                        <Input value={fullName} onChange={handleFullNameChange} size="large"
                                               style={{width: '18em'}}/>
                                        <Button size="large" type="primary"
                                                onClick={handleFullNameEdit}>Сохранить</Button>
                                    </Space.Compact> :
                                    <Title>{fullName}<Button type="link" onClick={handleFullNameEdit}>
                                        {isEditingFullName ? null : <EditFilled/>}
                                    </Button></Title>}{' '}
                            </Row>
                            <div>
                                <b>Номер телефона:</b> {isEditingPhone ?
                                <Space.Compact>
                                    <MaskedInput mask={"+7 (000) 000-00-00"} value={phone}
                                                 onChange={handlePhoneChange} style={{width: '15em'}}/>
                                    <Button type="primary" onClick={handlePhoneEdit}>Сохранить</Button>
                                </Space.Compact> : <span>{phone}</span>}{' '}
                                <Button type="link" onClick={handlePhoneEdit}>
                                    {isEditingPhone ? null : <EditFilled/>}
                                </Button>
                            </div>
                            <div>
                                <b>Email:</b> {isEditingEmail ?
                                <Space.Compact>
                                    <Input value={email} onChange={handleEmailChange}
                                           style={{width: '15em'}}/>
                                    <Button type="primary" onClick={handleEmailEdit}>Сохранить</Button>
                                </Space.Compact>
                                : <span>{email}</span>}{' '}
                                <Button type="link" onClick={handleEmailEdit}>
                                    {isEditingEmail ? null : <EditFilled/>}
                                </Button>
                            </div>
                            <Button style={{marginTop: '10px'}} danger onClick={setButtonLoading} loading={loading}>
                                Выйти
                            </Button>
                        </Col>
                    </Row>
                </Card>
            </Col>
        </Row>
    );
};

export default ProfilePage;
