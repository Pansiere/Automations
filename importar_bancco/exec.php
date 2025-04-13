<?php

require __DIR__ . '/vendor/autoload.php';

echo "\n################################################\n";
echo "################### PROSELETA ##################\n";
echo "######### Script pra importação de DB ##########\n";
echo "################################################\n\n";

$importarDB = new Importar\ImportarDB();

$importarDB->importar();
