import { Button, Card, Col, Row } from 'antd';
import {KeyOutlined, CarryOutOutlined, UserOutlined} from '@ant-design/icons'
import styles from './styles.module.css';

const mainPage = () => {
    return (
        <Row justify={'center'}>
            <Col md={11}>
                <Card className={styles.mainMenuCard}>
                    <Button className={`${styles.menuBtn} ${styles.verticalMargin}`}> <CarryOutOutlined />Заявки</Button>
                    <Button  className={`${styles.menuBtn} ${styles.verticalMargin}`}> <KeyOutlined/> Ключи</Button>
                    <Button className={`${styles.menuBtn} ${styles.verticalMargin}`}><UserOutlined />Пользователи</Button>
                </Card>
            </Col>
        </Row>
    );
};

export default mainPage;
