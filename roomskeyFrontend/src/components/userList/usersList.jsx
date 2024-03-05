import {Input, Pagination } from 'antd';
import UserCard from "../userCrad/userCard.jsx";
import styles from './styles.module.css'
import {useState} from "react";
const { Search } = Input;

export default function UsersList(props) {
    const [current, setCurrent] = useState(1);
    const onChange = (page) => {
        setCurrent(page);
        //api request
    };
    //api request to get users info
    return (
        <>
            <div className={styles.searchContainer}>
                <Search className={styles.search} placeholder="Введите ФИО" enterButton />
            </div>
            <div>
                <UserCard fullName={'Васильев Андрей Денисович'} role={props.role} phoneNumber={'+7 (913) 827-61-62'}/>
                <UserCard fullName={'Новичков Илья Вадимович'} role={props.role} phoneNumber={'+7 (913) 827-61-62'}/>
            </div>
            <div className={styles.paginationContainer}>
                <Pagination current={current} onChange={onChange} total={50} />
            </div>
        </>
    );
}
