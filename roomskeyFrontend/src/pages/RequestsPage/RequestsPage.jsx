import {Breadcrumb, Col, Layout, Menu, Row, theme} from 'antd';
import HeaderSection from "../../components/Header/Header.jsx";
import SubmitReservationSection from "../../components/SubmitRoomReservationSection/SumbitRoomReservationSection.jsx";
const { Header, Content, Footer } = Layout

export default function RequestsPage() {

    return (
        <Row justify="center">
            <Col md={16}>
                <SubmitReservationSection>
                </SubmitReservationSection>
            </Col>
        </Row>
    )
}