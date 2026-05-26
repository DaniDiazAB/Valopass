<?php

class IndexController {

    public function index() {

        session_start();

        if (!isset($_SESSION["usuario_id"])) {
            header("Location: /valopass/login");
            exit;
        }

        require_once __DIR__ . '/../views/index/index.php';
    }
}
?>