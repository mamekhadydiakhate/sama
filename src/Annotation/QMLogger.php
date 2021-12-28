<?php

namespace App\Annotation;


/**
* Annotation for parameter injection in Email contents
*
* @author Mame Khady Diakhate <mamekhady.diakhate@orange-sonatel.com>
*
* @Annotation
*/
final class QMLogger
{
    public $message;

    public function affiche()
    {
//        exit(var_dump($this->message));
    }
}
?>