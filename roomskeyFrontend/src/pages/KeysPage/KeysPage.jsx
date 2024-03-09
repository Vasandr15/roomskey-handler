import {Col, Flex, Pagination, Row} from "antd";
import KeyCard from "../../components/KeyCard/KeyCard.jsx";
import KeyFilters from "../../components/KeyFilters/KeyFilters.jsx";

export default function KeysPage() {
    return (
        <Row justify="center">
            <Col md={16}>
                <Flex style={{marginTop: '50px'}}>
                    <KeyFilters/>
                </Flex>
                <Flex vertical style={{marginTop: '50px'}}>
                    <KeyCard/>
                    <KeyCard/>
                    <KeyCard/>
                    <KeyCard/>
                </Flex>
                <Flex horizontal align="center" justify="center">
                    <Pagination defaultCurrent={1} total={50} style={{marginBottom: '30px'}}/>
                </Flex>
            </Col>
        </Row>
    )
}