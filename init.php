<?php

function connectToDb() {
    return mysqli_connect('localhost', 'root', 'root', 'doingsdone');
}