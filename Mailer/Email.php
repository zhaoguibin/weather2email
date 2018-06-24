<?php
/**
 * Created by PhpStorm.
 * User: ZHaoGuiBin
 * Date: 2018/6/24/0024
 * Time: 15:32
 */
namespace Mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Email
{

    public static function sendMail($sendTo = '940508445@qq.com',$title = 'hello world',$content = "<b>hello world</b>")
    {

        $config = file_get_contents(BASEDIR.'/conf/'."config.ini");
        $config = json_decode($config,true);

        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = $config['HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['USERNAME'];
            $mail->Password = $config['PASSWORD'];
            $mail->SMTPSecure = $config['SMTPSecure'];
            $mail->Port = $config['PORT'];

            //Recipients
            $mail->setFrom($config['SETFROM'],$config['NICKNAME']);
            $mail->addAddress($sendTo);

            //Content
            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body    = $content;
            $mail->send();
        } catch (Exception $e) {
            $log_path = BASEDIR.'/Log/error.log';

            if(!file_exists($log_path)){
                touch($log_path);
            }

            $handle = fopen($log_path, "a+");
            $content = date("Y-m-d H:i:s",time()).'Message could not be sent. Mailer Error: '.$mail->ErrorInfo."\r\n";
            fwrite($handle, $content);
            fclose($handle);

        }
    }

}