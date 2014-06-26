<?php
namespace Diggin\HtmlFormatter;


trait HtmlFormatterAwareTrait
{
    private $htmlFormatter;

    public function setHtmlFormatter($htmlFormatter)
    {
        $this->htmlFormatter = $htmlFormatter;
    }

    public function getHtmlFormatter()
    {
        if (!$this->htmlFormatter instanceof HtmlFormatter) {
            $this->htmlFormatter = new HtmlFormatter();
        }

        return $this->htmlFormatter;
    }
} 