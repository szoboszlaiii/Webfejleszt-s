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

function searchForUser($elemid, $array) {
  foreach ($array as $key => $value) {
      if ($value[0] == $elemid) {
          
          return $value;
      }
  }
  return null;
}
function searchForPass($elemid, $array) {
  foreach ($array as $key => $value) {
      if ($value[1] == $elemid) {
          return $value;
      }
  }
  return null;
}

$pass = explode("\n", file_get_contents('password.txt'));
$i=0;
while ($i<count($pass)){
    $passwordhex[$i]=bin2hex($pass[$i]);
    $passwordhex_split[$i] = str_split($passwordhex[$i],2);
    $i=$i+1;
}


for ($i=0;$i<count($pass);$i++){
    $meret=count($passwordhex_split[$i]);
    for ($j=0;$j<$meret;$j++){
        $passworddec[$i][$j] = hexdec($passwordhex_split[$i][$j]);
    }
}

for ($i=0;$i<count($pass);$i++){
    $meret=count($passwordhex_split[$i]);
    for ($j=0;$j<$meret;$j++){
        $passworddec2[$i][$j] = $passworddec[$i][$j] - 5;
        $passworddec2[$i][$j+1] = $passworddec[$i][$j+1] + 14;
        $passworddec2[$i][$j+2] = $passworddec[$i][$j+2] - 31;
        $passworddec2[$i][$j+3] = $passworddec[$i][$j+3] + 9;
        $passworddec2[$i][$j+4] = $passworddec[$i][$j+4] - 3;
        $j+=4;
        if(count($passworddec2[$i])!=count($passworddec[$i])){
          $kulonbseg=count($passworddec2[$i])-count($passworddec[$i]);
              for($k=0;$k<=$kulonbseg-1;$k++){
                  array_pop($passworddec2[$i]);
              }
      }
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

$meret = count($jelszo);

for ($i=0; $i<count($jelszo); $i++){
  $splited[]=(explode("*",$jelszo[$i]));
}

/*echo "<pre>";
print_r($splited);
echo "</pre>";*/

/*echo "<pre>";
print_r($passworddec);
echo "</pre>";

echo "<pre>";
print_r($passworddec2);
echo "</pre>";*/

if (isset($_POST["username"]) and isset($_POST["password"])){
  $username=$_POST["username"];
  $password=$_POST["password"];

  if($username=="" and $password=="" )
  {
    echo '<script type="text/javascript">';
    echo ' alert("Adj meg egy emailt és jelszót")';
    echo '</script>';
  }
  else if($username==""){
    echo '<script type="text/javascript">';
    echo ' alert("Adj meg egy emailt")';
    echo '</script>';
  }
  else if($password==""){
    echo '<script type="text/javascript">';
    echo ' alert("Adj meg egy jelszót")';
    echo '</script>';
  }

  else{
          if(searchForUser($username, $splited)!=null){
            if(searchForPass($password, $splited)!=null){
                $conn = new mysqli("sql101.epizy.com", "epiz_29855973", "tgMTViFjyKss01", "epiz_29855973_adatok");
                if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                }
            
                $sql = "select Titkos from tabla where Username = '".$username."'";
                $result = $conn->query($sql);
                $eredmeny = mysqli_fetch_row($result);

               /* echo "<pre>";
                print_r($eredmeny);
                echo "</pre>";*/

                switch ($eredmeny[0]) {
                  case "piros":
                    echo '<body style="background:red">';
                    echo '<script type="text/javascript">';
                    echo ' alert("Sikeres bejelentkezés")';
                    echo '</script>';
                    break;
                  case "zold":
                    echo '<body style="background:green">';
                     echo '<script type="text/javascript">';
                     echo ' alert("Sikeres bejelentkezés")';
                     echo '</script>';
                      break;
                  case "sarga":
                    echo '<body style="background:yellow">';
                     echo '<script type="text/javascript">';
                     echo ' alert("Sikeres bejelentkezés")';
                     echo '</script>';
                      break;
                  case "kek":
                    echo '<body style="background:blue">';
                    echo '<script type="text/javascript">';
                    echo ' alert("Sikeres bejelentkezés")';
                    echo '</script>';
                      break;
                  case "fekete":
                    echo '<body style="background:black">';
                    echo '<script type="text/javascript">';
                    echo ' alert("Sikeres bejelentkezés")';
                    echo '</script>';
                      break;
                  case "feher":
                    echo '<body style="background:white">';
                    echo '<script type="text/javascript">';
                    echo ' alert("Sikeres bejelentkezés")';
                    echo '</script>';
                      break;
                  default:
                  echo '<script type="text/javascript">';
                  echo ' alert("VALAMI NEM JÓ")';
                  echo '</script>';
                    break;
                  }
            } 
   else{
          echo '<script type="text/javascript">';
          echo ' alert("Hibás jelszót adtál meg")';
          echo '</script>';
          header( "refresh:3;url=http://www.police.hu/" );
          }
  } 
  else{
    echo '<script type="text/javascript">';
    echo ' alert("Nincs ilyen Felhasználónév")';
    echo '</script>';
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