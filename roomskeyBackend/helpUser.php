<?php
function validatePassword($pass)
{
    if (validateStringNotLess($pass,6))
    {
        return true;
    }
    return false;
}

function validateEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        return true;
    }
    return false;
}

function validateFullName($fullName)
{
    if (validateStringNotLess($fullName,1))
    {
        return true;
    }
    return false;
}

function validateBirthDate($birthDate)
{
    //есть проблемы если дата в будущем
    $format = "Y-m-d";
    $dateTime = DateTime::createFromFormat($format, $birthDate);
    if ($dateTime && $dateTime->format($format) === $birthDate)
    {
        return true;
    }
    return false;
}

function validateGender($gender)
{
    if ($gender == "Male" or $gender == "Female")
    {
        return true;
    }
    return false;
}

function validatePhoneNumber($phoneNumber)
{
    if (ctype_digit($phoneNumber))
    {
        return true;
    }
    return false;
}
