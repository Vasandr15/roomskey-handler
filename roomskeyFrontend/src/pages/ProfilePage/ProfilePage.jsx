import React, {useState} from 'react';
import {Card, Row, Col, Avatar, Button, Typography, Input, Space} from 'antd';
import {UserOutlined, EditFilled} from '@ant-design/icons';
import styles from './profile.module.css';

const {Title} = Typography;


const ProfilePage = () => {
    const [loading, setLoading] = useState(false);
    const [phone, setPhone] = useState('+7 (913) 827-61-62');
    const [email, setEmail] = useState('vasandrey007@gmail.com');
    const [fullName, setFullName] = useState('Васильев Андрей Денисович')
    const [isEditingPhone, setIsEditingPhone] = useState(false);
    const [isEditingEmail, setIsEditingEmail] = useState(false);
    const [isEditingFullName, setIsEditingFullName] = useState(false);

    const setButtonLoading = () => {
        setLoading(true);
        setTimeout(() => {
            setLoading(false);
        }, 4000);
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
            <Col md={11}>
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
                                        <Input value={phone} onChange={handlePhoneChange}
                                               style={{width: '15em'}}/>
                                        <Button type="primary" onClick={handlePhoneEdit}>Сохоанить</Button>
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
