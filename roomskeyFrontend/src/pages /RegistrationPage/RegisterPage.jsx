import {useEffect, useState} from 'react';
import {Link} from "react-router-dom";
import {Form, Input, Button, Card, Flex, Typography} from 'antd';
const {Title} = Typography;
import styles from'./register.module.css'

const RegistrationForm = () => {
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
                <Form
                    form={form}
                    name="registration"
                    onFinish={onFinish}
                    initialValues={{
                        remember: true,
                    }}
                    layout="vertical"
                >
                    <Title>Регистрация</Title>
                    <Flex gap={"small"} justify={"space-between"} align={"center"}>
                        <Form.Item
                            name="name"
                            label="Имя"
                            rules={[
                                {
                                    required: true,
                                    message: 'Введите своё имя',
                                },
                            ]}
                        >
                            <Input/>
                        </Form.Item>
                        <Form.Item
                            name="surname"
                            label="Фамилия"
                            rules={[
                                {
                                    required: true,
                                    message: 'Введите свою фамилию',
                                },
                            ]}
                        >
                            <Input/>
                        </Form.Item>
                        <Form.Item
                            name="middleName"
                            label="Отчество"
                        >
                            <Input/>
                        </Form.Item>
                    </Flex>


                    <Form.Item
                        name="email"
                        label="Email"
                        rules={[
                            {
                                type: 'email',
                                message: 'Введите действующий E-mail',
                            },
                            {
                                required: true,
                                message: 'Введите свой E-mail',
                            },
                        ]}
                    >
                        <Input/>
                    </Form.Item>

                    <Form.Item
                        name="password"
                        label="Пароль"
                        rules={[
                            {
                                required: true,
                                message: 'Введите свой пароль',
                            },
                        ]}
                        hasFeedback
                    >
                        <Input.Password/>
                    </Form.Item>

                    <Form.Item
                        name="confirm"
                        label="Потдверждение пароля"
                        dependencies={['password']}
                        hasFeedback
                        rules={[
                            {
                                required: true,
                                message: 'Подтвердите свой пароль',
                            },
                            ({getFieldValue}) => ({
                                validator(_, value) {
                                    if (!value || getFieldValue('password') === value) {
                                        return Promise.resolve();
                                    }
                                    return Promise.reject(new Error('Пароли должны совпадать'));
                                },
                            }),
                        ]}
                    >
                        <Input.Password/>
                    </Form.Item>
                    <Flex justify={"center"} align={"center"}>
                        <Form.Item>
                            <Button type="primary" htmlType="submit" loading={loading}>
                                Зарегестрироваться
                            </Button>
                        </Form.Item>
                    </Flex>

                </Form>
                <div style={{textAlign: 'center'}}>
                    Уже есть аккаунт? <Link to="/login">Войти</Link>
                </div>
            </Card>
        </div>
    );
};

export default RegistrationForm;
