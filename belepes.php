<?php
session_start();

require 'db.inc.php';
require 'model/ulesrend.php';
//form feldolgozása
$tanulo = new ulesrend;

if(isset($_POST['user'])and isset($_POST['pw'])){
    $loginError = '';
    if(strlen($_POST['user']) == 0) $loginError .= "Nem írtál be felhasználónevet<br>";
    if(strlen($_POST['pw']) == 0) $loginError .= "Nem írtál be jelszót<br>";
    if($loginError == ''){
      $sql = "SELECT id FROM ulesrend WHERE felhasznalonev= '".$_POST['user']."' ";
     if(!$result=$conn->query($sql)) echo $conn->error;
      if($result->num_rows > 0){
  
        if($row=$result->fetch_assoc()){
          $tanulo->set_user($row['id'],$conn);
            if(md5($_POST['pw'])==$tanulo->get_jelszo()){
              $_SESSION["id"]=$row['id'];
              $_SESSION["nev"]=$tanulo->get_nev();
              header('Location: ulesrend.php');
              exit();
            }
            else $loginError .= 'Érvénytelen jelszó';        
        }
      }
      else $loginError .= 'Érvénytelen felhasználónév';
    }
}


?>
<!doctype html>
<html lang="HU">

<head>
  <meta charset="utf-8">
  <title>Belépés</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<?php
$title = "Belépés";
include 'htmlheader.php';
?>
<body>
    <?php
    include 'menu.inc.php';
    ?>
  <table>
    <tr>
    <th colspan="6">
      <?php
      if(!empty($_SESSION["id"])){
         echo "Üdv ".$_SESSION['nev']."!";
         ?>
         <br>
         <form action="belepes.php" method="get">
         <input type="submit" name ="logout" value="Kilépés">
    </form>
         <?php  
      }
      else{
        if(isset($_POST['user'])){
          echo $loginError;
        }
        else echo "<h2>Belépés</h2>";
      ?>
      <form action="belepes.php" method="post">
        Felhasználó: <input type="text" name="user">
        <br>
        Jelszó: <input type="password" name="pw">
    <br>
    <input type="submit">
    </form>
    <?php
    }
    ?>

</th>     
  </table>
</body>

</html>