<?php
    // Biorhythm by 1wise.es
    // http://kamala.pro/biorritmos
    // http://1wise.es
    //
    // Last edit 04-04-03-2023 00:00
    //
    // Print a standard page header
    //
 
function sense_sms($remMsg, $nomImg, $smsNum, $somApi) {
      global $remMsg, $nomImg, $smsNum, $somApi;
      $emIp = $_SERVER['REMOTE_ADDR'];
      $datUrl = "https://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
      $dirImg = "/consultas/";
      $smsMsg = $datUrl.$dirImg.$nomImg." . ".$remMsg;
      $http_status_som = '';
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
        $status_som = curl_getinfo($smsCurl, CURLINFO_HTTP_CODE);
        curl_close($smsCurl);
        $smsLog  = ">".$emIp."<< - >>".$smsNum."<< - >>".$remMsg."<< - >>".$somRes." - ".date("d-m-Y H:i:s :)").PHP_EOL;
        file_put_contents('LOCOSMS.log', $smsLog, FILE_APPEND); 
        
        if ($status_som === 200) {
            echo "SMS Enviado con Exito !!".$somRes."<br>";
        } else {
            echo "Fallo envio SMS !!<br>";
        }
}
?> 
