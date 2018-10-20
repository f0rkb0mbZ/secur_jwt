<?php
$email_error = $str_error = "";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function domain_exists($email, $record = 'MX')
{
    list($user, $domain) = explode('@', $email);
    return checkdnsrr($domain, $record);
}

function mailcheck($email)
{
    if (empty($email)) {
        $email_error = "Email is required";
        return false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format";
        return false;
    } elseif (!domain_exists($email)) {
        $email_error = "Invalid email id";
        return false;
    } else {
        $email_vaildated = test_input($email);
        $email_error = "";
        return $email_vaildated;
    }
}

function stringval($string)
{
    if (empty($string)) {
        $str_error = "Field is blank";
        return false;
    } elseif (!preg_match("/^[a-zA-Z ,]*$/", $string)) {
        $str_error = "Only letters and white space allowed";
        return false;
    } else {
        $str_validated = test_input($string);
        return $str_validated;
    }
}

function alphanumval($string)
{
    if (empty($string)) {
        $str_error = "Field is blank";
        return false;
    } elseif (!preg_match("/^[0-9a-zA-Z ,\/-.]*$/", $string)) {
        $str_error = "Only letters and white space allowed";
        return false;
    } else {
        $str_validated = test_input($string);
        return $str_validated;
    }
}

function numval($num)
{
    if (empty($num)) {
        $num_error = "Field is blank";
        return false;
    } elseif (!preg_match("/^[0-9]*$/", $num)) {
        $num_error = "Only numbers are allowed.";
        return false;
    } else {
        $num_validated = test_input($num);
        return $num_validated;
    }
}

function passval($password)
{
    if (empty($password)) {
        $pass_error = "Password can't be empty";
        return false;
    } /*elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$^+=!*()@%&]).{8,10}$/",$password)) {
        $pass_error = "Length 8 characters and must contain at least 1 uppercase, 1 lowercase letter, 1 number and 1 symbol";
        return false;
    }*/
    else {
        $pass_validated = test_input($password);
        return $pass_validated;
    }
}

?>
