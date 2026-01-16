<?php
require_once __DIR__.'/modelos/usuario.php';
session_start();
session_destroy();

header('Location: index.php');