<?php
/**
 * Zo2 Framework (http://zo2framework.org)
 *
 * @link         http://github.com/aploss/zo2
 * @package      Zo2
 * @author       Hiepvu
 * @copyright    Copyright ( c ) 2008 - 2013 APL Solutions
 * @license      http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */
//no direct accees
defined('_JEXEC') or die ('resticted aceess');

Zo2Framework::import2('core.shortcodes');

class Dailymotion extends ZO2Shortcode
{
    // set short code tag
    protected $tagname = 'dailymotion';

    protected function body()
    {

        // initializing variables for short code
        extract(shortcode_atts(array(
                'id' => 'xuj8os',
                'w' => 720,
                'h' => 320,
                'autoplay' => 0
            ),
            $this->attrs
        ));

        if (!empty($this->content)) {

            $parse = parse_url($this->content);
            $path = str_replace('/video/', '', $parse['path']);
            $array = explode('_', $path);
            $id = $array[0];

        }
        return '<iframe width="' . $w . '" height="' . $h . '" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://www.dailymotion.com/embed/video/' . $id . '?autoPlay=' . $autoplay . '" webkitAllowFullScreen mozallowfullscreen allowfullscreen=""></iframe>';

    }

}