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

class Hulu  extends ZO2Shortcode
{
    protected $tagname = 'hulu';

    protected function body()
    {
        extract(shortcode_atts(array(
            'id' => 'mijitruv1ycv8yacnpumuq',
            'w' => 720,
            'h' => 320,
        ), $this->attrs));

        if ( ! is_array( $this->attrs ) ) {
            return '<!-- Hulu shortcode passed invalid attributes -->';
        }

        return '<iframe width="' . $w . '" height="' . $h . '" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://www.hulu.com/embed.html?eid=' . $id . '" webkitAllowFullScreen mozallowfullscreen allowfullscreen=""></iframe>';
    }

}