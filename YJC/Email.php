<?php

namespace YJC;

/**
 * Created by PhpStorm.
 * Date: 2014/12/11
 * Time: 14:24
 */
class Email
{
    public static function send($email,$subject,$body)
    {
        require_once(__DIR__ . "/Mailer/class.phpmailer.php");
        require_once(__DIR__ . "/Mailer/class.smtp.php");
        $mail = new \PHPMailer;
        $mail->isSMTP();
        $mail->IsHTML(true);

        if(is_array($email)){
            foreach($email as $e){
                $mail->AddAddress($e);
            }
        }else{
            $mail->AddAddress($email);
        }

        $mail->Host = 'smtp.exmail.qq.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'test@qq.com';
        $mail->Password = '123456';
        $mail->From = 'test@qq.com';
        $mail->FromName = '测试';
        $mail->CharSet = "UTF-8";
        $mail->Subject = $subject;

        $mail->Body = $body;
        $mail->send();
    }

} 