<?php
session_start();

require 'db.inc.php';
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
        while ($row = $result->fetch_assoc()) {
          if ($row["sor"] != $sor) {

            if ($sor != 0) echo "</tr>";
            echo '<tr>';
            $sor = $row["sor"];
          }
          if (!$row["nev"]) {
            $plusz .= ' class="empty"';
            echo "<td" . $plusz . ">" . $row["nev"] . "</td>";
          } else {
            $plusz = ' ';
            if (in_array($row["id"], $hianyzok)) $plusz .= ' class="missing"';
            if ($row["id"]==$en) $plusz .= ' id="me"';
            if (in_array($row["oszlop"] - 1, $osszevon[$sor - 1])) $plusz .= 'colspan="2"';
            if (in_array($row["oszlop"] - 1, $von[$sor - 1])) $plusz .= 'rowspan="2"';
            echo '<td' . $plusz . '>' . $row["nev"];
            if (in_array($row["id"], $hianyzok)) echo '<br><a href="ulesrend.php?nem_hianyzo='.$row["id"].'">Nem hiányzó</a>';
            echo "</td>";
          }
        }
      } else {
        echo "0 results";
      }
      $conn->close();
      /*
      foreach($osztaly as $sor => $tomb){
      echo'<tr>';
      foreach($tomb as $oszlop => $tanulo){
         if($tanulo===NULL) echo'<td class="empty"></td>';
         else{
          $plusz =' ';
          if(in_array($oszlop,$hianyzok[$sor])) $plusz .= ' class="missing"';
          if(in_array($oszlop,$en[$sor])) $plusz .= ' id="me"';
          if(in_array($oszlop,$osszevon[$sor])) $plusz .= 'colspan="2"';
          if(in_array($oszlop,$von[$sor])) $plusz .= 'rowspan="2"';
          echo '<td'.$plusz.'>'.$tanulo.'</td>';
         }
      }
      echo'</tr>';
    }*/
      ?>
    </tr>
  </table>
</body>

</html>