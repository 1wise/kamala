<!DOCTYPE html>
<html>
<head>
    <title>Combinador de estudios</title>
    <meta name="Biorritmos" content="width=device-width; height=device-height; charset=utf-8;">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link href="./stylesheet.css" rel="stylesheet" type="text/css">
</head>
<body>
    &nbsp;&nbsp;&nbsp;<b><u>Combinador de estudios</u></b><br>
    <form method="get" enctype="multipart/form-data">
    <a><label for="emdat1">DD-MM-YYYY-Para-DD-MM-YYYY.png</label></a><br>
    <a><input type="text" style="height:40px;font-size:20px;width:310px" maxlength="60" name="emdat1"></a><br>
    <a><label>DD-MM-YYYY-Para-DD-MM-YYYY.png</label></a><br>
        <a><input type="text" style="height:40px;font-size:20px;width:310px" maxlength="60" name="emdat2"></a><br>
    <b><label for="emComb">Nombre de fichero .png</label></b><br>
    <a><input type="text" style="height:40px;font-size:20px;width:310px" maxlength="254" name="emComb"></a><br><br>
    <a><input type="submit" name="submit" style="height:45px;width:312px;font-size:20px" value="Combinar Imagenes"></a>
    </form>

<?php
    $somUsu = '';
    $somPas = '';
    $somApi = base64_encode("$somUsu:$somPas");
    if(isset($_GET['emdat1']) && isset($_GET['emdat2']) && isset($_GET['emComb']) || isset($_POST['submit'])) {
       $emdat1 = $_GET['emdat1'];
       $emdat2 = $_GET['emdat2'];
       $emComb = $_GET['emComb'];
       $nomImg = substr($emComb, 0, strpos($emComb, ".png")).".png";
       $dirImg = './consultas/';
       $dirNomImg = $dirImg . $nomImg;

       // Last edit 14-03-2023 00:00
       // Run ImageMagick to emComb the images
       exec("convert $dirImg$emdat1 $dirImg$emdat2 -composite $dirNomImg");

       // Display the result
       echo "<br>&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp<b>Imagen Compuesta</b><br><br>";
       echo "<img id='laimagen' src='" . $dirImg . $nomImg . "' alt='" . $nomImg . "' />";

    
       // append log file
       $emIp = $_SERVER['REMOTE_ADDR'];
       $datLog  = ">".$emIp."<< - >>".$emdat1." - ".$emdat2." - ".$emComb." - ".date("d-m-Y H:i:s :)").PHP_EOL;
       file_put_contents('LOCOMIX.log', $datLog, FILE_APPEND);

       $emUsr = '';
       if (preg_match('~_m_(.*?)_m_~', $emComb, $emUsch)) {
          $emUsr = $emUsch[1];
       }
       $smsNum = '';
       if (preg_match('~_smA_(.*?)_Smi_~', $emComb, $smsNch)) {
          $smsNum = $smsNch[1];
       }
       $remMsg = '';    
       if (preg_match('~_mSg_(.*?)_mSg_~', $emComb, $mstch)) {
          $remMsg = $mstch[1];
       }
    
       // a random hash will be necessary to send mixed content
       $corAle = md5(time());

       // Create and output the PNG image
       $datImg = imagecreatefrompng($nomImg);
       imagePNG($datImg);
       imagepng($datImg, $dirNomImg);

       if ($smsNum !== '' && $somApi !== '') {
          $smsMsg = "https://kamala.pro/bior/consultas/".$nomImg." . ".$remMsg;
          $now = date("d/m/Y");
          $validesa = 60;
          $prioritat = 1;
          $somUrl = 'https://sms.andorratelecom.ad/webtosms/sendSms';
          $smsCap = array(
                   'Content-Type: application/json',
                   'Authorization: Basic ' . $somApi,
                    );
          $somMsg = array(
                    'mobils' => $smsNum,
                    'missatge' => $smsMsg,
                    'data' => $now,
                    'validesa' => $validesa,
                    'prioritat' => $prioritat,
                     );
          $somMsgEnc = json_encode($somMsg);
          $smsCurl = curl_init($somUrl);
          curl_setopt($smsCurl, CURLOPT_POST, true);
          curl_setopt($smsCurl, CURLOPT_POSTFIELDS, $somMsgEnc);
          curl_setopt($smsCurl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($smsCurl, CURLOPT_HTTPHEADER, $smsCap);    
          $somRes = curl_exec($smsCurl);
          curl_close($smsCurl);
          $http_status_som = curl_getinfo($smsCurl, CURLINFO_HTTP_CODE);
          if ($http_status_som == 200) {
             echo "SMS Enviado con Exito !!";
          } else {
             echo "Fallo envio SMS !!";
          }
          $emIp = $_SERVER['REMOTE_ADDR'];
          $smsLog  = ">".$emIp."<< - >>".$smsNum."<< - >>".$remMsg."<< - >>".$somRes." - ".date("d-m-Y H:i:s :)").PHP_EOL;
          file_put_contents('LOCOSMS.log', $smsLog, FILE_APPEND);   
        } else {
       } 

       if ($emUsr !== '') {
          $nowForm = date("d-m-Y H:i:s ");
          $memUsr =  filter_var($emUsr, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
          $emAsu = " Biorritmo https://kamala.pro - ".$emdat1." Combinado con ".$emdat2;
          $emMsg .= "<html><body>";
          $emMsg .= "<p>Este correo ha sido enviado desde el formulario https://kamala.pro powered by https://1wise.es.</p>";
          $emMsg .= "<p>Para el móvil https://kamala.pro/bior/ y para el ordenador https://kamala.pro/biorritmos/.</p>";
          $emMsg .= "<p>Para hacer las consultas directamente https://kamala.pro/bior/bior.php?emdat=01-01-1970-31-12-2023 respetando ese formato.</p>";
          $emMsg .= "<p>From - >>".$emIp." - ".$nowForm."<< - >>".$emComb."<< - >>".$remMsg."<< - Greetings ;)</p>";
          $emMsg .= '<img src="cid:'.$nomImg.'" alt="'.$nomImg.'" />';
          $emMsg .= "</body></html>";
          $emCont = file_get_contents($dirNomImg);
          $emCont = chunk_split(base64_encode($emCont));

          $emCap .= "From: Biorritmos <info@1wise.es>" . PHP_EOL;
          $emCap .= "Cc: " . PHP_EOL . "Bcc: henri@sirkia.es" . PHP_EOL;
          $emCap .= "MIME-Version: 1.0" . PHP_EOL;
          $emCap .= "Content-Type: multipart/mixed; boundary=\"" . $corAle . "\"" . PHP_EOL;
          $emCap .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;
          $emCap .= "This is a MIME encoded message." . PHP_EOL;

          $emCor .= "--" . $corAle . PHP_EOL;
          $emCor .= "Content-Type: text/html; charset=\"utf-8\"" . PHP_EOL;
          $emCor .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;   
          $emCor .= $emMsg . PHP_EOL . PHP_EOL;
          $emCor .= "--" . $corAle . PHP_EOL. PHP_EOL;

          $emCor .= "--" . $corAle . PHP_EOL;
          $emCor .= "Content-Disposition: attachment; filename=\"" . $nomImg . "\"" . PHP_EOL;
          $emCor .= "Content-Type: image/png; name=\"" . $nomImg . "\"" . PHP_EOL;
          $emCor .= "Content-Transfer-Encoding: base64" . PHP_EOL;
          $emCor .= "Content-ID: <" . $nomImg .">". PHP_EOL;
          $emCor .= $emCont . PHP_EOL;
          $emCor .= "--" . $corAle . "--" . PHP_EOL . PHP_EOL;
          mail($memUsr, $emAsu, $emCor, $emCap);
          imagedestroy($datImg);
     } else {
       }
   exit();
   }
?>
</body>
</html>
