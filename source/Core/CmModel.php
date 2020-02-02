<?php

namespace Source\Core;

use Exception;

abstract class CmModel
{
    /** @var object|null */
    protected $data;

    /** @var \Exception|null */
    protected $fail;

    /** @var string|null */
    protected $message;

    /** @var string */
    private static $sql;

    /** @var int|null */
    protected static $nresults;

    public function __construct(string $sql) {
        self::$sql = $sql;
    }

    /**
     * Undocumented function
     *
     * @return object|null
     */
    public function data(): ?object
    {
        return $this->data;
    }

    /**
     * Undocumented function
     *
     * @return Exception|null
     */
    public function fail(): ?\Exception
    {
        return $this->fail;
    }

    /**
     * Undocumented function
     *
     * @return string|null
     */
    public function message(): ?string
    {
        return $this->message;
    }

    protected function load(): array
    {
        try {

            $conn = ConnectOracle::connectOracleDB();
            
            //conversão dos campos com formato de data
            $stmt = ConnectOracle::parse($conn, "ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD'");
            ConnectOracle::execute($stmt);

            $stmt = ConnectOracle::parse($conn, self::$sql);

            if (!ConnectOracle::execute($stmt)) {
                throw new Exception("Erro na execução"); 
            }

            $results = array();

            // $rows = oci_fetch_all($stmt, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);

            // if ($rows>0) {
            //     self::$nresults = $rows;
            // }

            while (($row = oci_fetch_array($stmt, OCI_ASSOC)) != false) {
                $results[] = $row;
            }
            
            self::$nresults = count($results);

            ConnectOracle::closeCMConnection();
            
            //return $array;
            return $results;

        } catch (Exception $e) {
            $this->fail = $e;
            ConnectOracle::closeCMConnection();
            return null;
        }
    }

}
