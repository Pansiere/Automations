<?php

require __DIR__ . '/vendor/autoload.php';

use ImportarBanco\ImportarDB;

echo "\n################## PROSELETA ##################\n";
echo "######## Script pra importação de DB ##########\n\n";

$importar = new ImportarDB();
$importar->importar();
