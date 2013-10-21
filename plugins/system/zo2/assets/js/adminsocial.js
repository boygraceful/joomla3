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

var Zo2Social = window.Zo2Social || {};

!function($){

    $.extend(Zo2Social, {

        toggleButtons : function() {

            $('.social-onoff > label').click(function() {
                var $this = $(this);
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
            $('td.index', ui.item.parent()).each(function (i) {
                $(this).html(i + 1);
            })
        },

        saveConfig: function (e, ui) {

            var $items = [];
            $(ui.item.parent().children()).each(function(e) {
                var $item = Zo2Social.getRow(this);
                $items.push($item);
            });
            console.log($items);
            var $json = Zo2Social.encodeJSON($items);
            if ($json) {
                $('#jform_params_social_order').val($json);
            }

            return true;
        },

        getRow: function(e) {

            var $item = {};
            // columns
            var $columns = $(e).children();
            //console.log($columns);
            $item.name = $($columns[0]).attr('name');
            $item.index = parseInt($($columns[1]).text());
            $item.website = $($columns[2]).find('a').text();
            $item.link = $($columns[2]).find('a').attr('href');
            $item.enable = parseInt($($columns[3]).find('input[type=radio]').val());
            $item.button_design = $($columns[4]).find('select[name='+$item.name+'_button_design]').val();

            return $item;
        },

        encodeJSON: function($items) {

            if (JSON && JSON.stringify) {
                $items = JSON.stringify($items);
                return $items;
            } else {
                return $items;
            }

        },

        decodeJSON: function ($json) {
            return  jQuery.parseJSON($json);
        }

    });

    $(document).load({

    });

    $(document).ready(function() {
        Zo2Social.toggleButtons();
    });

}(jQuery)
