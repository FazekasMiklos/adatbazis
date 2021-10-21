<?php
session_start();

require 'db.inc.php';
require 'model/ulesrend.php';
$tanulo = new ulesrend;
require 'functions.inc.php';


//form feldolgozása

if (!empty($_POST["hianyzo_id"])) {
  $sql = "INSERT INTO hianyzok VALUES(" . $_POST["hianyzo_id"] . ")";
  $result = $conn->query($sql);
}
elseif(!empty($_GET['nem_hianyzo'])){
  $sql = "DELETE FROM hianyzok WHERE id =".$_GET['nem_hianyzo'];
  $result = $conn->query($sql);
}

?>
<!doctype html>
<html lang="HU">

<head>
  <meta charset="utf-8">
  <title>Ülésrend</title>
  <link rel="stylesheet" type="text/css" href="style.css">

  <?php
  $hianyzok = array();


  $sql = "SELECT id FROM hianyzok";
  $result = $conn->query($sql);

  if($result->num_rows > 0){
    while($row=$result->fetch_assoc()){
      $hianyzok[]=$row['id'];
    }
  }
$adminok=array();

$sql = "SELECT id FROM adminok";
  $result = $conn->query($sql);

  if($result->num_rows > 0){
    while($row=$result->fetch_assoc()){
      $adminok[]=$row['id'];
    }
  }

  ?>

</head>
<?php
$title = "Belépés";
include 'htmlheader.php';
?>
<body>
<?php
    include 'menu.inc.php';
    ?>
  <?php


/*
  $hianyzok = array(
    array(0, 3),
    array(),
    array(1),
    array()
  );*/

  $en = 0;
  if(!empty($_SESSION["id"])) $en = $_SESSION["id"];

  $osszevon = array(
    array(),
    array(),
    array(),
    array(3, 4)
  );
  $von = array(
    array(1, 2, 3, 4, 5),
    array(1, 2, 3, 4, 5),
    array(),
    array()
  );

  ?>
  <table>
    <tr>
    <th colspan="6">
      <?php
      if(!empty($_SESSION["id"])){
         echo "Üdv ".$_SESSION['nev']."!";
         ?>
         <br>
         <form action="ulesrend.php" method="get">
         <input type="submit" name ="logout" value="Kilépés">
    </form>
         <?php  
      }
      else{
        if(isset($_POST['user'])){
          echo $loginError;
        }
        else echo "Belépés";
      ?>
      <form action="ulesrend.php" method="post">
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
      <th colspan="6">Ülésrend:
       

        <form action="ulesrend.php" method="post">

          Hiányzó: <select name="hianyzo_id">
            <?php
                $result = tanulokListaja($conn);
                if ($result->num_rows > 0) {
                  // output data of each row
                  $sor = 0;
                  while ($row = $result->fetch_assoc()) {
                    if($row["nev"] and !in_array($row['id'],$hianyzok)) echo '<option value="'.$row['id'].'">'.$row['nev'].'</option>';
                  }
                }

            ?>
            

            

          </select>

          <br>
          <input type="submit">
        </form>


      </th>
    </tr>
    <tr>
    <?php

$result = tanulokListaja($conn);

if ($result->num_rows > 0) {
// output data of each row
$sor = 0;
while($row = $result->fetch_assoc()) {
  $tanulo->set_user($row['id'], $conn);
  if($tanulo->get_sor() != $sor) {
    if($sor != 0) echo '</tr>';
    echo '<tr>';
    $sor = $tanulo->get_sor();
  }
  if(!$tanulo->get_nev()) echo '<td class="empty"></td>';
  else {
    $plusz = '';
    if(in_array($row["id"], $hianyzok)) $plusz .=  ' class="missing"';
    if($row["id"] == $en) $plusz .=  ' id="me"';
    echo "<td".$plusz.">" . $tanulo->get_nev();
    if(!empty($_SESSION["id"])) {
      if(in_array($_SESSION["id"], $adminok)) {
        if(in_array($row["id"], $hianyzok)) echo '<br><a href="ulesrend.php?nem_hianyzo='.$row["id"].'">Nem hiányzó</a>';
      }
    }
    echo "</td>";
  }
}
} 
else {
  echo "0 results";
}
$conn->close();

?>
    </tr>
  </table>
</body>

</html>