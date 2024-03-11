import {useEffect, useState} from 'react';
import {Form, Input, Button, Card, Flex, Typography, message} from 'antd';
import {Link} from "react-router-dom";
import styles from './login.module.css'
import {Validations} from "../../consts/validations.js";
import {routes} from "../../consts/routes.js";
import {MaskedInput} from "antd-mask-input";
import {loginUser} from "../../API/loginUser.js";
import {useNavigate} from "react-router";

const {Title} = Typography;
const LoginForm = () => {
    const [form] = Form.useForm();
    const [loading, setLoading] = useState(false);
    const [messageApi, contextHolder] = message.useMessage();
    const navigate = useNavigate();
    const notify = (type, message) =>{
        messageApi.open({
            type: type,
            content: message,
        });
    }

    useEffect(() => {
        document.body.classList.add(styles.backgroundImage);
        return () => {
            document.body.classList.remove(styles.backgroundImage);
        }
    }, []);

    const onFinish = async (values) => {
        setLoading(true);
        console.log('Received values of form:', values);
        let token = await loginUser(values);
        console.log(token)
        if (token) {
            notify('success',
                'Вы успешно вошли');
        } else {
            notify('error',
                'Неверный логин или пароль')
        }
        localStorage.setItem("token", token)
        setTimeout(() => {
            setLoading(false);
            navigate(routes.root());
        }, 1000);
    };

    return (
        <div className={styles.formContainer}>
            {contextHolder}
            <Card className={styles.antCard}>
                <Form form={form} name="login" onFinish={onFinish} layout="vertical" initialValues={{remember: true,}}>
                    <Title>Вход</Title>
                    <Form.Item
                        name="phone" label="Номер телефона" rules={Validations.phoneValidation()}>
                        <MaskedInput mask={"+7 (000) 000-00-00"}/>
                    </Form.Item>
                    <Form.Item name="password" label="Пароль" hasFeedback rules={Validations.passwordValidation()}>
                        <Input.Password/>
                    </Form.Item>
                    <Flex justify={"center"} align={"center"}>
                        <Form.Item>
                            <Button type="primary" htmlType="submit" loading={loading}>
                                Войти
                            </Button>
                        </Form.Item>
                    </Flex>
                </Form>
                <div style={{textAlign: 'center'}}>
                    Еще нет аккаунта? <Link to={routes.registration()}>Зарегестрироваться</Link>
                </div>
            </Card>
        </div>);
};

export default LoginForm;
