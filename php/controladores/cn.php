<?php
class cn {
    private $server;
    private $user;
    private $password;
    private $db;

    public function __construct() {
        $this -> server = "localhost";
        $this -> user = "root";
        $this -> password = "boxi123";
        $this -> db = "administracion_prestamos_materiales_itca";
    }

    public function cn() {
        return new mysqli($this -> server, $this -> user, $this -> password, $this -> db);
    }
}
?>