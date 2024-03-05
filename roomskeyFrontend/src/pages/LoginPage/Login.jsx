import {useEffect, useState} from 'react';
import {Form, Input, Button, Card, Flex, Typography} from 'antd';
import {Link} from "react-router-dom";
import styles from './login.module.css'
import {Validations} from "../../consts/validations.js";

const {Title} = Typography;
const LoginForm = () => {
    const [form] = Form.useForm();
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        document.body.classList.add(styles.backgroundImage);
        return () => {
            document.body.classList.remove(styles.backgroundImage);
        }
    }, []);

    const onFinish = (values) => {
        setLoading(true);
        console.log('Received values of form:', values);
        // api request here
        setTimeout(() => {
            setLoading(false);
        }, 4000);
    };

    return (
        <div className={styles.formContainer}>
            <Card className={styles.antCard}>
                <Form form={form} name="login" onFinish={onFinish} layout="vertical" initialValues={{remember: true,}}>
                    <Title>Вход</Title>
                    <Form.Item name="email" label="Email" rules={Validations.emailValidation()}>
                        <Input/>
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
                    Еще нет аккаунта? <a>Зарегестрироваться</a> {/*add link*/}
                </div>
            </Card>
        </div>);
};

export default LoginForm;
