import {useEffect, useState} from 'react';
import {Link} from "react-router-dom";
import {Form, Input, Button, Card, Flex, Typography} from 'antd';
import styles from './register.module.css'
import {
    confirmPasswordValidation,
    emailValidation,
    middleNameValidationRules,
    nameValidationRules, passwordValidation,
    surnameValidationRules
} from "../../consts/validations.js";

const {Title} = Typography;

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
            <Card className={styles.antCard} justify={"center"} align={"center"}>
                <Form form={form} name="registration" onFinish={onFinish}  layout="vertical"
                      initialValues={{remember: true,}}
                >
                    <Title>Регистрация</Title>
                    <Flex justify="space-between" align="center" wrap="wrap">
                        <Form.Item name="surname" label="Фамилия" rules={surnameValidationRules}>
                            <Input />
                        </Form.Item>
                        <Form.Item name="name" label="Имя" rules={nameValidationRules}>
                            <Input />
                        </Form.Item>
                        <Form.Item name="middleName" label="Отчество" rules={middleNameValidationRules}>
                            <Input />
                        </Form.Item>
                    </Flex>
                    <Form.Item name="email" label="Email" rules={emailValidation}>
                        <Input/>
                    </Form.Item>
                    <Form.Item name="password" label="Пароль" hasFeedback rules={passwordValidation}>
                        <Input.Password/>
                    </Form.Item>
                    <Form.Item name="confirm" label="Потдверждение пароля" dependencies={['password']} hasFeedback
                        rules={confirmPasswordValidation}
                    >
                        <Input.Password/>
                    </Form.Item>

                        <Form.Item >
                            <Button type="primary" htmlType="submit" loading={loading}>
                                Зарегестрироваться
                            </Button>
                        </Form.Item>

                </Form>
                <div style={{textAlign: 'center'}}>
                    Уже есть аккаунт? <a>Войти</a> {/*add link*/}
                </div>
            </Card>
        </div>
    );
};

export default RegistrationForm;
