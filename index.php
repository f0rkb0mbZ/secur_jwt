<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
//include 'db.php';
include 'validation.php';
include_once('func.php');

function generateToken($sess)
{
    $random_salt = bin2hex(random_bytes(128));
    return $token = hash_hmac('sha512', $sess, $random_salt);
}

$conn = new PDO('mysql:host=127.0.0.1;dbname=user', 'root', '');
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (isset($_POST['submit_reg'])) {
    $email = filter_var(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
    if (!domain_exists($email)) {
        unset($email);
    }
    $password = htmlspecialchars($_POST['passwrd']);
    $hash = password_hash($password, PASSWORD_BCRYPT, [12]);
    $insquery = $conn->prepare("INSERT INTO signupped (email, pass) VALUES(:email, :password)");

    $insquery->bindParam(':password', $hash);
    $insquery->bindParam(':email', $email);
    if (isset($email) && isset($password)) {
        $insquery->execute();
    }
} elseif (isset($_POST['submit_login'])) {
    $email = filter_var(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
    if (!domain_exists($email)) {
        unset($email);
    }
    $password = htmlspecialchars($_POST['password']);
    $loginquery = $conn->prepare("SELECT * FROM signupped WHERE email= :email");
    $loginquery->bindParam(':email', $email);
    if (isset($email) && isset($password)) {
        $loginquery->execute();
    }
    $user = $loginquery->fetch(PDO::FETCH_OBJ);
    $user = (array)$user;
    $dbpass = $user['pass'];
    if (password_verify($password, $dbpass)) {
        echo "<p class='bg-success'>User verified</p>";
        $sess = $_SESSION['PHPSESSID'];

        $_SESSION['uid'] += 1;
        $_SESSION['token'] = $token = generateToken($sess);
        setcookie('token', $token, time() + 3600);
        setcookie('uid', );
    } else {
        echo "<p class='bg-danger'>User not verified</p>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login, Register form</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

    <link rel='stylesheet' href='http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>

    <link rel="stylesheet" href="css/style.css">


</head>

<body>


<div class="login-box">
    <div class="lb-header">
        <a href="#" class="active" id="login-box-link">Login</a>
        <a href="#" id="signup-box-link">Sign Up</a>
    </div>
    <div class="social-login">
        <a href="#">
            <i class="fa fa-facebook fa-lg"></i>
            Login in with facebook
        </a>
        <a href="#">
            <i class="fa fa-google-plus fa-lg"></i>
            log in with Google
        </a>
    </div>
    <form class="email-login" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="u-form-group">
            <input type="email" placeholder="Email" name="email"/>
        </div>
        <div class="u-form-group">
            <input type="password" placeholder="Password" name="password"/>
        </div>
        <div>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
        </div>
        <div class="u-form-group">
            <button type="submit" name="submit_login">Log in</button>
        </div>
        <div class="u-form-group">
            <a href="#" class="forgot-password">Forgot password?</a>
        </div>
    </form>
    <form class="email-signup" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="u-form-group">
            <input type="email" placeholder="Email" name="email"/>
        </div>
        <div class="u-form-group">
            <input type="password" placeholder="Password" name="passwrd"/>
        </div>
        <div class="u-form-group">
            <input type="password" placeholder="Confirm Password"/>
        </div>
        <div class="u-form-group">
            <button type="submit" name="submit_reg">Sign Up</button>
        </div>
    </form>
</div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>


<script src="js/index.js"></script>


</body>

</html>
