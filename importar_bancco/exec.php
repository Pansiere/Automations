<?php

require __DIR__ . '/vendor/autoload.php';

echo "\n################## PROSELETA ##################\n";
echo "######## Script pra importação de DB ##########\n\n";

$importarDB = new Importar\ImportarDB();

$importarDB->importar();
