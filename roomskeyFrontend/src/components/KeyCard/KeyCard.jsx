import {Collapse, Space} from "antd";
import KeyCardChild from "./KeyCardChild.jsx";

export default function KeyCard() {
    const child = (<KeyCardChild></KeyCardChild>)

    return(
        <Space direction="vertical" style={{marginBottom: '30px'}}>
            <Collapse
                className="collapseCard"
                size="large"
                defaultActiveKey={['1']}
                items={[
                    {
                        key: '1',
                        label: 'Ключ 2-218',
                        children: child,
                    },
                ]}
            />
        </Space>
    )
}