<?php

function connectToDb() {
    $link = mysqli_connect('localhost', 'root', 'root', 'doingsdone');

    if($link) {
        return $link;
    } else {
        print renderTemplate('templates/error.php', ['error' => mysqli_connect_error()]);
        exit;
    }
}
