<?php
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
defined('_JEXEC') or die;

class JFormFieldSocialorder extends JFormFieldText
{
    protected $type = 'Socialorder';

    /**
     * Get the html for input
     *
     * @return string
     */
    public function getInput()
    {
        $html = '<table width="100%" id="social_options" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="1%" class="index sequence nowrap center"></th>
                            <th width="1%" class="index sequence nowrap center">#</th>
                            <th width="8%" class="nowrap center">Website</th>
                            <th width="30%" class="">Integration Type</th>
                            <th width="20%" class="">Button Design</th>
                            <th width="20%" class="nowrap center isactive">Enable</th>
                            <th width="20%" class="nowrap center">Ordering</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr class="row0">

                            <td class="nowrap center"><i class="icon-reorder"></i></td>
                            <td class="index sequence order nowrap center">1</td>
                            <td class="center">
                                <a href="#" title="twitter">Twitter</a>
                            </td>
                            <td class="">
                                <select name="integration_type">
                                    <option value="float_left">Float Left</option>
                                    <option value="float_right">Float Right</option>
                                </select>
                            </td>
                            <td class="">
                                <select name="button_design">
                                    <option value="like_standard">Like Standard</option>
                                    <option value="like_box_count">Like Box Count</option>
                                </select>
                            </td>
                             <td class="center">
                                <fieldset id="enable_twitter" class="radio btn-group">
                                    <input type="radio" id="enable_twitter0" name="enable_twitter" value="1">
                                    <label for="enable_twitter0" class="btn">Yes</label>
                                    <input type="radio" id="enable_twitter1" name="enable_twitter" value="0">
                                    <label for="enable_twitter1" class="btn active btn-danger">No</label>
                                </fieldset>
                            </td>
                            <td class="center">
                                Ordering
                            </td>
                        </tr>

                        <tr class="row1">
                            <td class="nowrap center"><i class="icon-reorder"></i></td>
                            <td class="index sequence order nowrap center">2</td>
                            <td class="center">
                                <a href="#" title="twitter">Goolge</a>
                            </td>
                            <td class="">
                                <select name="integration_type">
                                    <option value="float_left">Float Left</option>
                                    <option value="float_right">Float Right</option>
                                </select>
                            </td>
                            <td class="">
                                <select name="button_design">
                                    <option value="like_standard">Like Standard</option>
                                    <option value="like_box_count">Like Box Count</option>
                                </select>
                            </td>
                             <td class="center">
                                <fieldset id="enable_twitter" class="radio btn-group">
                                    <input type="radio" id="enable_twitter0" name="enable_twitter" value="1">
                                    <label for="enable_twitter0" class="btn">Yes</label>
                                    <input type="radio" id="enable_twitter1" name="enable_twitter" value="0">
                                    <label for="enable_twitter1" class="btn active btn-danger">No</label>
                                </fieldset>
                            </td>
                            <td class="center">
                                Ordering
                            </td>
                        </tr>

                    </tbody>
                </table>
                <script type="text/javascript">
                    jQuery("#social_options > tbody").sortable({
                        stop: updateIndex
                    });

                    function updateIndex(e, ui) {
                        console.log(ui);
                        jQuery(\'td.index\', ui.item.parent()).each(function (i) {
                            jQuery(this).html(i + 1);
                        });
                    };
                </script>
            ';

        return  $html ;
    }
}
