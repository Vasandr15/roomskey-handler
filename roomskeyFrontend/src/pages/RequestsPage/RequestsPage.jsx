import { Breadcrumb, Layout, Menu, theme } from 'antd';
import HeaderSection from "../../components/Header/Header.jsx";
import SubmitReservationSection from "../../components/SubmitRoomReservationSection/SumbitRoomReservationSection.jsx";
const { Header, Content, Footer } = Layout

export default function RequestsPage() {

    return (
        <Layout>
            <HeaderSection/>
            <Content>
                <SubmitReservationSection/>
            </Content>
        </Layout>
    )
}