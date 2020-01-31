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
    public static $sql;

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

    protected function load()
    {
        try {

            $conn = ConnectOracle::connectOracleDB();

            $stmt = ConnectOracle::parse($conn, self::$sql);

            if (!ConnectOracle::execute($stmt)) {
                throw new Exception("Erro na execução"); 
            }

            $rows = oci_fetch_all($stmt, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);

            $array = array();

            if ($rows>0) {
                foreach ($results as $item){
                    $array[] = (object) $item;
                }
            }

            ConnectOracle::closeCMConnection();
            
            return $array;

        } catch (Exception $e) {
            $this->fail = $e;
            ConnectOracle::closeCMConnection();
            return null;
        }
    }

}
