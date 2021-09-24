<html>
    <head>
        <title>ZH</title>
        <meta Http-equiv="Content-type" Content="=text/html; charset=Windows-1252">
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    <body>
    <hr/>
<?php
error_reporting(E_ERROR | E_PARSE);

function getBetween($string, $start, $end){
      $startCharCount = strpos($string, $start) + strlen($start);
      $firstSubStr = substr($string, $startCharCount, strlen($string));
      $endCharCount = strpos($firstSubStr, $end);
      if ($endCharCount == 0) {
          $endCharCount = strlen($firstSubStr);
      }
      return substr($firstSubStr, 0, $endCharCount);
    }


$conn = new mysqli("localhost", "root", "", "adatok");
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

$password = explode("\n", file_get_contents('password.txt'));
$i=0;
while ($i<6){
    $passwordhex[$i]=bin2hex($password[$i]);
    $passwordhex_split[$i] = str_split($passwordhex[$i],2);
    $i=$i+1;
}


for ($i=0;$i<6;$i++){
    $meret=count($passwordhex_split[$i]);
    for ($j=0;$j<$meret;$j++){
        $passworddec[$i][$j] = hexdec($passwordhex_split[$i][$j]);
    }
}

for ($i=0;$i<6;$i++){
    $meret=count($passwordhex_split[$i]);
    for ($j=0;$j<$meret-4;$j++){
        $passworddec2[$i][$j] = $passworddec[$i][$j] - 5;
        $passworddec2[$i][$j+1] = $passworddec[$i][$j+1] + 14;
        $passworddec2[$i][$j+2] = $passworddec[$i][$j+2] - 31;
        $passworddec2[$i][$j+3] = $passworddec[$i][$j+3] + 9;
        $passworddec2[$i][$j+4] = $passworddec[$i][$j+4] - 3;
        $j+=4;
    }
}

for ($i=0;$i<6;$i++){
    $meret=count($passworddec2[$i]);
    for ($j=0;$j<$meret;$j++){

        $darabolt_jelszo[$i][$j] = chr($passworddec2[$i][$j]);
    }
}

$jelszo=implode(" ", array_map('implode', $darabolt_jelszo, array_fill(0, count($darabolt_jelszo),'')));
$jelszo = explode(" ", $jelszo);

if (isset($_POST["username"])){
  $username=$_POST["username"];
} else {
  $username = "";
}
if (isset($_POST["password"])){
  $password=$_POST["password"];
} else {
  $password = "";
}

$start = "*";
$end="";
$meret = count($jelszo);

for ($i=0; $i < $meret; $i++){
  if ($username === getBetween($jelszo[$i],$end,$start) and $password === getBetween($jelszo[$i],$start,$end)) {
    $sql = "select Titkos from tabla where Username = '".$username."'";
    $result = $conn->query($sql);
    $i=7;
    while ($eredmeny= mysqli_fetch_row($result) ){
      switch ($eredmeny[0]) {
        case "piros":
         echo '<body style="background-color:green">';
          break;
        case "zold":
          echo '<body style="background-color:green">';
            break;
        case "sarga":
          echo '<body style="background-color:sarga">';
            break;
        case "kek":
          echo '<body style="background-color:blue">';
            break;
        case "fekete":
          echo '<body style="background-color:black">';
            break;
        case "feher":
          echo '<body style="background-color:white">';
            break;
        default:
          echo "hiba";
          break;
      }
    }
  } else {
      if ($username != getBetween($jelszo[$i],$end,$start) and $username != "" and $i == 5) {
      echo '<script type="text/javascript">';
      echo ' alert("Hibás Felhasználónév")';
      echo '</script>';
    } elseif ($password != getBetween($jelszo[$i],$start,$end) and $password != "" and $i == 5){
      echo '<script type="text/javascript">';
      echo ' alert("Hibás Jelszó")';
      echo '</script>';
      sleep(3);
      header( "location: http://www.police.hu/" ); 
    
    }
  }
}

?>
<div class="login-box">
  <h2>Jelentkezzen Be</h2>
  <form method="POST" action="index.php">
    <div class="user-box">
      <input type="text" name="username" required="">
      <label>Felhasználónév</label>
    </div>
    <div class="user-box">
      <input type="password" name="password" required="">
      <label>Jelszó</label>
      </div>
    <input type="submit" class="login-button" value="Bejelentkezés">
    </div>
  </form>
    </body>
</html>