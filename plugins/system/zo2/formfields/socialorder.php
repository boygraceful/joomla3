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
        $document = JFactory::getDocument();
        $document->addScript(ZO2_PLUGIN_URL . '/assets/js/adminsocial.js');

        $html = '<table width="100%" id="social_options" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="1%" class="index sequence nowrap center"></th>
                            <th width="1%" class="index sequence nowrap center">#</th>
                            <th width="8%" class="nowrap center">Website</th>
                            <th width="20%" class="nowrap center isactive">Enable</th>
                            <th width="30%" class="">Position</th>
                            <th width="20%" class="">Button Design</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr class="row0">

                            <td class="nowrap center"><i class="icon-reorder"></i></td>
                            <td class="index sequence order nowrap center">1</td>
                            <td class="center">
                                <a href="#" title="twitter">Twitter</a>
                            </td>

                             <td class="center">
                                '.$this->renderEnable('twitter', 1).'
                            </td>

                            <td class="">
                                '.$this->renderPosition('twitter').'
                            </td>

                            <td class="">
                                <select name="button_design">
                                    <option value="like_standard">Like Standard</option>
                                    <option value="like_box_count">Like Box Count</option>
                                </select>
                            </td>

                        </tr>

                        <tr class="row1">
                            <td class="nowrap center"><i class="icon-reorder"></i></td>
                            <td class="index sequence order nowrap center">2</td>
                            <td class="center">
                                <a href="#" title="twitter">Goolge</a>
                            </td>

                            <td class="center">
                                '.$this->renderEnable('google', 0).'
                            </td>

                            <td class="">
                                '.$this->renderPosition('google').'
                            </td>

                            <td class="">
                                <select name="button_design">
                                    <option value="like_standard">Like Standard</option>
                                    <option value="like_box_count">Like Box Count</option>
                                </select>
                            </td>

                        </tr>

                    </tbody>
                </table>
                <script type="text/javascript">
                    jQuery("#social_options > tbody").sortable({
                        stop: Zo2Social.updateIndex
                    });
                </script>
            ';

        return  $html ;
    }

    function renderEnable($name, $value = 0) {

        $name = 'enable_' . $name;
        $on = ($value) ? 'active btn-success' : '';
        $off = (!$value) ? 'active btn-danger' : '';
        $html = '
            <fieldset name="fs_'.$name.'" class="radio btn-group social-onoff '.((!$value) ? 'toggle-off' : '').'">
                <input name="'.$name.'" id="'.$name.'" type="radio" value="'.$value.'">
                <label for="'.$name.'" class="btn on '. $on .'">Yes</label>
                <label for="'.$name.'" class="btn off '. $off .'">No</label>
            </fieldset>
        ';

        $options = array(
            JHtml::_('select.option', '1', JText::_('JYES')),
            JHtml::_('select.option', '0', JText::_('JNO'))
        );

        return $this->radiolist($options, $name ,null, 'value', 'text', $value, $name);
        //return $html;
    }

    function renderPosition($name, $active = 'top', $type = 'normal') {

        $array = array();

        if ($type == 'normal') {
            $array[] = JHtml::_('select.option', 'top', JText::_('Top'));
            $array[] = JHtml::_('select.option', 'bottom', JText::_('Bottom'));
        } else if ($type == 'floating') {
            $array[] = JHtml::_('select.option', 'float_left', JText::_('Float left'));
            $array[] = JHtml::_('select.option', 'float_right', JText::_('Float right'));
        }

        return JHtml::_('select.genericlist', $array, $name . '_position', 'class="inputbox"', 'value', 'text', $active, $name . '_position');
    }

    function radiolist($data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false, $translate = false)
    {
        reset($data);

        if (is_array($attribs))
        {
            $attribs = JArrayHelper::toString($attribs);
        }

        $id_text = $idtag ? $idtag : $name;

        $html = '<fieldset class="radio btn-group">';

        foreach ($data as $obj)
        {
            $k = $obj->$optKey;
            $t = $translate ? JText::_($obj->$optText) : $obj->$optText;
            $id = (isset($obj->id) ? $obj->id : null);

            $extra = '';
            $extra .= $id ? ' id="' . $obj->id . '"' : '';

            if (is_array($selected))
            {
                foreach ($selected as $val)
                {
                    $k2 = is_object($val) ? $val->$optKey : $val;

                    if ($k == $k2)
                    {
                        $extra .= ' selected="selected"';
                        break;
                    }
                }
            }
            else
            {
                $extra .= ((string) $k == (string) $selected ? ' checked="checked"' : '');
            }

            $html .= "\n\t" . "\n\t" . '<input type="radio" name="' . $name . '" id="' . $id_text . $k . '" value="' . $k . '" ' . $extra . ' '
                . $attribs . '>';
            $html .= "\n\t" . '<label for="' . $id_text . $k . '" id="' . $id_text . $k . '-lbl" class="radio">'. $t .'</label>';
        }

        $html .= '</fieldset>';
        $html .= "\n";

        return $html;
    }

}
