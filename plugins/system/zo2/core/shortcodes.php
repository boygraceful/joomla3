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

class ZO2ShortCode extends JObject
{
    /**
     * @var array
     */
    protected $default_attrs = array();
    /**
     * @var string
     */
    protected $tagname = '';
    /**
     * @var array
     */
    protected $attrs = array();
    /**
     * @var string
     */
    protected $content = "";

    /**
     * Construct
     */
    public function __construct() {
        $this->init_attrs();
    }

    /**
     * initializing variables for shortcode
     */
    protected function init_attrs() {
        // default
        $this->default_attrs = array(
            'id' => '',
            'w' => '',
            'h' => ''
        );

    }

    /**
     * Running short code
     */
    public function run()
    {
        if ($this->tagname) {
            add_shortcode($this->tagname, array(&$this, 'content'));
        }
    }

    /**
     * The content of a short code
     */
    protected function body(){}

    /**
     *
     * @param $attrs
     * @param string $content
     * @return string|void
     */
    public function content($attrs, $content = "")
    {
        $this->attrs = $attrs;
        $this->content = $content;

        extract(shortcode_atts(
            $this->default_attrs,
            $this->attrs
        ));

        if (!is_array($this->attrs)) {
            return '<!-- '.$this->tagname.' tag passed invalid attributes -->';
        }

        return $this->body();
    }

}