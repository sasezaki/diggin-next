<?php
namespace Diggin\HtmlFormatter;

interface HtmlFormatterInterface
{
    /**
     * @param string $content
     * @return string
     */
    public function format($html);
} 