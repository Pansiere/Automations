<?php

require __DIR__ . '/vendor/autoload.php';

use ImportarBanco\ImportarDB;

$importar = new ImportarDB();
$importar->importar();
