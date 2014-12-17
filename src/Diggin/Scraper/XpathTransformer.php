<?php
namespace Diggin\Scraper;

use Zend\Dom\Css2Xpath;

class XpathTransformer
{
	public function xpathOrCss2Xpath($exp)
    {
        if (preg_match('/^id\(/', $exp)) {
            return preg_replace("/^id\(((?:'|\")(\w*)(?:'|\"))\)/", '//*[@id=$1]', $exp);
        } elseif (preg_match('#^(?:\.$|\./)#', $exp)) {
            return $exp;
        } elseif (preg_match('!^/!', $exp)) {
            return '.'.$exp;
        } else {
            if (ctype_alnum($exp)) {
                return ".//$exp";
            } else {
                return '.'.preg_replace('#//+#', '//', str_replace(chr(32), '', Css2Xpath::transform($exp)));
            }
        }
    }
}
