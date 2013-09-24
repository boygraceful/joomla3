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

class zo2com_toparticle extends Zo2Component
{
    public $viewName = 'toparticle';

    public $articles = null;

    public function run()
    {
        $db = JFactory::getDBO();
        $limit = $this->attributes['limit'];
        $query = "SELECT * FROM `#__content` ORDER BY `id` DESC LIMIT " . ($limit ? $limit : 1);
        $db->setQuery($query);
        $this->articles = $db->loadAssocList();
    }
}