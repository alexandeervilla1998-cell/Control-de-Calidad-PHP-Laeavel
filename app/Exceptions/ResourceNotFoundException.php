<?php

namespace App\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    public function __construct($resourceName = 'Recurso')
    {
        parent::__construct("{$resourceName} no encontrado.");
    }

    public function render()
    {
        return response()->json(['error' => $this->message], 404);
    }
}
