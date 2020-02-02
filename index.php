<?php

use Source\Core\Connect;
use Source\Core\ConnectOracle;
use Source\Core\DocumentsMapper;
use Source\Models\CmBillingDocuments;

require __DIR__ . "/vendor/autoload.php";

// $conn = ConnectOracle::connectOracleDB();

// $sql = file_get_contents("resources/cm_recebimentos_em_aberto.sql");

// //echo getSql("cm_recebimentos_em_aberto");

// $stmt = ConnectOracle::parse($conn, $sql);

// if(ConnectOracle::execute($stmt)){

// $nrows = oci_fetch_all($stmt, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);

//     echo "$nrows rows fetched<br>\n";

//     foreach ($results as $item){
//         $object = (object) $item;
//         var_dump($object);
//     }

// } else {
//     echo "erro ao executar consulta: " . oci_error($con);
// }

// oci_close($conn);

// $cmDocs = new CmBillingDocuments;

// var_dump($cmDocs->all());


try {

    $cmDocs = new CmBillingDocuments;

    $conn = Connect::getInstance();
    
    $conn->beginTransaction();

    DocumentsMapper::setConnection($conn);

    DocumentsMapper::saveDocuments($cmDocs);

    echo "{$cmDocs->getNumResults()} modificado(as).";

    $conn->commit();

} catch (\Exception $e) {
    $conn->rollBack();
    print $e->getMessage();
}