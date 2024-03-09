import {Col, Flex, Pagination, Row} from "antd";
import KeyCard from "../../components/KeyCard/KeyCard.jsx";
import KeyFilters from "../../components/KeyFilters/KeyFilters.jsx";
import {useState} from "react";

export default function KeysPage() {
    const [inStock, setInStock] = useState(false);
    const [isCollapsed, setIsCollapsed] = useState(false);


    const handleToggleSwitch = (checked) => {
        setInStock(checked);
        setIsCollapsed(checked);
    };


    return (
        <Row justify="center">
            <Col md={16}>
                <Flex style={{marginTop: '50px'}}>
                    <KeyFilters inStock={inStock} onToggleSwitch={handleToggleSwitch} />
                </Flex>
                <Flex vertical style={{marginTop: '50px'}}>
                    <KeyCard isCollapsed={isCollapsed} />
                    <KeyCard isCollapsed={isCollapsed} />
                    <KeyCard isCollapsed={isCollapsed} />
                    <KeyCard isCollapsed={isCollapsed} />
                </Flex>
                <Flex  align="center" justify="center">
                    <Pagination defaultCurrent={1} total={50} style={{marginBottom: '30px'}}/>
                </Flex>
            </Col>
        </Row>
    )
}