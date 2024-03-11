import {Layout, Menu} from 'antd';
import {useNavigate, useLocation} from "react-router-dom";
import {routes} from "../../consts/routes.js";
import styles from './Header.module.css';
import {getProfile} from "../../API/getProfile.js";

const {Header} = Layout;

export default function HeaderSection() {
    const navigate = useNavigate();
    const location = useLocation();

    const menuRoutes = {
        main: routes.root(),
        users: routes.users(),
        requests: routes.requests(),
        profile: routes.profile(),
        login: routes.login(),
        keys: routes.keys()
    };

    const pathToKey = Object.keys(menuRoutes).reduce((acc, key) => {
        const path = menuRoutes[key];
        acc[path] = key;
        return acc;
    }, {});

    const currentKey = pathToKey[location.pathname] || 'main';

    let items = [
        {key: 'main', label: "Главная"},
    ];

    const isAuth = localStorage.getItem('token');

    if (isAuth) {
        items.push({key: 'users', label: "Пользователи"},
            {key: 'requests', label: "Заявки"},
            {key: 'keys', label: "Ключи"},
            {key: 'profile', label: "Профиль", style: {marginLeft: 'auto'}}
        );
    } else {
        items.push({key: 'login', label: 'Вход', style: {marginLeft: 'auto'}});
    }

    const handleMenuClick = (e) => {
        const path = menuRoutes[e.key];
        if (path) navigate(path);
    };

    return (
        <Header className={styles.header}>
            <div className="demo-logo"/>
            <Menu
                theme="dark"
                mode="horizontal"
                items={items}
                selectedKeys={[currentKey]}
                onClick={handleMenuClick}
                style={{flex: 1}}
            />
        </Header>
    );
}
