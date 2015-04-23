<?php namespace MyAccount\Exceptions;

class FideloperException extends Exception {}

App::error(function(FideloperException $e, $code, $fromConsole)
{
    if ( $fromConsole )
    {
        return 'Error '.$code.': '.$e->getMessage()."\n";
    }

    return '<h1>Error '.$code.'</h1><p>'.$e->getMessage().'</p>';

});