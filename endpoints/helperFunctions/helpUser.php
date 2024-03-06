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

function validatePhoneNumber($phoneNumber)
{
    if (ctype_digit($phoneNumber))
    {
        return true;
    }
    return false;
}
