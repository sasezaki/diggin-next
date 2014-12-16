<?php
namespace Diggin\HttpCharset;

class HttpCharsetFront
{
    public function detect($body)
    {
        return 'UTF-8';
    }

    public function convert($content)
    {
        return $content;
    }

}