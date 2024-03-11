import {Avatar, Card, Flex} from "antd";
import {UserOutlined} from "@ant-design/icons";

export default function ModalUserCard({name, role}) {
    return(
        <Card size="small" style={{marginBottom:'10px'}} hoverable="true">
            <Flex horizontal align="center" justify="space-between">
                <Flex horizontal align="center">
                    <Avatar size={32} icon={<UserOutlined />} />
                    <h3 style={{marginLeft: '10px'}}>{name}</h3>
                </Flex>
                <h3>{role}</h3>
            </Flex>
        </Card>
    )
}