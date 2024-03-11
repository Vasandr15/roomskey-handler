import {Button, DatePicker, Flex, Input, Select, Space, Switch} from "antd";
import {useState} from "react";
import {buildings} from "../../consts/buildings.js";
import {rooms} from "../../consts/rooms.js";

export default function KeyFilters({ inStock, onToggleSwitch, onFilterSubmit }) {
    const [selectedDate, setSelectedDate] = useState(null);
    const [selectedBuildings, setSelectedBuildings] = useState([]);
    const [selectedRooms, setSelectedRooms] = useState([]);

    const handleDateChange = (date) => {
        setSelectedDate(date);
    };

    const handleBuildingChange = (values) => {
        setSelectedBuildings(values);
    };

    const handleRoomsChange = (values) => {
        setSelectedRooms(values);
    };

    const handleFilterSubmit = () => {
        const filters = {
            date: selectedDate,
            buildings: selectedBuildings,
            rooms: selectedRooms,
            inStock: inStock
        };

        onFilterSubmit(filters);
    };

    return (
        <Flex  gap="large" align="center">
            <DatePicker
                style={{ width: '100%' }}
                placeholder="Дата"
                onChange={handleDateChange}
            />
            <Select
                mode="multiple"
                allowClear
                style={{ width: '100%' }}
                placeholder="Корпус"
                onChange={handleBuildingChange}
                options={buildings}
            />
            <Select
                mode="multiple"
                allowClear
                style={{ width: '100%' }}
                placeholder="Аудитория"
                onChange={handleRoomsChange}
                options={rooms}
            />
            <Flex  align="center">
                <span style={{marginRight: '10px'}}>В наличии:</span>
                <Switch checked={inStock} onChange={onToggleSwitch}/>
            </Flex>
            <Space>
                <Button type="primary" onClick={handleFilterSubmit}>Применить</Button>
            </Space>
        </Flex>
    )
}