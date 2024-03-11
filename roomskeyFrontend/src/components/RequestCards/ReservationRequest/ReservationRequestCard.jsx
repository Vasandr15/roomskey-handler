
import {Button, Card, Flex} from 'antd';
import {lessons} from "../../../consts/lessons.js";
import {changeStatus} from "../../../API/changeStatus.js";

export default function ReservationCard ({ data }) {
    const getLesson = (lessonKey) => {
        const lesson = lessons.find(item => item.key === parseInt(lessonKey));
        return lesson ? lesson.label : '';
    };

    const handleAccept = async () => {
        try {
            await changeStatus(data.id, { status: 'accepted' });
        } catch (error) {
            console.log(error.text())
        }
    };

    const handleReject = async () => {
        try {
            await changeStatus(data.id, { status: 'refused' });
        } catch (error) {
           console.log(error.text())
        }
    };

    return (
        <Card style={{ marginTop: '10px' }} size="default">
            <Flex horizontal>
                <Flex vertical>
                    <p>Аудитория: {data.building}-{data.room}</p>
                    <p>{getLesson(data.time)}</p>
                    <p><b>Автор заявки: </b>{data.userName}</p>
                    <p><b>Статус: </b>{data.status}</p>
                </Flex>
                <Flex vertical>
                    <Button type="primary" onClick={handleAccept}>Подтвердить</Button>
                    <Button type="default" onClick={handleReject}>Отклонить</Button>
                </Flex>
            </Flex>
        </Card>
    );
};
