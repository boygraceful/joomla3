<?php
/**
 * Zo2 (http://www.zo2framework.org)
 * A powerful Joomla template framework
 *
 * @link        http://www.zo2framework.org
 * @link        http://github.com/aploss/zo2
 * @author      ZooTemplate <http://zootemplate.com>
 * @copyright   Copyright (c) 2013 APL Solutions (http://apl.vn)
 * @license     GPL v2
 */
//no direct accees
defined('_JEXEC') or die ('resticted aceess');


Zo2Framework::import2('core.Zo2Shortcode');

class Bliptv extends Zo2Shortcode
{
    // set short code tag
    protected $tagname = 'blip.tv';

    protected $embed = true;

    /**
     * Overwrites the parent method
     * @return string the embed HTML
     */
    protected function body()
    {
        // initializing variables for short code
        extract($this->shortcode_atts(array(
                'id' => 6608034,
                'w' => 720,
                'h' => 320,
                'autoplay' => false
            ),
            $this->attrs
        ));

        return '<iframe width="' . $w . '" height="' . $h . '" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://blip.tv/players/xplayer?id=' . $id . '&amp;autoplay=' . $autoplay . '&amp;onsite=true&amp;no_postroll=true" webkitAllowFullScreen mozallowfullscreen allowfullscreen=""></iframe>';
    }

}


