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
        if (!$this->htmlFormatter instanceof HtmlFormatterInterface) {
            if (extension_loaded('tidy')) {
                $this->htmlFormatter = new TidyHtmlFormatter();
            } else {
                throw new \Exception("tidy not loaded");
            }
        }

        return $this->htmlFormatter;
    }
} 