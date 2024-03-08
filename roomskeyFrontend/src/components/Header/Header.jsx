import { Layout, Menu } from 'antd';
import { useNavigate, useLocation } from "react-router-dom";
import { routes } from "../../consts/routes.js";
import styles from './Header.module.css';
const { Header } = Layout;

export default function HeaderSection() {
    const navigate = useNavigate();
    const location = useLocation();

    const menuRoutes = {
        main: routes.root(),
        users: routes.users(),
        requests: routes.requests(),
        profile: routes.profile(),
        login: routes.login()
    };

    // Reverse mapping from path back to key
    const pathToKey = Object.keys(menuRoutes).reduce((acc, key) => {
        const path = menuRoutes[key];
        acc[path] = key;
        return acc;
    }, {});

    const currentKey = pathToKey[location.pathname] || 'main';

    const items = [
        { key: 'main', label: "Главная" },
        { key: 'users', label: "Пользователи" },
        { key: 'requests', label: "Заявки" },
        { key: 'keys', label: "Ключи" },
        { key: 'profile', label: "Профиль", style: { marginLeft: 'auto' } },
        { key: 'login', label: 'Вход' }
    ];

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
                style={{ flex: 1 }}
            />
        </Header>
    );
}
