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

class Gist  extends ZO2Shortcode
{
    // set short code tag
    protected $tagname = 'gist';

    /**
     * initializing variables for short code
     */
    protected function init_attrs() {
        $this->default_attrs =  array(
            'id' => '',
            'username' => '',
            'url' => ''
        );
    }

    protected function body()
    {

        if (!empty($this->content)) {

            $url = JString::parse_url($this->content);
            $path = $url['path'];
            $info = explode('/', trim($path, '/'));
            $id = $info[1];
            $username = $info[0];

        }

        if (!empty($username)) {

            $gist_url = sprintf('https://gist.github.com/%s/%s.js', $username, $id);
            return '<script src="' . $gist_url . '"></script>';

        }

    }

}