<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

session_start();
require "connection.php";
$email = "";
$name = "";
$errors = array();
    if(isset($_POST['check'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM users WHERE code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $fetch_code = $fetch_data['code'];
            $email = $fetch_data['email'];
            $code = 0;
            $status = 'verified';
            $update_otp = "UPDATE users SET code = $code, status = '$status' WHERE code = $fetch_code";
            $update_res = mysqli_query($con, $update_otp);
            if($update_res){
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;
                header('location: panel.php');
                exit();
            }else{
                $errors['otp-error'] = "Şifre güncellenirken hata oluştu!";
            }
        }else{
            $errors['otp-error'] = "Yanlış OTP kodu girdiniz!";
        }
    }

    if(isset($_POST['check-email'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $check_email = "SELECT * FROM users WHERE email='$email'";
        $run_sql = mysqli_query($con, $check_email);
        if(mysqli_num_rows($run_sql) > 0){
            $code = rand(999999, 111111);
            $insert_code = "UPDATE users SET code = $code WHERE email = '$email'";
            $run_query =  mysqli_query($con, $insert_code);
            if($run_query){
                $subject = "Şifre Sıfırlama Kodu";
                $message = "Şifrenizi sıfırlamak için kullanacağınız tek kullanımlık kod: $code";
                $info = "Şifre sıfırlama kodu başarıyla gönderildi - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                    header('location: reset-code.php');
                    $mail = new PHPMailer(true);
                    $mail->SMTPDebug = 3;  
                    $mail->isSMTP();           
                    $mail->Host = "";
                    $mail->SMTPAuth = true;
                    $mail->Username = "noreply@nomee6.xyz";                 
                    $mail->Password = "sifreyimi istiyon delikanlı";
                    $mail->SMTPSecure = "ssl";
                    $mail->Port = 465;
                    $mail->From = "noreply@nomee6.xyz";
                    $mail->FromName = "Nomee6 Eğitim";
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Encoding = 'base64';
                    $mail->Subject = "$subject";
                    $mail->Body = "$message";
                    $mail->Timeout       =   60;
                    $mail->SMTPKeepAlive = true;
try {
    $mail->send();
    echo "Şifre sıfırlama maili başarıyla gönderildi.";
} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
            }else{
                $errors['db-error'] = "Bir şeyler yanlış gitti!";
            }
        }else{
            $errors['email'] = "Bu mail adresine sahip bir kullanıcı yok!";
        }
    }

    if(isset($_POST['check-reset-otp'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM users WHERE code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $email = $fetch_data['email'];
            $_SESSION['email'] = $email;
            $info = "Yeni bir şifre belirleyin. Lütfen daha önce kullanmadığınız bir şifre kullanmaya özen gösteriniz.";
            $_SESSION['info'] = $info;
            header('location: new-password.php');
            exit();
        }else{
            $errors['otp-error'] = "Yanlış OTP kodu girdiniz!";
        }
    }

    if(isset($_POST['change-password'])){
        $_SESSION['info'] = "";
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
        if($password !== $cpassword){
            $errors['password'] = "Şifreler eşleşmiyor!";
        }else{
            $code = 0;
            $email = $_SESSION['email'];
            $encpass = md5($password);
            $update_pass = "UPDATE users SET code = $code, password = '$encpass' WHERE email = '$email'";
            $run_query = mysqli_query($con, $update_pass);
            if($run_query){
                $info = "Şifreniz başarıyla değiştirildi. Yeni şifreniz ile giriş yapabilirsiniz.";
                $_SESSION['info'] = $info;
                header('Location: index.php');
            }else{
                $errors['db-error'] = "Şifreniz değiştirilirken bir hata oluştu!";
            }
        }
    }
?>