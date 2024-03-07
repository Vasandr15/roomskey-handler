import ReservationCard from "../RequestCards/ReservationRequest/ReservationRequestCard.jsx";
import classes from './SRRS.module.css'

export default function SubmitReservationSection() {
    return (
        <>
            <div className={classes.requests_sect}>
                <ReservationCard></ReservationCard>
            </div>
        </>
    )
}