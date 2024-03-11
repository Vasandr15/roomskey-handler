export const removeSpaces = (string) => {
    return string.replace(/\s/g, '')
}

export const cleanUpValues = (values) => {
    const cleanedName = removeSpaces(values.name);
    const cleanedSurname = removeSpaces(values.surname);
    let cleanedMiddleName;
    if (values.middleName) {
        cleanedMiddleName = removeSpaces(values.middleName);
    }

    values.name = `${cleanedSurname} ${cleanedName} ${cleanedMiddleName ? cleanedMiddleName : ''}`;

    delete values.confirm;
    delete values.surname;
    delete values.middleName;
    values.role = 'public';
}