import {Breadcrumb, Layout, Menu, theme} from 'antd';

const {Header, Content, Footer} = Layout
export default function HeaderSection() {

    const items = [
        {key: 'users', label: "Пользователи"},
        {key: 'requests', label: "Заявки"},
        {key: 'keys', label: "Ключи"},
        {key: 'profile', label: "Профиль", style: {marginLeft: 'auto'}},
        {key: 'login', label: 'Вход'}
    ]

    return (
        <Header style={{display: 'flex', alignItems: 'center'}}>
            <div className="demo-logo"/>
            <Menu
                theme="dark"
                mode="horizontal"
                defaultSelectedKeys={['requests']}
                items={items}
                style={{flex: 1, minWidth: 0}}
            />
        </Header>
    )
}