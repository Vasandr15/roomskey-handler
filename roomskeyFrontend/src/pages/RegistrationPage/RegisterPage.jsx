import {useEffect, useRef, useState} from 'react';
import {Link} from "react-router-dom";
import {Form, Input, Button, Card, Flex, Typography} from 'antd';
import {MaskedInput} from 'antd-mask-input';
import styles from './register.module.css'
import {Validations} from "../../consts/validations.js";
import {routes} from "../../consts/routes.js";

const {Title} = Typography;

const RegistrationForm = () => {
    const [form] = Form.useForm();
    const [loading, setLoading] = useState(false);
    const phoneInputRef = useRef(null);
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
                <Form form={form} name="registration" onFinish={onFinish} layout="vertical"
                      initialValues={{remember: true,}}
                >
                    <Title>Регистрация</Title>
                    <Flex justify="space-between" align="center" wrap="wrap">
                        <Form.Item name="surname" label="Фамилия" rules={Validations.surnameValidationRules()}>
                            <Input/>
                        </Form.Item>
                        <Form.Item name="name" label="Имя" rules={Validations.nameValidationRules()}>
                            <Input/>
                        </Form.Item>
                        <Form.Item name="middleName" label="Отчество" rules={Validations.middleNameValidationRules()}>
                            <Input/>
                        </Form.Item>
                    </Flex>
                    <Form.Item name="email" label="Email" rules={Validations.emailValidation()}>
                        <Input/>
                    </Form.Item>
                    <Form.Item
                        name="phone" label="Номер телефона" rules={Validations.phoneValidation()}>
                        <MaskedInput mask={"+7 (000) 000-00-00"}/>
                    </Form.Item>
                    <Form.Item name="password" label="Пароль" hasFeedback rules={Validations.passwordValidation()}>
                        <Input.Password/>
                    </Form.Item>
                    <Form.Item name="confirm" label="Потдверждение пароля" dependencies={['password']} hasFeedback
                               rules={Validations.confirmPasswordValidation()}
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
                    Уже есть аккаунт? <Link to={routes.login()}>Войти</Link>
                </div>
            </Card>
        </div>
    );
};

export default RegistrationForm;
