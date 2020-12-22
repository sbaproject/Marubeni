<?php
namespace App\Exceptions\Entertainment;

use Exception;

class NotFoundFlowSettingException extends Exception {
    public function __construct()
    {
        parent::__construct(__('msg.application.error.flow_not_found'));
    }
}