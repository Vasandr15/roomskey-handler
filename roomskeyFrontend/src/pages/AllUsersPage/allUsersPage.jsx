import { Row, Card, Col, Tabs, Button  } from 'antd';
import styles from './styles.module.css'
import UsersList from "../../components/userList/usersList.jsx";
const AllUsersPage = () => {
    return(
        <Row justify={'center'}>
            <Col md={13}>
                <Card className={styles.card}>
                    <Tabs defaultActiveKey="student" centered
                        items = {[
                            {
                                key: 'student',
                                label: 'Студенты',
                                children: <UsersList role={'student'}/>
                            },
                            {
                                key: 'teacher',
                                label: 'Преподаватели',
                                children: <UsersList role={'teacher'}/>
                            },
                            {
                                key: 'deanOffice',
                                label: 'Сотрудники деканата',
                                children: <UsersList role={'deanOffice'}/>
                            },
                            {
                                key: 'public',
                                label: 'Остальные пользователи',
                                children: <UsersList role={'public'}/>
                            }
                        ]}
                    />
                </Card>
            </Col>
        </Row>
    );
};
export default AllUsersPage;