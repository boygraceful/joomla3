<?php
/**
 * Zo2 Framework (http://zo2framework.org)
 *
 * @link         http://github.com/aploss/zo2
 * @package      Zo2
 * @author       Hiep Vu <vqhiep2010@gmail.com>
 * @copyright    Copyright ( c ) 2008 - 2013 APL Solutions
 * @license      http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */
 //no direct accees
defined ('_JEXEC') or die ('resticted aceess');

class ZO2Controller
{
    public static function exec ($controller) {
        if (method_exists('ZO2Controller', $controller)) {
            ZO2Controller::$controller();
        }
        exit;
    }
    public static function menu() {
        $task = JFactory::getApplication()->input->get('task', '');
        Zo2Framework::import2('core.class.admin.menu');
        if(method_exists('AdminMenu', $task)){
            AdminMenu::$task();
            exit;
        }
    }
}