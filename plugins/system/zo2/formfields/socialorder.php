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

class JFormFieldSocialorder extends JFormFieldHidden
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
        if ($this->value) {
            $value = json_decode($this->value);
        } else {
            $value = array(
                array(
                    'name' => 'twitter',
                    'index' => 1,
                    'website' => 'Twitter',
                    'link' => '#',
                    'enable' => 1,
                    'button_design' => 'like_standard'
                ),
                array(
                    'name' => 'google',
                    'index' => 2,
                    'website' => 'Google',
                    'link' => '#',
                    'enable' => 1,
                    'button_design' => 'like_standard',
                ),
            );
            $value = JArrayHelper::toObject($value);
        }

        $layout_button = array();
        $layout_button['twitter'] = array('like_standard' => 'Like standard', 'like_box_count' => 'Like Count');
        $layout_button['google'] = array('like_standard' => 'Like standard', 'like_box_count' => 'Like Count');

        $html = '<table width="100%" id="social_options" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="1%" class="index sequence nowrap center"></th>
                            <th width="1%" class="index sequence nowrap center">#</th>
                            <th width="8%" class="nowrap center">Website</th>
                            <th width="20%" class="nowrap center isactive">Enable</th>
                            <th width="20%" class="">Button Design</th>
                        </tr>
                    </thead>
                    <tbody>';

                    $count = 0;

                    foreach($value as $item) {
                        $layouts = $layout_button[$item->name];
                        $options = array();
                        foreach($layouts as $key => $layout) {
                            $options[] = JHtml::_('select.option', $key, JText::_($layout));
                        }

                        $html .= '<tr class="row'.$count.'">
                                    <td class="nowrap center" name="'.$item->name.'"><i class="icon-reorder"></i></td>
                                    <td class="index sequence order nowrap center">'.$item->index.'</td>
                                    <td class="center">
                                        <a href="'.$item->link.'" title="twitter">'.$item->website.'</a>
                                    </td>

                                     <td class="center">
                                        '.$this->renderEnable($key, $item->enable).'
                                    </td>

                                    <td class="">
                                        '.JHtml::_('select.genericlist', $options, $item->name . '_button_design', 'class="inputbox"', 'value', 'text', $item->button_design, $item->name . '_button_design').'
                                    </td>

                                </tr>';
                        $count++;
                    }


        $html .=    '</tbody>
                </table>
                <script type="text/javascript">
                    jQuery("#social_options > tbody").sortable({
                        beforeStop: Zo2Social.updateIndex,
                        stop: Zo2Social.saveConfig
                    }).disableSelection();
                </script>
            ';

        return  $html . parent::getInput();
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

        //return $this->radiolist($options, $name ,null, 'value', 'text', $value, $name);
        return $html;
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
