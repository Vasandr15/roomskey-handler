import React, { useEffect, useState } from "react";
import { Input, Pagination } from 'antd';
import UserCard from "../userCrad/userCard.jsx";
import styles from './styles.module.css'
import { getUsersWithParams } from "../../API/getUsers.js";

const { Search } = Input;

export default function UsersList(props) {
    const [current, setCurrent] = useState(1);
    const [users, setUsers] = useState([]);
    const [total, setTotal] = useState(0);

    useEffect(() => {
        fetchInfo();
    }, [current]);

    const onChange = (page) => {
        setCurrent(page);
    };

    const fetchInfo = async (name = null) => {
        const result = await getUsersWithParams(props.role, name, current);
        if (result && result.users) {
            setUsers(result.users);
            setTotal(result.pagination ? result.pagination.size * result.pagination.count : 0);
        }
    };

    const onSearch = async (value)=>{
        await fetchInfo(value);
    }
    return (
        <>
            <div className={styles.searchContainer}>
                <Search className={styles.search} placeholder="Введите ФИО" enterButton onSearch={onSearch}/>
            </div>
            <div>
                {users.map(user => (
                    <UserCard key={user.id} id={user.id} fullName={user.name} role={user.role} phoneNumber={user.phone} />
                ))}
            </div>
            {total > 10 ? (
                <div className={styles.paginationContainer}>
                    <Pagination current={current} onChange={onChange} total={total} />
                </div>
            ) : null}
        </>
    );
}
