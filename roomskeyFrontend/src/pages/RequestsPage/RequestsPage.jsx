import Header from "../../components/Header/Header.jsx";
import classes from "./RequestPage.module.css"
import {Tabs} from 'antd'
import SumbitReservationSection from "../../components/SubmitRoomReservationSection/SumbitRoomReservationSection.jsx";
import SumbitRoleSection from "../../components/SubmitUserRoleSection/SumbitUserRoleSection.jsx";

export default function RequestsPage() {
    return (
        <>
            <Header></Header>
            <Tabs className = {classes.TabsSection}
                defaultActiveKey="1"
                centered size = "large"
                items={[
                    {
                        label: 'Заявки на подтверждение роли',
                        key: '1',
                        children: <SumbitRoleSection/> ,
                    },
                    {
                        label: 'Заявки на бронирование аудитории',
                        key: '2',
                        children: <SumbitReservationSection/>,
                    }
                ]}
            />
        </>
    )
}