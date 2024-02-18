import { Button, Result } from 'antd';
import {routes} from "../consts/routes.js";
import {useNavigate} from "react-router";
const NotFoundPage = () => {
    const navigate = useNavigate();
    const handleButtonClick=()=>{
        navigate(routes.root())
    }
    return(
    <Result
        status="404"
        title="404"
        subTitle="Похоже, что страница, на которой вы находитесь, несуществует"
        extra={<Button type="primary" onClick={handleButtonClick}>На главную</Button>}
    />
    );
};
export default NotFoundPage;