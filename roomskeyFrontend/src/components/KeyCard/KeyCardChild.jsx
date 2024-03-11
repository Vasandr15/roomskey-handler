import {Flex} from "antd";
import {lessons} from "../../consts/lessons.js";

export default function KeyCardChild({ booked }) {
    const allPairsFree = !booked || booked.length === 0;

    return (
        <Flex vertical>
            <Flex vertical>
                {allPairsFree ? (
                    <h5>Ключ не забронирован ни на одну пару</h5>
                ) : (
                    lessons.map((lesson) => {
                        const bookedTime = booked.find((time) => parseInt(time.time) === lesson.key);
                        if (bookedTime) {
                            return (
                                <h5 key={lesson.key}>
                                    {lesson.label}: {bookedTime.name} ({bookedTime.role === "public" ? "Student" : bookedTime.role})
                                </h5>
                            );
                        }
                    })
                )}
            </Flex>
        </Flex>
    );
}