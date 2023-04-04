<?php
    // Biorhythm by 1wise.es
    // http://kamala.pro/biorritmos
    // http://1wise.es
    //
    // Last edit 04-04-2023 00:00
    //
    // Print a standard page header
    //
require_once './PHPMailer/src/Exception.php';
require_once './PHPMailer/src/PHPMailer.php';
require_once './PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
function sense_mail($emUsr, $remMsg, $emAsu, $miMsg, $nomImg) {
      global $emUsr, $remMsg, $emAsu, $emMsg, $nomImg;
      $emIp = $_SERVER['REMOTE_ADDR'];
      $datUrl = "https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
      $dirImg = "/consultas/";

      $mail = new PHPMailer();
      // SMTP configuration
      try {
        $nowForm = date("d-m-Y H:i:s ");
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Host = 'SMPT.SERVER';
        $mail->SMTPAuth = true;
        $mail->Username = 'USER@DOMAIN';
        $mail->Password = 'PASSWORD';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        // Sender and recipient details
        $mail->setFrom('kamala@1wise.es', 'Biorritmos kamala.pro');
//        $mail->addCC('');
        $mail->addBCC('henri@sirkia.es');
        $recipients = explode(',', $emUsr);
        foreach ($recipients as $recipient) {
            $mail->addAddress(trim($recipient));
        }
        // Email content
        $mail->isHTML(true);
        $mail->Subject = $emAsu;

        $nowForm = date("d-m-Y H:i:s ");
        $emMsg .= "<html><body>";
        $emMsg .= "<p>Este correo ha sido enviado desde el formulario https://kamala.pro powered by https://1wise.es.</p>";
        $emMsg .= "<p>Para el móvil https://kamala.pro/bior/ y para el ordenador https://kamala.pro/biorritmos/.</p>";
        $emMsg .= "<p>Para hacer las consultas directamente https://kamala.pro/bior/bior.php?emdat=01-01-1970-31-12-2023 respetando ese formato.</p>";
        $emMsg .= $miMsg;
        $mail->addEmbeddedImage(".".$dirImg.$nomImg, $nomImg);
        $emMsg .= "</body></html>";

        $mail->Body = $emMsg;
        $mail->addAttachment(".".$dirImg.$nomImg, $nomImg);

        // Send email
        $mail->send();
        $mailLog  = ">".$emIp."<< - >>".$emUsr."<< - >>".$emDat."<< - >>".$remMsg." - ".date("d-m-Y H:i:s :)").PHP_EOL;
        file_put_contents('LOCOMAIL.log', $smsLog, FILE_APPEND); 

 
        echo "Email enviado !!<br>"; 
      } catch (Exception $e) {
         echo 'Email no se a podido enviar. Error: ', $mail->ErrorInfo; echo '<br>';
      }
}
?>
