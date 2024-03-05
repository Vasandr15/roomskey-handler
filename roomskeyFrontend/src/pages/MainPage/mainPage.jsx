import { Button, Card, Col, Row } from 'antd';
import {KeyOutlined, CarryOutOutlined, UserOutlined} from '@ant-design/icons'
import styles from './mainPage.module.css';
import {routes} from "../../consts/routes.js";
import {useNavigate} from "react-router";

const mainPage = () => {
    const navigate = useNavigate();
    const handleKeysClick=()=>{
        navigate()
    }
    const handleRequestsClick=()=>{
        navigate()
    }
    const handleUsersClick=()=>{
        navigate(routes.users())
    }
    return (
        <Row justify={'center'}>
            <Col md={11}>
                <Card className={styles.mainMenuCard}>
                    <Button className={`${styles.menuBtn} ${styles.verticalMargin}`} onClick={handleRequestsClick}> <CarryOutOutlined />Заявки</Button>
                    <Button  className={`${styles.menuBtn} ${styles.verticalMargin}`} onClick={handleKeysClick}> <KeyOutlined/> Ключи</Button>
                    <Button className={`${styles.menuBtn} ${styles.verticalMargin}`} onClick={handleUsersClick}><UserOutlined />Пользователи</Button>
                </Card>
            </Col>
        </Row>
    );
};

export default mainPage;
