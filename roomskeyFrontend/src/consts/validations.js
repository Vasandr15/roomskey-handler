const ONLY_LETTERS  = /^[a-zA-Zа-яА-Я]+\s*$/;
const PHONE_REGEX = /^\+7\s*\(\d{3}\)\s*\d{3}(-\d{2}){2}\s*$/;
const FILL_IN = 'Заполните поле'
const FILL_IN_EMAIL = 'Введите свой E-mail'
const ONLY_LETTERS_MESSAGE = 'Вводите только буквы'

class Validation {
    static requireRule = (errorMessage) => ({
        required: true,
        message: errorMessage
    });

    static inputValidation = (regex, errorMessage) => ({
        pattern: regex,
        message: errorMessage
    });

    static typeValidation = (type, errorMessage) =>({
        type:type,
        message:errorMessage
    })

    static lengthValidation = (minLength, maxLength, errorMessage) =>({
        max : maxLength,
        min : minLength,
        message: errorMessage
    })
}

export const Validations = {

    nameValidationRules : () => [
        Validation.requireRule(FILL_IN),
        Validation.inputValidation(ONLY_LETTERS,ONLY_LETTERS_MESSAGE )
    ],

    surnameValidationRules : () =>  [
        Validation.requireRule(FILL_IN),
        Validation.inputValidation(ONLY_LETTERS, ONLY_LETTERS_MESSAGE)
    ],

    middleNameValidationRules : () =>  [
        Validation.inputValidation(ONLY_LETTERS, ONLY_LETTERS_MESSAGE)
    ],

    emailValidation : () =>  [
        Validation.requireRule(FILL_IN_EMAIL),
        Validation.typeValidation('email','Введите действующий E-mail')
    ],

    passwordValidation : () =>  [
        Validation.requireRule('Введите свой пароль'),
        Validation.lengthValidation(6, 30, 'Пароль должен соднржать от 6 до 30 символов')
    ],

    confirmPasswordValidation : () =>  [
        Validation.requireRule('Подтвердите свой пароль'),
        ({getFieldValue}) => ({
            validator(_, value) {
                if (!value || getFieldValue('password') === value) {
                    return Promise.resolve();
                }
                return Promise.reject(new Error('Пароли должны совпадать'));
            },
        })
    ],

    phoneValidation : () => [
        Validation.requireRule('Введите номер телефона'),
        Validation.inputValidation(PHONE_REGEX, 'Введите действующий номер телефона')
    ]
}
