<?php

if(session_status() === PHP_SESSION_NONE){

    session_start();
}

function login_required($role = null){

    if(!isset($_SESSION['id_user'])){

        header("Location: /apotek-web/login.php");
        exit;
    }

    if($role !== null){

        if($_SESSION['role'] !== $role){

            header("Location: /apotek-web/login.php");
            exit;
        }
    }
}

function flash($type, $message = null){

    return null;
}