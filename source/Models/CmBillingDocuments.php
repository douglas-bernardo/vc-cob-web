<?php

namespace Source\Models;

use Source\Core\CmModel;

class CmBillingDocuments extends CmModel
{
    public function __construct() 
    {
        $sql = getSql('cm_recebimentos_em_aberto');
        parent::__construct($sql);
    }

    public function all(): array
    {
        return $this->load();
    }
}
