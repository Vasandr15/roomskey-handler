<?php
    function validatePassword($password) {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    function validateEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    function validateName($name) {
        if (strlen($name) >= 1) {
            return true;
        }
        return false;
    }

    function validatePhone($phone) {
        $PHONE_REGEX = '/^\+7\s*\(\d{3}\)\s*\d{3}(-\d{2}){2}\s*$/';
        if (preg_match($PHONE_REGEX, $phone)) {
            return true;
        }
        return false;
    }
?>