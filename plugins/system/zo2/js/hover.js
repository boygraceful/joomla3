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

        var duration = 0;
        var $parent = $('.zo2-megamenu');
        if ($parent.data('duration')) {
            duration = $parent.data('duration');
        }

        if (duration) {
            var css = '.zo2-megamenu.animate .mega > .mega-dropdown-menu {';
            css += 'transition-duration: ' + duration + 'ms;';
            css += '-webkit-transition-duration: ' + duration + 'ms;';
            css += '-ms-transition-duration: ' + duration + 'ms;';
            css += '-o-transition-duration: ' + duration + 'ms;';
            css += '}';
            var style = document.createElement('style');
            style.type = 'text/css';

            if (style.stylesheet) {
                style.stylesheet.cssText = css;
            } else {
                style.appendChild(document.createTextNode(css));
            }
            $('head')[0].appendChild(style);

            var timeout = duration ? duration + 50 : 500;
            $('.nav > li, li.mega').hover(
                function (e) {
                    var $this = $(this);
                    if ($this.hasClass('mega')) {
                        $this.addClass('hovering');
                        clearTimeout($this.data('timeout'));
                        $this.data('timeout', setTimeout(function () {
                            $this.removeClass('hovering')
                        }), timeout);

                        $this.data('hoverTime',
                            setTimeout(function () {
                                $this.addClass('open')
                            }, 100));
                    } else {
                        clearTimeout($this.data('hoverTime'));
                        $this.data('hoverTime',
                            setTimeout(function () {
                                $this.addClass('open')
                            }, 100));
                    }
                },
                function () {
                    var $this = $(this);
                    clearTimeout($this.data('hoverTime'));
                    $this.data('hoverTime',
                        setTimeout(function () {
                            $this.removeClass('open')
                        }, 100));
                }
            );
        }
    });
}(jQuery);