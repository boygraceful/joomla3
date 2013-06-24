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
        var hover_type = $parent.data('hover');
        if ($parent.data('duration')) {
            duration = $parent.data('duration');
        }
        if (duration && (hover_type == 'hover')) {
            var timeout = duration ? duration + 50 : 500;
            $('.nav > li, li.mega').hover(
                function(e) {
                    onMouseIn(this, timeout);
                }
                ,
                function (e) {
                    onMouseOut(this);
                }
        );
        } else if (hover_type == 'click') {

            $('.dropdown-toggle').on('click',function(){
                if($(this).parent().hasClass('open') && this.href && this.href != '#'){
                    window.location.href = this.href;
                }
            });

            $('.mega-nav').find('.dropdown-submenu').hover(
                function(e) {
                    onMouseIn(this, 100);
                }
                ,
                function (e) {
                    onMouseOut(this);
                }
            );

        }

        function onMouseIn (e, timeout) {

            var $this = $(e);
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

        }

        function onMouseOut (e) {
            var $this = $(e);
            clearTimeout($this.data('hoverTime'));
            $this.data('hoverTime',
                setTimeout(function () {
                    $this.removeClass('open')
                }, 100));

        }
    });
}(jQuery);