import {DatePicker, Flex, Select, Space, Switch} from "antd";

export default function KeyFilters() {

    return (
        <Flex horizonal gap="large" align="center">
            <DatePicker
                style={{ width: '100%' }}
                placeholder="Дата"
            />
            <Select
                mode="multiple"
                allowClear
                style={{ width: '100%' }}
                placeholder="Корпус"
                defaultValue={["2"]}
            />
            <Select
                mode="multiple"
                allowClear
                style={{ width: '100%' }}
                placeholder="Корпус"
                defaultValue={["102", "103", "104", "102", "103", "104", "102", "103", "104"]}
            />
            <Flex horizontal align="center">
                <span style={{marginRight: '10px'}}>В наличии:</span>
                <Switch defaultChecked={false} />
            </Flex>
        </Flex>
    )
}