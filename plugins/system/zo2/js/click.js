/**
 * Zo2 Framework (http://zo2framework.org)
 *
 * @link     http://github.com/aploss/zo2
 * @package  Zo2
 * @author   Hiepvu
 * @copyright  Copyright ( c ) 2008 - 2013 APL Solutions
 * @license  http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */

!function ($) {
    $(document).ready(function ($) {
        $('.dropdown-toggle').on('click',function(){
            if($(this).parent().hasClass('open') && this.href && this.href != '#' ||
                ($('.btn-navbar').is(':visible'))){
                window.location.href = this.href;
            }
        });
    });
}(jQuery);