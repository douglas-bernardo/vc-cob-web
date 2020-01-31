<?php

namespace Source\Core;

class ConnectOracle
{
    private const TNS = " 
    (DESCRIPTION =
        (ADDRESS_LIST =
          (ADDRESS = (PROTOCOL = TCP)(HOST = " . CONF_DB_HOST . ")(PORT = " . CONF_DB_PORT . "))
        )
        (CONNECT_DATA =
          (SERVICE_NAME = " . CONF_DB_SERVICE_NAME . ")
        )
      )
           ";

    private static $conn;

    public static function connectOracleDB()
    {
        if (empty(self::$conn)) {
            try {

                if(!self::$conn = oci_connect(CONF_DB_USER, CONF_DB_PASS, self::TNS)){
                    $e = oci_error();
                    throw new \Exception("Erro ao conectar ao servidor usando a extensão OCI - " . $e['message']);                    
                }


            } catch (\Exception $exception) {

                echo $exception->getMessage();

            }
        }
        return self::$conn;
    }

    public static function parse($connection, string $sql_text)
    {        
        try {
            if (!$stmt = oci_parse($connection, $sql_text)) {
                $e = oci_error($stmt);
                throw new \Exception("Erro ao preparar consulta - " . $e['message']);
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
        return $stmt;
    }

    public static function execute($statement): bool
    {
        if (!oci_execute($statement)) {
            return false;
        }
        return true;
    }

    public static function closeCMConnection(): void
    {
        oci_close(self::$conn);
    }
}