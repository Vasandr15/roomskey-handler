const ONLY_LETTERS  = /^[a-zA-Zа-яА-Я]+$/;
const FILL_IN = 'Заполните поле'
const FILL_IN_EMAIL = 'Введите свой E-mail'
const ONLY_LETTERS_MESSAGE = 'Вводите только буквы'
const requireRule = (errorMessage) => ({
   required: true,
   message: errorMessage
});

const inputValidation = (regex, errorMessage) => ({
    pattern: regex,
    message: errorMessage
});

const typeValidation = (type, errorMessage) =>({
    type:type,
    message:errorMessage
})

export const nameValidationRules = [
    requireRule(FILL_IN),
    inputValidation(ONLY_LETTERS,ONLY_LETTERS_MESSAGE )
];

export const surnameValidationRules = [
    requireRule(FILL_IN),
    inputValidation(ONLY_LETTERS, ONLY_LETTERS_MESSAGE)
];
export const middleNameValidationRules = [
    inputValidation(ONLY_LETTERS, ONLY_LETTERS_MESSAGE)
];

export const emailValidation = [
    requireRule(FILL_IN_EMAIL),
    typeValidation('email','Введите действующий E-mail')
]

export const passwordValidation = [
    requireRule('Введите свой пароль')
]

export const confirmPasswordValidation = [
    requireRule('Подтвердите свой пароль'),
    ({getFieldValue}) => ({
        validator(_, value) {
            if (!value || getFieldValue('password') === value) {
                return Promise.resolve();
            }
            return Promise.reject(new Error('Пароли должны совпадать'));
        },
    })
]