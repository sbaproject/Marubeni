<?php
namespace App\Exceptions;

use Exception;

class NotFoundFlowSettingException extends Exception {
    public function __construct()
    {
        parent::__construct(__('msg.application_error_flow_not_found'));
    }
}