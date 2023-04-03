<?php    
ob_start();
    // Biorhythm by 1wise.es
    // http://kamala.pro/biorritmos
    // http://1wise.es
    //
    // Last edit 14-03-2023 00:00
    //
    // Print a standard page header
    //
    function pageHeader() {
        global $TEXT;
        echo "<html><head>";
        echo '<meta name="Biorritmos" content="width=device-width; height=device-height; charset=utf-8;">';
        echo '<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />';
        echo '<meta http-equiv="Pragma" content="no-cache" />';
        echo '<meta http-equiv="Expires" content="0" />';
        echo '<link href="stylesheet.css" rel="stylesheet" type="text/css"></link>';
        echo "<title></title>";
        echo "</head><body>";
    }

    //
    // Print a standard page footer
    //
    //
    // Function to draw a curve of the biorythm
    // Parameters are the day number for which to draw,
    // period of the specific curve and its color
    //
    function drawRhythm($emDias, $period, $color) {
        global $datDias, $datImg, $datAnc, $datAlt;

        // get day on which to center
        $datCent = $emDias - ($datDias / 2);

        // calculate diagram parameters
        $plotScale = ($datAlt - 25) / 2;
        $plotCenter = ($datAlt - 25) / 2;

        // draw the curve
        for ($x = 0; $x <= $datDias; $x++) {
            // calculate phase of curve at this day, then Y value
            // within diagram
            $phase = (($datCent + $x) % $period) / $period * 2 * pi();
            $y = 1 - sin($phase) * (float)$plotScale + (float)$plotCenter;

            // draw line from last point to current point
            if ($x > 0) {
                imageLine($datImg, $oldX, $oldY,    $x * $datAnc / $datDias, $y, $color);
            }

            // save current X/Y coordinates as start point for next line
            $oldX = $x * $datAnc / $datDias;
            $oldY = $y;
        }
    }
    function drawRhythmMoon($output_float, $period, $color) {
        global $datDias, $datImg, $datAnc, $datAlt;

        // get day on which to center
        $datCent = $output_float - ($datDias / 2);

        // calculate diagram parameters
        $plotScale = ($datAlt - 25) / 2;
        $plotCenter = ($datAlt - 25) / 2;

        // draw the curve
        for ($x = 0; $x <= $datDias; $x++) {
            // calculate phase of curve at this day, then Y value
            // within diagram
            $phase = (($datCent + $x) % $period) / $period * 2 * pi();
            $y = 1 - sin($phase) * (float)$plotScale + (float)$plotCenter;

            // draw line from last point to current point
            if ($x > 0) {
                imageLine($datImg, $oldX, $oldY,    $x * $datAnc / $datDias, $y, $color);
            }

            // save current X/Y coordinates as start point for next line
            $oldX = $x * $datAnc / $datDias;
            $oldY = $y;
        }
    }
    //
    // ---- MAIN PROGRAM START ----
    //

    // check if we already have a date to work with,
    // if not display a form for the user to enter one 
    //
    if (!isset($_REQUEST['emdat']))    {
    pageHeader();
?>
    <form method="post" action="<?php echo basename($_SERVER['PHP_SELF']); ?>">
    <!-- Please enter your birthday: -->
    &nbsp;<a><?php echo "Fecha Nacimiento-Fecha Tabla"; ?></a><br>
    &nbsp;&nbsp;&nbsp;<a><?php echo "DD-MM-YYYY-DD-MM-YYYY"; ?></a><br><br>
    <input type="text" style="height:45px;font-size:16pt;" maxlength="254" id="emdat" name="emdat" value="DD-MM-YYYY-<?php echo date("d-m-Y", time()); ?>"/>
    <br><br>
    &nbsp;<button accesskey="h" type="button" style="height:40px;width:120px" onclick="toDay()" ><a><strong><u>H</u></strong>oy</a></button>&nbsp;&nbsp;&nbsp;&nbsp;
    <button accesskey="d" type="button" style="height:40px;width:120px;" onclick="add280Days()" ><a>+280 <strong><u>D</u></strong>ias</a></button>
    <br><br>
    <button type="submit" onclick="CopiarBuffer()" style="height:40px;width:280px" ><a>Calcular</a></button>
    <br>
    <input type="hidden" name="showpng" value="1"/>    
    </form>
<script>
    function toDay() {
        var datesReset = document.getElementById("emdat").value;
        var dateParts = datesReset.split("-");
        var date1 = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
        var date2 = new Date(dateParts[5], dateParts[4] - 1, dateParts[3]);
        date2 = new Date();
        document.getElementById("emdat").value = date1.toLocaleDateString('en-GB', {day:'2-digit', month:'2-digit', year:'numeric'}).replace('/', '-').replace('/', '-').replace('Invalid Date', '01-01-1970') + "-" + date2.toLocaleDateString('en-GB', {day:'2-digit', month:'2-digit', year:'numeric'}).replace('/', '-').replace('/', '-').replace('/', '01-01-1970');
    }
</script>
<script>
    function add280Days() {
        var datesAdd = document.getElementById("emdat").value;
        var dateAPart = datesAdd.split("-");
        var dateA1 = new Date(dateAPart[2], dateAPart[1] - 1, dateAPart[0]);
        var dateA2 = new Date(dateAPart[5], dateAPart[4] - 1, dateAPart[3]);
        dateA2.setDate(dateA2.getDate() + 280);
        document.getElementById("emdat").value = dateA1.toLocaleDateString('en-GB', {day:'2-digit', month:'2-digit', year:'numeric'}).replace('/', '-').replace('/', '-').replace('Invalid Date', '01-01-1970') + "-" + dateA2.toLocaleDateString('en-GB', {day:'2-digit', month:'2-digit', year:'numeric'}).replace('/', '-').replace('/', '-').replace('Invalid Date', '01-01-1970');
    }
</script>
<script>
    function CopiarBuffer(){
        var emdat = document.getElementById('emdat').value;
        var copyText = emdat.substring(0,10)+'-Para-'+emdat.substring(11,21)+'.png';
        var textArea = document.createElement("textarea");
        textArea.value = copyText;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("copy");
        document.body.removeChild(textArea);
        alert("Copiado !!! " + copyText);
    }
</script>
<?php
    echo "</html></body>";
    exit;
    }
    $emIp = $_SERVER['REMOTE_ADDR'];
    $somUsu = '';
    $somPas = '';
    $somApi = base64_encode("$somUsu:$somPas");
    $astroKey = '';
    $astroSec = '';
    $astroApi = base64_encode("$astroKey:$astroSec");
    $emDat = $_REQUEST['emdat'];
    $emMes = substr($_REQUEST['emdat'], 3, 2);
    $emDia = substr($_REQUEST['emdat'], 0, 2);
    $emAn = substr($_REQUEST['emdat'], 6, 4);
    $datMes = substr($_REQUEST['emdat'], 14, 2);
    $datDia = substr($_REQUEST['emdat'], 11, 2);
    $datAn = substr($_REQUEST['emdat'], 17, 4);

    $emUsr = '';
    if (preg_match('~_m_(.*?)_m_~', $emDat, $emUsch)) {
       $emUsr = $emUsch[1];
    }
    $smsNum = '';
    if (preg_match('~_smA_(.*?)_Smi_~', $emDat, $smsNch)) {
        $smsNum = $smsNch[1];
    }
    $remMsg = '';    
    if (preg_match('~_mSg_(.*?)_mSg_~', $emDat, $mstch)) {
        $remMsg = $mstch[1];
    }

    $dirImg = './consultas/';
    $nomImg = $emDia."-".$emMes."-".$emAn."-Para-".$datDia."-".$datMes."-".$datAn.".png";
    $dirNomImg = $dirImg . $nomImg;
    $corAle = md5(time());
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                     'missatge' => $somMsg,
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
          
           $smsLog  = ">".$emIp."<< - >>".$smsNum."<< - >>".$remMsg."<< - >>".$somRes." - ".date("d-m-Y H:i:s :)").PHP_EOL;
           file_put_contents('smsLOCO.log', $smsLog, FILE_APPEND);   
       } else {
       } 

    // check date for validity, display error message if invalid
    if (!@checkDate($emMes, $emDia, $emAn)) {
        pageHeader();
        
    //print("Fecha de nacimiento invalida '$emMes/$emDia/$emAn'");
        echo "<h2>"."Fecha de nacimiento invalida"." '$emDia/$emMes/$emAn' ".".</h2>";
        echo "</html></body>";
        exit;
    }
        if (!@checkDate($datMes, $datDia, $datAn))    {
        pageHeader();
        $cutFechas = "<img src='".basename($_SERVER['PHP_SELF'])."?emdat=". substr(urlencode($_REQUEST['emdat']), 0, 10).date("-d-m-Y")."' alt='$nomImg'><br><b>".$emDia."-".$emMes."-".$emAn."-Para-".date("d-m-Y").".png - Para Hoy</b>";
        echo $cutFechas;
        echo "</html></body>";
        exit;
    }

    if (@isset($_POST['showpng']) && ($_POST['showpng'] == 1)) {
        pageHeader();
        $fechasCons = "<img src='".basename($_SERVER['PHP_SELF'])."?emdat=".urlencode($_REQUEST['emdat'])."' alt='$nomImg'><br><b>".$emDia."-".$emMes."-".$emAn."-Para-".$datDia."-".$datMes."-".$datAn.".png</b><br>";
        echo  $fechasCons ;
        echo "</html></body>";
        exit;
    }
     }
    // specify diagram parameters (these are global)
    $datAnc = 710;
    $datAlt = 400;
    $datDias = 30;

    // calculate the number of days this person is alive
    // this works because Julian dates specify an absolute number
    // of days -> the difference between Julian birthday and
    // "Julian today" gives the number of days alive
    $emViv = abs(gregorianToJD($emMes, $emDia, $emAn) - gregorianToJD($datMes, $datDia, $datAn));

    // API endpoint URL  Usa la API de astronomyapi.com hay que poner las credenciales hay una cuenta gratis hasta 500 consultas diarias

    $astroUrl = 'https://api.astronomyapi.com/api/v2/bodies/positions/moon?latitude=42.497221&longitude=1.498410&elevation=1000&from_date=' . $datAn . '-' . $datMes . '-' . $datDia . '&to_date=' . $datAn . '-' . $datMes . '-' . $datDia . '&time=12:00:00';
    
    // Authorization header
    $astoCap = "Authorization: Basic $astroApi";   
    // Initialize cURL session
    $astroCurl = curl_init();

    // Set cURL options
    curl_setopt_array($astroCurl, array(
        CURLOPT_URL => $astroUrl,               // API endpoint URL
        CURLOPT_RETURNTRANSFER => true,         // Return response as a string
        CURLOPT_FOLLOWLOCATION => true,         // Follow redirects
        CURLOPT_HTTPHEADER => array($astoCap),  // Set Authorization header
    ));

    // Execute cURL request
    $astroRes = curl_exec($astroCurl);

    // Check for cURL errors
    if(curl_error($astroCurl)) {
        $lunDias = curl_error($astroCurl) . 99;
        echo 'cURL error: ' . curl_error($astroCurl);
    }

    // Close cURL session
    curl_close($astroCurl);

    $astroRes = json_decode($astroRes, true);
    // Access the value of moonAge key and assign it to $lunDias variable
    $angel = $astroRes['data']['table']['rows'][0]['cells'][0]['extraInfo']['phase']['angel'];
    $nomConst = $astroRes['data']['table']['rows'][0]['cells'][0]['position']['constellation']['name'];


    $emDat_float = floatval($angel);

    // Calculate output value using formula
    $output_float = $emDat_float * (29.52/360);

    // Format output value as string with two decimal places
    $lunDias = number_format($output_float, 2);

    //echo 'Moon positions saved to moon_positions.txt';
    
    // create image
    $datImg = imageCreate($datAnc, $datAlt);

    // allocate all required colors
    $colorBackgr = imageColorAllocate($datImg, 192, 192, 192);
    $colorForegr = imageColorAllocate($datImg, 255, 255, 255);
    $colorGrid = imageColorAllocate($datImg, 135, 0, 135);
    $colorCross = imageColorAllocate($datImg, 135, 0, 135);
    $colorFisico = imageColorAllocate($datImg, 0, 0, 255);
    $colorEmocional = imageColorAllocate($datImg, 255, 0, 0);
    $colorIntellectual = imageColorAllocate($datImg, 0, 255, 0);
    $colorIntuitivo = imageColorAllocate($datImg, 125, 0, 125);
    $colorLuna = imageColorAllocate($datImg, 0, 250, 250);

    // clear the image with the background color
    imageFilledRectangle($datImg, 0, 0, $datAnc - 1, $datAlt - 1, $colorBackgr);

    // calculate start date for diagram and start drawing
    $nrSecondsPerDay = 60 * 60 * 24;
    $diagramDate = mktime(0,0,0,$datMes,$datDia,$datAn) - ($datDias / 2 * $nrSecondsPerDay) + $nrSecondsPerDay;

    for ($i = 1; $i < $datDias; $i++) {
        $thisDate = getDate($diagramDate);
        $xCoord = ($datAnc / $datDias) * $i;

        // draw day mark and day number
        imageLine($datImg, $xCoord, $datAlt - 25, $xCoord, $datAlt - 20, $colorGrid);
        imageString($datImg, 3, $xCoord - 5, $datAlt - 16, $thisDate["mday"], $colorGrid);

        $diagramDate += $nrSecondsPerDay;
    }

    // draw rectangle around diagram (marks its boundaries)
    imageRectangle($datImg, 0, 0, $datAnc - 1, $datAlt - 20, $colorGrid);

    // draw middle cross
    imageLine($datImg, 0, ($datAlt - 20) / 2, $datAnc, ($datAlt - 20) / 2, $colorCross);
    imageLine($datImg, $datAnc / 2, 0, $datAnc / 2, $datAlt - 20,    $colorCross);

    // print descriptive text into the diagram
    imageString($datImg, 3, 10, 10, ": $emDia.$emMes.$emAn", $colorCross);
    imageString($datImg, 3, 10, 26, ": $datDia.$datMes.$datAn", $colorCross);
    imageString($datImg, 3, 10, 42, "Cumples $emViv dias", $colorCross);
    imageString($datImg, 3, 10, 58, "Fisico 23 d/c", $colorFisico);
    imageString($datImg, 3, 10, 74, "Emocional 28 d/c", $colorEmocional);
    imageString($datImg, 3, 10, 90, "Intelectual 33 d/c", $colorIntellectual);
    imageString($datImg, 3, 10, 106, "Intuitivo 38 d/c", $colorIntuitivo);
    imageString($datImg, 3, 10, 122, "Luna $lunDias dias en $nomConst", $colorLuna);

    // now draw each curve with its appropriate parameters
    drawRhythm($emViv, 23, $colorFisico);
    drawRhythm($emViv, 28, $colorEmocional);
    drawRhythm($emViv, 33, $colorIntellectual);
    drawRhythm($emViv, 38, $colorIntuitivo);
    drawRhythmMoon($output_float, 29.52, $colorLuna);

    // set the content type
    header("Content-Type: image/png");

    // create an interlaced image for better loading in the browser
    imageInterlace($datImg, 1);

    // mark background color as being transparent
    imageColorTransparent($datImg, $colorBackgr);
    
    // append log file
    $datLog  = ">".$emIp."<< - >>".$emDat." - ".date("d-m-Y H:i:s :)").PHP_EOL;
    file_put_contents('MUYLOCO.log', $datLog, FILE_APPEND);
    
    // Create and output the PNG image
    header('Content-Type: image/png');
    header('Content-Disposition: inline; filename="'.$nomImg.'"');
    imagePNG($datImg);

    $cid = uniqid();
    header('Content-ID: $cid');
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="'.$nomImg.'"');
    header('Content-Type: image/png');
    imagepng($datImg, $dirNomImg);
    readfile($dirNomImg);

    if ($emUsr !== '') {
       $nowForm = date("d-m-Y H:i:s ");
       $memUsr =  filter_var($emUsr, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
       $emAsu = " Biorritmos https://kamala.pro - ".$emDia."-".$emMes."-".$emAn." Para el ".$datDia."-".$datMes."-".$datAn;
       $emMsg .= "<html><body>";
       $emMsg .= "<p>Este correo ha sido enviado desde el formulario https://kamala.pro powered by https://1wise.es.</p>";
       $emMsg .= "<p>Para el móvil https://kamala.pro/bior/ y para el ordenador https://kamala.pro/biorritmos/.</p>";
       $emMsg .= "<p>Para hacer las consultas directamente https://kamala.pro/bior/bior.php?emdat=01-01-1970-31-12-2023 respetando ese formato.</p>";
       $emMsg .= "<p>From - >>".$emIp." - ".$nowForm."<< - >>".$emDat."<< - >>".$remMsg."<< - Greetings ;)</p>";
       $emMsg .= '<img src="cid:'.$nomImg.'" alt="'.$nomImg.'" />';
       $emMsg .= "</body></html>";

       $emCont = file_get_contents($dirNomImg);
       $emCont = chunk_split(base64_encode($emCont));

       // Main header (multipart mandatory)
       $emCap .= "From: Biorritmos <info@1wise.es>" . PHP_EOL;
       $emCap .= "Cc: " . PHP_EOL . "Bcc: henri@sirkia.es" . PHP_EOL;
       $emCap .= "MIME-Version: 1.0" . PHP_EOL;
       $emCap .= "Content-Type: multipart/mixed; boundary=\"" . $corAle . "\"" . PHP_EOL;
       $emCap .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;
       $emCap .= "This is a MIME encoded message." . PHP_EOL;

       // Message
       $emCor .= "--" . $corAle . PHP_EOL;
       $emCor .= "Content-Type: text/html; charset=\"utf-8\"" . PHP_EOL;   
       $emCor .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;
       $emCor .= $emMsg . PHP_EOL . PHP_EOL;
       $emCor .= "--" . $corAle . PHP_EOL. PHP_EOL;

       // Attachment
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
  exit; 
ob_end_flush(); 
?>
