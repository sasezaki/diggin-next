<?php

/**
 * DigginHttpCharset
 *
 * a part of this package (Diggin_Http_Charset_Detector_Html) is
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

namespace Diggin\HttpCharset;

final class CharsetEncoding
{
    final private function __construct(){}

    final public static function convert($str, $to_string, $from_encoding)
    {
        // if not available for mbstring, use iconv.
        if (!in_array($from_encoding, mb_list_encodings())) {
            $str = @iconv($from_encoding, $to_string, $str);
            return $str;
        }
    
        $str = mb_convert_encoding($str, $to_string, $from_encoding);
        return $str;
    }
}
