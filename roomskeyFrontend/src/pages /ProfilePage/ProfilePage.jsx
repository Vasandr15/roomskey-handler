import { Card, Row, Col, Avatar, Button, Typography } from 'antd';
import { UserOutlined } from '@ant-design/icons';
import styles from './profile.module.css';
import {useState} from "react";

const {Title} = Typography;
const ProfilePage = () => {
    const [loading, setLoading] = useState(false)

    const setButtonLoading = () =>{
            setLoading(true);
            setTimeout(()=>{
            setLoading(false)},
                4000)
    }

    return (
        <Row justify="center">
            <Col md={13}>
                <Card className={styles.card}>
                    <Row align="middle">
                        <Col flex="100px">
                            <Avatar
                                size={90}
                                className={styles.avatar}
                                icon={<UserOutlined />}
                            />
                        </Col>
                        <Col flex="auto">
                            <div>
                                <Title>Васильев Андрей Денисович</Title>
                                <Button danger onClick={setButtonLoading} loading={loading}>
                                    Выйти
                                </Button>
                            </div>
                        </Col>
                    </Row>
                </Card>
            </Col>
        </Row>
    );
};

export default ProfilePage;
