<?php
namespace Diggin\HtmlFormatter;

class TidyHtmlFormatter
{
    /**
     * Configuration array, set using the constructor or using ::setConfig()
     *
     * @var array
     * @see http://tidy.sourceforge.net/docs/quickref.html
     */
    protected $config = [
        'tidy' => [
            'output-xhtml' => true, 
            'wrap' => 0
        ],
        'pre_ampersand_escape' => false,
        'url' => null
    ];
    
    
    /**
     * @var array
     */
    private $backup = array();
    /**
     * @var integer
    */
    private $backup_count = 0;

    /**
     * $content must UTF-8 
     * 
     * @param string $content
     * @return string
     */
    public function format($html)
    {
        return $this->getXhtml($html);
    }
    
    public function getXhtml($responseBody)
    {
        /*
         * Initialize the backups.
         */
        $this->backup = array();
        $this->backup_count = 0;
        /*
         * Removing SCRIPT and STYLE is recommended.
         * The following substitute code will capsulate the content of the tags in CDATA.
         * If use it, be sure that some JavaScript method such as document.write
         * is not compliant with XHTML/XML.
         */
        $tags = array('script', 'style');
        foreach ($tags as $tag) {
            $responseBody = preg_replace("/<$tag\b[^>]*?>.*?<\/$tag\b[^>]*?>/si", '' , $responseBody);
        }
        /*
         * Backup CDATA sections for later process.
         */
        $responseBody = preg_replace_callback(
            '/<!\[CDATA\[.*?\]\]>/s', array($this, 'backup'), $responseBody
        );
        /*
         * Comment section must not contain two or more adjacent hyphens.
        */
        $responseBody = preg_replace_callback(
            '/<!--(.*?)-->/si',
            create_function('$matches', '
                return "<!-- ".preg_replace("/-{2,}/", "-", $matches[1])." -->";
            '),
            $responseBody
        );
        /*
         * Backup comment sections for later process.
        */
        $responseBody = preg_replace_callback(
            '/<!--.*?-->/s', array($this, 'backup'), $responseBody
        );
        /*
         * Process tags that is potentially dangerous for XML parsers.
        */
        $responseBody = preg_replace_callback(
            '/(<textarea\b[^>]*?>)(.*?)(<\/textarea\b[^>]*?>)/si',
            create_function('$matches', '
                return $matches[1].str_replace("<", "&lt;", $matches[2]).$matches[3];
            '),
            $responseBody
        );
        $responseBody = preg_replace_callback(
            '/<xmp\b[^>]*?>(.*?)<\/xmp\b[^>]*?>/si',
            create_function('$matches', '
                return "<pre>".str_replace("<", "&lt;", $matches[1])."</pre>";
            '),
            $responseBody
        );
        $responseBody = preg_replace_callback(
            '/<plaintext\b[^>]*?>(.*)$/si',
            create_function('$matches', '
                return "<pre>".str_replace("<", "&lt;", $matches[1])."</pre>";
            '),
            $responseBody
        );
        /*
         * Remove DTD declarations, wrongly placed comments etc.
         * This must be done before removing DOCTYPE.
        */
        $responseBody = preg_replace('/<!(?!DOCTYPE)[^>]*?>/si', '', $responseBody);
        /*
         * XML and DOCTYPE declaration will be replaced.
        */
        $responseBody = preg_replace('/<!DOCTYPE\b[^>]*?>/si', '', $responseBody);
        $responseBody = preg_replace('/<\?xml\b[^>]*?\?>/si', '', $responseBody);
        if (preg_match('/^\s*$/s', $responseBody)) {
            throw new Exception\UnexpectedValueException('The entity body became empty after preprocessing.');
        }
        /*
         * Restore CDATAs and comments.
         */
        for ($i = 0; $i < $this->backup_count; $i++) {
            $responseBody = str_replace("<restore count=\"$i\" />", $this->backup[$i], $responseBody);
        }
        /*
         * Use Tidy to format HTML if available.
         * Otherwise, use HTMLParser class (is slower and consumes much memory).
         */
        
        /*
         * Replace every '&' with '&amp;'
         * for XML parser not to break on non-predefined entities.
         * So you may need to replace '&amp;' with '&'
         * to have the original HTML string from returned SimpleXML object.
         *
         * //@see
         * And tidy, it will replace htmlspecialchars('>' '<') to ('&lt;, '&gt;'')
         * if not as Html Tag for tidy.
         * so, "str_replace('&')" before tidy.
         */
        
        
        if ($this->config['pre_ampersand_escape']) {
            $responseBody = str_replace('&', '&amp;', $responseBody);
        }
        $tidy = new \tidy();
        $tidy->parseString($responseBody, $this->config['tidy'], 'UTF8');
        $tidy->cleanRepair();
        $responseBody = $tidy->html();
        
        /*
         * Valid XHTML DOCTYPE declaration (with DTD URI) is required
         * for SimpleXMLElement->asXML() method to produce proper XHTML tags.
         */
        $declarations = '<?xml version="1.0" encoding="UTF-8"?>';
        $declarations .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" ';
        $declarations .= '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        
        return "$declarations$responseBody";
    }
    
    /**
     * backup (Html and Xml comment)
     *
     * @param  array   $matches
     * @return string
     */
    private function backup($matches)
    {
        $this->backup[] = $matches[0];
        $replace = "<restore count=\"{$this->backup_count}\" />";
        $this->backup_count++;
    
        return $replace;
    }
} 