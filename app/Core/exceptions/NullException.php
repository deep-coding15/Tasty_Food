<?php 
namespace App\Core\Exceptions;
class NullException extends \Exception {
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}