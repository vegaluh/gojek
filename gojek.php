<?php
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);
if (!file_exists('token')) {
    mkdir('token', 0777, true);
}
// include ("function.php");
function request($url, $token = null, $data = null, $pin = null){

$header[] = "Host: api.gojekapi.com";
$header[] = "User-Agent: okhttp/3.10.0";
$header[] = "Accept: application/json";
$header[] = "Accept-Language: id-ID";
$header[] = "Content-Type: application/json; charset=UTF-8";
$header[] = "X-AppVersion: 3.30.2";
$header[] = "X-UniqueId: ".time()."57".mt_rand(1000,9999);
$header[] = "Connection: keep-alive";
$header[] = "X-User-Locale: id_ID";
$header[] = "X-Location: -6.246023,106.963373";
$header[] = "X-Location-Accuracy: 3.0";
if ($pin):
$header[] = "pin: $pin";
    endif;
if ($token):
$header[] = "Authorization: Bearer $token";
endif;
$c = curl_init("https://api.gojekapi.com".$url);
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    if ($data):
    curl_setopt($c, CURLOPT_POSTFIELDS, $data);
    curl_setopt($c, CURLOPT_POST, true);
    endif;
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_HEADER, true);
    curl_setopt($c, CURLOPT_HTTPHEADER, $header);
    $response = curl_exec($c);
    $httpcode = curl_getinfo($c);
    if (!$httpcode)
        return false;
    else {
        $header = substr($response, 0, curl_getinfo($c, CURLINFO_HEADER_SIZE));
        $body   = substr($response, curl_getinfo($c, CURLINFO_HEADER_SIZE));
    }
    $json = json_decode($body, true);
    return $json;
}
function save($filename, $content)
{
    $save = fopen($filename, "a");
    fputs($save, "$content\r\n");
    fclose($save);
}
function nama()
    {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://ninjaname.horseridersupply.com/indonesian_name.php");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $ex = curl_exec($ch);
    // $rand = json_decode($rnd_get, true);
    preg_match_all('~(&bull; (.*?)<br/>&bull; )~', $ex, $name);
    return $name[2][mt_rand(0, 14) ];
    }


    function fetch_value($str,$find_start,$find_end) {
    $start = @strpos($str,$find_start);
    if ($start === false) {
        return "";
    }
    $length = strlen($find_start);
    $end    = strpos(substr($str,$start +$length),$find_end);
    return trim(substr($str,$start +$length,$end));
}function color($color = "default" , $text)
    {
        $arrayColor = array(
            'grey'      => '1;30',
            'red'       => '1;31',
            'green'     => '1;32',
            'yellow'    => '1;33',
            'blue'      => '1;34',
            'purple'    => '1;35',
            'nevy'      => '1;36',
            'white'     => '1;0',
        );  
        return "\033[".$arrayColor[$color]."m".$text."\033[0m";
    }

function register($no)
    {
    $nama = nama();
    $email = str_replace(" ", "", $nama) . mt_rand(100, 999);
    $data = '{"email":"'.$email.'@gmail.com","name":"'.$nama.'","phone":"+'.$no.'","signed_up_country":"ID"}';
    $register = request("/v5/customers", "", $data);
    if ($register['success'] == 1)  
        {
        return $register['data']['otp_token'];
        }
      else
        {
      save("error_log.txt", json_encode($register));
        return false;
        }
    }


    function login($no)
    {

    $data = '{"phone":"+'.$no.'"}';
    $register = request("/v4/customers/login_with_phone", "", $data);
    //print_r($register);
    if ($register['success'] == 1)
        {
        return $register['data']['login_token'];
        }
      else
        {
      save("error_log.txt", json_encode($register));
        return false;
        }
    }

function veriflogin($otp, $token)
    {
    $data = '{"client_name":"gojek:cons:android","client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e","data":{"otp":"'.$otp.'","otp_token":"'.$token.'"},"grant_type":"otp","scopes":"gojek:customer:transaction gojek:customer:readonly"}';
    $verif = request("/v4/customers/login/verify", "", $data);
    if ($verif['success'] == 1)
        {
        return $verif['data']['access_token'];
        }
      else
        {
      save("error_log.txt", json_encode($verif));
        return false;
        }
    }

function verif($otp, $token)
    {
    $data = '{"client_name":"gojek:cons:android","data":{"otp":"' . $otp . '","otp_token":"' . $token . '"},"client_secret":"83415d06-ec4e-11e6-a41b-6c40088ab51e"}';
    $verif = request("/v5/customers/phone/verify", "", $data);
    if ($verif['success'] == 1)
        {
        return $verif;
        }
      else
        {
      save("error_log.txt", json_encode($verif));
        return false;
        }
    }
function claim($token)
    {
    $data = '{"promo_code":"GOFOODSANTAI19"}';    
    $claim = request("/go-promotions/v1/promotions/enrollments", $token, $data);
    if ($claim['success'] == 1)
        {
        return $claim['data']['message'];
        }
      else
        {
      save("error_log.txt", json_encode($claim));
        return false;
        }
    }
     function ride($token)
    {
    $data = '{"promo_code":"GOFOODSANTAI11"}';    
    $claim = request("/go-promotions/v1/promotions/enrollments", $token, $data);
    if ($claim['success'] == 1)
        {
        return $claim['data']['message'];
        }
      else
        {
      save("error_log.txt", json_encode($claim));
        return false;
        }
    }
    function coba($token)
    {
    $data = '{"promo_code":"GOFOODSANTAI08"}';    
    $claim = request("/go-promotions/v1/promotions/enrollments", $token, $data);
    if ($claim['success'] == 1)
        {
        return $claim['data']['message'];
        }
      else
        {
      save("error_log.txt", json_encode($claim));
        return false;
        }
    }
    function gpc($token)
    {
    $data = '{"promo_code":"COBAINGOJEK"}';    
    $claim = request("/go-promotions/v1/promotions/enrollments", $token, $data);
    if ($claim['success'] == 1)
        {
        return $claim['data']['message'];
        }
      else
        {
      save("error_log.txt", json_encode($claim));
        return false;
        }
    }
function transfer($qrid, $token, $amount, $pin)
    {
        $data = '{"amount":"'.$amount.'","description":null,"qr_id":"'.$qrid.'"}';
        return request("/v2/fund/transfer", $token, $data, $pin);
    }

echo color("red","   =====================================\n");
echo color("red","  |            V E G A L U H              |\n");
echo             "  |  Timezone  : ".date('d-m-Y H:i:s')."    |\n";
echo             "   =====================================\n\n";

echo color("nevy","[?] Nomor ? : ");
$nohp = trim(fgets(STDIN));
        $nohp = str_replace("62","62",$nohp);
        $nohp = str_replace("(","",$nohp);
        $nohp = str_replace(")","",$nohp);
        $nohp = str_replace("-","",$nohp);
        $nohp = str_replace(" ","",$nohp);

        if (!preg_match('/[^+0-9]/', trim($nohp))) {
            if (substr(trim($nohp),0,3)=='62') {
                $hp = trim($nohp);
            }
            else if (substr(trim($nohp),0,1)=='0') {
                $hp = '62'.substr(trim($nohp),1);
        }
         elseif(substr(trim($nohp), 0, 2)=='62'){
            $hp = '6'.substr(trim($nohp), 1);
        }
        else{
            $hp = '1'.substr(trim($nohp),0,13);
        }
    }
$register = register($hp);
if ($register == false)
    {
    echo color("red","[!] Nomor sudah terdaftar di gojek!\n");
    }
  else
    {
    echo color("green","[+] Kode verifikasi sudah dikirim\n");
    sleep(1);
    otp:
    echo color("nevy","[?] Enter OTP ? : ");
    $otp = trim(fgets(STDIN));
    $verif = verif($otp, $register);
    if ($verif == false)
        {
        echo color("red","[!] Kode verifikasi salah , Please try again !\n");
        goto otp;
        }
      else
        {
        echo color("green","[+] Kode verifikasi benar\n");
        sleep(3);
        echo color("yellow","[-] Trying to get Access Token...\n");
        sleep(3);
        echo color("yellow","[-] Please wait...\n");
        sleep(3);

        $toket = $verif['data']['access_token'];
        $nama = $verif['data']['customer']['name'];
        $go = 'Gored';
        $food = 'GOFOOD';
        $coba = 'Gored2';
        $gpc = 'Cashback?';
        save("token/"."akun.txt", $toket);
        echo color("grey","[+] Access Token : ".$toket."\n");

        sleep(3);
        echo color("yellow","[-] Trying to redeem ".$food."...\n");
        sleep(3);
        echo color("yellow","[-] Please wait...\n");
        sleep(3);
        $claim = claim($toket);
        if ($claim == false)
            {
            echo color("red","[!] message : Yah, gadapet 20+10k... coba lagi ya...\n");
            sleep(3);
            goto ride;
            }
            else{

                echo color("grey","[+] Message : ".$claim."\n");
                sleep(3);
                echo color("yellow","[-] Trying to redeem ".$go."...\n");
                sleep(1);
                echo color("yellow","[-] Please wait...\n");
                sleep(3);
                goto gpc;
            }
            ride:
            $claim = ride($toket);
            if ($claim == false ) {
                echo color("red","[!] Message : Yah, gadapet 15+10k... coba lagi ya...\n");
                sleep(3);
                goto coba;
            }
            else{
                echo color("grey","[+] Message : ".$claim."\n");
                sleep(3);
                echo color("yellow","[-] Trying to redeem ".$coba."...\n");
                sleep(1);
                echo color("yellow","[-] Please wait...\n");
                sleep(3);
                goto gpc;
            }
            coba:
            $claim = coba($toket);
            if ($claim == false) {
                 echo color("red","[!] Message : Yah, gadapet 10+10k... ganti nomer aja...\n");
                 goto gpc;
            }
            else{
                echo color("grey","[+] Message : ".$claim."\n");
                sleep(3);
                echo color("yellow","[-] Trying to redeem ".$coba."...\n");
                sleep(1);
                echo color("yellow","[-] Please wait...\n");
                sleep(3);
                goto gpc; 
            }

            gpc:
            $claim = gpc($toket);
            if ($claim == false ) {
                echo color("red","[!] Message : Yah, kuota voucher ini sudah habis atau code voucher-nya sudah tidak berlaku. Coba code lain,ya\n");
                sleep(3);
                //goto opsi;
            }
            else{
                echo color("grey","[+] Message : ".$claim."\n");
                sleep(3);
                echo color("red","[!!]Created With Love By Daff[[!!]\n");
                sleep(1);
                echo color("yellow","[-] Please wait...\n");
                sleep(3);
                //goto opsi;
            }

               

                
        }
    }
    

    
?>
