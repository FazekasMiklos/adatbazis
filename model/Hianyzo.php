<?php

require_once 'Kijeloltfelhasznalok.php';

class Hianyzo extends Kijeloltfelhasznalok {
    
    function __construct() {
        $this->tablaNev = 'hianyzok';
    }

    //remote_id metódus elkészítése
    public function remove_id($id, $conn){
        $sql = "DELETE FROM hianyzok WHERE id = $id";
	    $result = $conn->query($sql);	

    }
}

?>