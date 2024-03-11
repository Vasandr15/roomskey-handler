import {Col, Flex, Pagination, Row} from "antd";
import KeyCard from "../../components/KeyCard/KeyCard.jsx";
import KeyFilters from "../../components/KeyFilters/KeyFilters.jsx";
import {useState} from "react";
import {getAllKeys} from "../../API/getAllKeys.js";

export default function KeysPage() {
    const [inStock, setInStock] = useState(false);
    const [isCollapsed, setIsCollapsed] = useState(false);
    const [keysData, setKeysData] = useState([]);

    const handleToggleSwitch = async (checked) => {
        setInStock(checked);
        setIsCollapsed(checked);
        await handleFilterSubmit({ date: null, buildings: null, rooms: null, inStock: checked });
    };

    const handleFilterSubmit = async (filters) => {
        const data = await getAllKeys(filters);
        if (data) {
            setKeysData(data.keys);
        }
    };

    return (
        <Row justify="center">
            <Col md={16}>
                <Flex style={{marginTop: '50px'}} justify="center">
                    <KeyFilters
                        inStock={inStock}
                        onToggleSwitch={handleToggleSwitch}
                        onFilterSubmit={handleFilterSubmit}
                    />
                </Flex>
                <Flex vertical style={{marginTop: '50px'}}>
                    {keysData.map((key) => (
                        <KeyCard key={key.id} data={key} isCollapsed={isCollapsed} />
                    ))}
                </Flex>
                <Flex  align="center" justify="center">
                    <Pagination defaultCurrent={1} total={50} style={{marginTop: '30px'}}/>
                </Flex>
            </Col>
        </Row>
    )
}