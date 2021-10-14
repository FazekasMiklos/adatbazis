<?php
function tanulokListaja($conn)
{
  $sql = "SELECT id, nev, sor, oszlop FROM ulesrend";
  $result = $conn->query($sql);

  return $result;
}
?>