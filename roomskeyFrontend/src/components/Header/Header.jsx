import classes from "./Header.module.css"
export default function Header() {

    return (
    <>
        <nav>
            <div>
                <ul className={classes.navbarList}>
                    <li><a href="">Главная</a></li>
                    <li><a href="">Преподаватели</a></li>
                    <li><a className="active" href=" ">Заявки</a></li>
                    <li><a href="">Ключи</a></li>
                </ul>
            </div>
            <span><a href="">Имя Фамилия</a></span>
        </nav>
    </>
    )
}