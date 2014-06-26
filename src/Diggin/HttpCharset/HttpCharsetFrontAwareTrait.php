<?php
/**
 * Created by PhpStorm.
 * User: kazusuke
 * Date: 14/06/27
 * Time: 0:44
 */

namespace Diggin\HttpCharset;


trait HttpCharsetFrontAwareTrait
{

    public function getHttpCharsetFront()
    {
        return new HttpCharsetFront();
    }
} 