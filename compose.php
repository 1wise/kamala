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
global $emUsr, $remMsg, $emAsu, $nomImg, $smsNum, $somApi;
require_once './sense_mail.php';
require_once './sense_sms.php';
    // Biorhythm by 1wise.es
    // http://kamala.pro/biorritmos
        // http://1wise.es
    //
    // Last edit 04-04-03-2023 00:00
    //
    // Print a standard page header
    //
    $somUsu = 'CLAU API SOM';
    $somPas = 'CLAU API SOM';
    $somApi = base64_encode("$somUsu:$somPas");
    if(isset($_GET['emdat1']) && isset($_GET['emdat2']) && isset($_GET['emComb']) || isset($_POST['submit'])) {
       $emIp = $_SERVER['REMOTE_ADDR'];
       $emdat1 = $_GET['emdat1'];
       $emdat2 = $_GET['emdat2'];
       $emComb = $_GET['emComb'];
       $nomImg = substr($emComb, 0, strpos($emComb, ".png")).".png";
       $emAsu =  ' Biorritmo https://kamala.pro - ' . $emdat1 . ' Combinado con ' . $emdat2;
       $dirImg = './consultas/';
       $dirNomImg = $dirImg . $nomImg;

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

       if ($emUsr !== '') {
          sense_mail($emUsr, $remMsg, $emAsu, $nomImg);
       }
       if ($smsNum !== '' && $somApi !=='') {
          sense_sms($remMsg, $nomImg, $smsNum, $somApi);
       }

exit();
}
?>
</body>
</html>
