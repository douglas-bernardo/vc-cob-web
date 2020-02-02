<?php

namespace Source\Core;

use Source\Models\CmBillingDocuments;

class DocumentsMapper
{
    private static $conn;

    public static function setConnection(\PDO $conn):void
    {
        self::$conn = $conn;
    }

    public static function saveDocuments (CmBillingDocuments $documents)
    {        
        foreach ($documents->all() as $doc) {
            
            $prepare = self::prepare($doc);

            $fields = implode(", ", array_keys($prepare)); 
            $values = implode(", ", array_values($prepare));

            $sql = "INSERT INTO billingDocuments ({$fields}) VALUES ({$values})";
            
            //self::$conn->exec($sql);
            
            print $sql . "<br>\n";
        }
    }

    private static function prepare($data)
    {
        $prepared = array();
        foreach ($data as $key => $value) {
            if (is_scalar($value)) {
                $prepared[$key] = self::escape($value);
            }
        }
        return $prepared;
    }

    private static function escape($value)
    {
        if (is_string($value) and (!empty($value))){
            //adiciona \ em aspas
            $value = addslashes($value);
            return "'$value'";
        }
        else if (is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }
        else if ($value !== '') {
            return $value;
        }
        else {
            return "NULL";
        }
    }

}
