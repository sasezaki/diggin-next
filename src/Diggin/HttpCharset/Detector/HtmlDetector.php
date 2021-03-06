<?php

/**
 * DigginHttpCharset
 *
 * a part of this package (HtmlDetector) is
 * borrowed from HTMLScraping
 *
 * @see http://www.rcdtokyo.com/etc/htmlscraping/
 *
 * LICENSE: This source file is subject to the GNU Lesser General Public
 * License as published by the Free Software Foundation;
 * either version 2.1 of the License, or any later version
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/lgpl.html
 * If you did not have a copy of the GNU Lesser General Public License
 * and are unable to obtain it through the web, please write to
 * the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA
 * ---------------------------------------------------------------------
 */

namespace Diggin\HttpCharset\Detector;

use Diggin\HttpCharset\Exception;

class HtmlDetector implements DetectorInterface
{
    const DEFAULT_DETECT_ORDER = 'ASCII, JIS, UTF-8, eucJP-win, EUC-JP, SJIS-win, SJIS';

    /**
     * @var string
     */
    private $detectOrder = 'ASCII, JIS, UTF-8, eucJP-win, EUC-JP, SJIS-win, SJIS';

    private $config = [
        'accept_header_ctype' => true,
        'force_detect_body' => false,
        'detect_prefer_mime' => false,
        'iconv_map' => [
            'KS_C_5601-1987' => 'CP949',
        ],
    ];

    /**
     * configure
     *
     * @param array|Traversable
     * @throws Exception\InvalidArgumentException
     */
    public function setConfig($config = array())
    {
        if (! is_array($config) && !$config instanceof \Traversable) {
            throw new Exception\InvalidArgumentException('Array expected, got ' . gettype($config));
        }

        foreach ($config as $k => $v) {
            $this->config[strtolower($k)] = $v;
        }

        return $this;
    }

    /**
     * Set detect-order
     *
     * @param string $order
     */
    public function setDetectOrder($order)
    {
        if ($order === false) {
            $this->detectOrder = self::DEFAULT_DETECT_ORDER;
        } else {
            $this->detectOrder = $order;
        }
    }

    /**
     * Get detecting order
     *
     * @return string
     */
    public function getDetectOrder()
    {
        return $this->detectOrder;
    }

    /**
     * Get list - against mb_preferred_mime_name()
     *
     * @return array
     */
    public function getListAgainstMime()
    {
        return array(
            'UUENCODE' => 'x-uuencode',
            'ASCII' => 'US-ASCII',
            'SJIS' => 'Shift_JIS',
            'eucJP-win' => 'EUC-JP',
            'SJIS-win' => 'Shift_JIS',
            'JIS' => 'ISO-2022-JP',
            'ISO-2022-JP-MS' => 'ISO-2022-JP',
            'EUC-CN' => 'CN-GB',
            'HZ' => 'HZ-GB-2312',
            'BIG-5' => 'BIG5',
        );
    }

    /**
     * Detect response's character code name
     *
     * @param  string $responseBody
     * @param  string $contentType
     * @return string $encoding
     * @throw Diggin\HttpCharset\Exception
     */
    public function detect($responseBody, $contentType = null)
    {
        $encoding = false;
        if (isset($contentType)) {
            $encoding = $this->_getCharsetFromCType($contentType);
        }
        if ((!$encoding or (!$this->config['accept_header_ctype']))
                and preg_match_all('/<meta\b[^>]*?>/si', $responseBody, $matches)) {
            foreach ($matches[0] as $value) {
                $encoding = $this->_getAttribute('charset', $value);
                if ($encoding) {
                    break;
                }
                if (strtolower($this->_getAttribute('http-equiv', $value)) == 'content-type'
                    and false !== $encoding = $this->_getAttribute('content', $value)) {
                    $encoding = $this->_getCharsetFromCType($encoding);
                    break;
                }
            }
        }

        /*
         * detect character encoding
         */
        if ((in_array($encoding, $this->getListAgainstMime())) or (!$encoding or $this->config['force_detect_body'])) {
            $detect = @mb_detect_encoding($responseBody, $this->getDetectOrder());

            /*
             * If previous search failed, do a scan against all known encodings
             * Ideally the user never gets here, because there's a performance penalty,
             * redundancy against previous detection, and the order is not based on
             * our preference.
             */
            if (!$detect) {
                $detect = @mb_detect_encoding($responseBody, mb_list_encodings());
            }

            if ($detect) {
                if (in_array($encoding, $this->getListAgainstMime())) {
                    return $detect;
                }

                if ($this->config['detect_prefer_mime']) {
                    $detect = @mb_preferred_mime_name($detect);
                    if (!$detect) {
                        throw new Exception\DetectException('Failed preferred_mime_name.');
                    }
                }

                return $detect;
            }

            throw new Exception\DetectException('Failed detecting character encoding.');
        }

        if (is_array($this->config['iconv_map']) &&
            in_array(strtoupper($encoding), array_keys($this->config['iconv_map']))) {
            $encoding = $this->config['iconv_map'][strtoupper($encoding)];
        }

        return $encoding;
    }

    /**
     * Get Charset From Ctype
     *
     * @param  string $string
     * @return mixed
     */
    protected function _getCharsetFromCType($string)
    {
        $array = explode(';', $string);
        if (isset($array[1])) {
            $array = explode('=', $array[1]);
            if (isset($array[1])) {
                $charset = trim($array[1]);
                if (preg_match('/^UTF-?8$/i', $charset)) {
                    return 'UTF-8';
                } else {
                    // force preferred_mime_name in CharsetFromCType
                    $preferred = @mb_preferred_mime_name($charset);

                    return ($preferred) ? $preferred : $charset;
                }
            }
        }

        return false;
    }

    /**
     * Get Attribute from meta-tags
     *
     * @param  string $name:
     * @param  string $string:
     * @return mixed
     */
    protected function _getAttribute($name, $string)
    {
        $search = "'[\s\'\"]".'\b'.$name.'\b'."\s*=\s*([^\s\'\">]+|\'[^\']+\'|\"[^\"]+\")'si";
        if (preg_match($search, $string, $matches)) {
            return preg_replace('/^\s*[\'\"](.+)[\'\"]\s*$/s', '$1', $matches[1]);
        } else {
            return false;
        }
    }
}
