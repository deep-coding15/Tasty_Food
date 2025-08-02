<?php 
namespace App\Core\Exceptions;
class TypeUserException extends \Exception {
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}