import classes from "./RoleRequestCard.module.css";
import {Button} from 'antd';

export default function RoleCard() {
    return (
        <div className={classes.requstCard}>
            <div className={classes.info}>
                <p><span className={classes.spanned}>ФИО:</span>Новичков Илья Вадимович</p>
                <p><span className={classes.spanned}>Роль:</span>Студент</p>
                <span><a href="">Перейти в профиль</a></span>
            </div>
            <div className={classes.controls}>
                <Button type="primary">Подтвердить</Button>
                <Button type="default">Отклонить</Button>
            </div>
        </div>
    )
}