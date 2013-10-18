/**
 * Zo2 (http://www.zo2framework.org)
 * A powerful Joomla template framework
 *
 * @link        http://www.zo2framework.org
 * @link        http://github.com/aploss/zo2
 * @author      Duc Nguyen <ducntv@gmail.com>
 * @author      Hiepvu <vqhiep2010@gmail.com>
 * @copyright   Copyright (c) 2013 APL Solutions (http://apl.vn)
 * @license     GPL v2
 */


jQuery(document).ready(function() {
    Zo2Social.toggleButtons();
});

var Zo2Social = {

    toggleButtons : function() {

        jQuery('.social-onoff > label').click(function() {
            var $this = jQuery(this);
            var $parent = $this.parent();
            var $input = $parent.find('input[type=radio]');
            if ($parent.hasClass('toggle-off')) {
                $parent.find('.off').removeClass('active btn-danger');
                $parent.find('.on').addClass('active btn-success');
                $input.prop('value', 1);
                $parent.removeClass('toggle-off');

            } else {

                $parent.find('.off').addClass('active btn-danger');
                $parent.find('.on').removeClass('active btn-success');
                $input.prop('value', 0);
                $parent.addClass('toggle-off');

            }
        });

    },
    updateIndex: function (e, ui) {
        jQuery('td.index', ui.item.parent()).each(function (i) {
            jQuery(this).html(i + 1);
        })
    }
}