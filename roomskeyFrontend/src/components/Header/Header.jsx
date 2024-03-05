import {Breadcrumb, Layout, Menu, theme} from 'antd';

const {Header, Content, Footer} = Layout
export default function HeaderSection() {

    const items = [{key: 1, label: "Пользователи"}, {key: 2, label: "Заявки"}, {key: 3, label: "Ключи"}, {
        key: 4, label: "Профиль"
    }]

    return (<Header style={{display: 'flex', alignItems: 'center'}}>
            <div className="demo-logo"/>
            <Menu
                theme="dark"
                mode="horizontal"
                defaultSelectedKeys={['2']}
                items={items}
                style={{flex: 1, minWidth: 0}}
            />
        </Header>)
}