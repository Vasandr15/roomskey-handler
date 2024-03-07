import classes from './ReservationCard.module.css';
import {Button} from 'antd';

export default function ReservationCard() {
    return (
        <div className={classes.requstCard}>
            <div className={classes.info}>
                <p><span className={classes.spanned}>Аудитории:</span> 123</p>
                <p><span className={classes.spanned}>Пара:</span> 1 (8:45-10:20)</p>
                <span><a href="">Перейти в профиль бронирующего</a></span>
            </div>
            <div className={classes.controls}>
                <Button type="primary">Подтвердить</Button>
                <Button type="default">Отклонить</Button>
            </div>
        </div>
    )
}