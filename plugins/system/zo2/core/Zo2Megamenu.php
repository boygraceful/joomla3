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
//no direct accees
defined('_JEXEC') or die ('resticted aceess');

class Zo2MegaMenu
{
    protected $_params = null;
    protected $_configs = null;
    protected $children = null;
    protected $_items = null;
    protected $edit = false;
    protected $isAdmin = false;
    function __construct($menutype = 'mainmenu', $configs = array(), $params)
    {
        $this->_configs = $configs;
        $this->_params = $params;
        $this->edit = isset($configs['edit']) ? $configs['edit'] : false;
        $this->loadMegaMenu($menutype);
    }

    function loadMegaMenu($menutype)
    {

        $app = JFactory::getApplication();
        $menu = $app->getMenu('site');
        $items = $menu->getItems('menutype', $menutype);

        $active_menu = $menu->getActive() ? $menu->getActive() : $menu->getDefault();
        $menu_id = $active_menu ? $active_menu->id : 0;
        $menu_tree = $active_menu->tree ? $active_menu->tree : array();

        // Get all child menus for a parent menu
        foreach ($items as &$item) {

            $parent_id = $item->parent_id;
            if (isset($this->children[$parent_id])) {
                $menus = $this->children[$parent_id];
            } else {
                $menus = array();
            }
            // push a item into $menus array
            array_push($menus, $item);
            $this->children[$parent_id] = $menus;
        }

        foreach ($items as &$item) {

            $itemid = 'item-' . $item->id;
            $config = isset($this->_configs[$itemid]) ? $this->_configs[$itemid] : array();

            // decode html tag
            if (isset($config['caption']) && $config['caption']) $config['caption'] = str_replace(array('[lt]','[gt]'), array('<','>'), $config['caption']);
            if ($item->level == 1 && isset($config['caption']) && $config['caption']) {
                $item->top_level_caption = true;
            }
            // active - current
            $class = '';
            if ($item->id == $menu_id) {
                $class .= ' current';
            }
            if (in_array($item->id, $menu_tree)) {
                $class .= ' active';
            } elseif ($item->type == 'alias') {
                if (count($menu_tree) > 0 && $item->params->get('aliasoptions') == $menu_tree[count($menu_tree) - 1]) {
                    $class .= ' active';
                } elseif (in_array($item->params->get('aliasoptions'), $menu_tree)) {
                    $class .= ' alias-parent-active';
                }
            }

            $item->class = $class;
            $item->show_group = false;
            $item->isdropdown = false;
            if (isset($config['group'])) {
                $item->show_group = true;
            } else {
                // if this item is a parent then setting up the status is dropdown
                if ($this->_params->get('show_submenu', 1) && (isset($config['submenu']) || (isset($this->children[$item->id]) && (!isset($config['hidesub']) || $this->edit)))) {
                    $item->isdropdown = true;
                }
            }
            $item->megamenu = $item->isdropdown || $item->show_group;

            if ($item->megamenu && !isset($config['submenu'])) {
                $firstChild = $this->children[$item->id][0]->id;
                $config['submenu'] = array('rows'=>array(array(array('width'=>12, 'item'=>$firstChild))));
            }

            $item->config = $config;


            $item->flink = $item->link;

            // Reverted back for CMS version 2.5.6
            switch ($item->type) {
                case 'separator':
                case 'heading':
                    // No further action needed.
                    continue;

                case 'url':
                    if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
                        // If this is an internal Joomla link, ensure the Itemid is set.
                        $item->flink = $item->link . '&Itemid=' . $item->id;
                    }

                    break;

                case 'alias':
                    // If this is an alias use the item id stored in the parameters to make the link.
                    $item->flink = 'index.php?Itemid=' . $item->params->get('aliasoptions');
                    break;

                default:
                    $router = JSite::getRouter();
                    if ($router->getMode() == JROUTER_MODE_SEF) {
                        $item->flink = 'index.php?Itemid=' . $item->id;
                    } else {
                        $item->flink .= '&Itemid=' . $item->id;
                    }
                    break;
            }

            if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false)) {

                $item->flink = JRoute::_($item->flink, true, $item->params->get('secure'));
            } else {
                $item->flink = JRoute::_($item->flink);
            }

            // We prevent the double encoding because for some reason the $item is shared for menu modules and we get double encoding
            // when the cause of that is found the argument should be removed
            $item->title = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8', false);
            $item->anchor_css = htmlspecialchars($item->params->get('menu-anchor_css', ''), ENT_COMPAT, 'UTF-8', false);
            $item->anchor_title = htmlspecialchars($item->params->get('menu-anchor_title', ''), ENT_COMPAT, 'UTF-8', false);
            $item->menu_image = $item->params->get('menu_image', '') ? htmlspecialchars($item->params->get('menu_image', ''), ENT_COMPAT, 'UTF-8', false) : '';
            $this->_items[$item->id] = $item;
        }

    }

    /**
     * render menu
     */
    function renderMenu($isAdmin = false)
    {
        $this->isAdmin = $isAdmin;
        //
        $prefix = '<nav data-zo2selectable="navbar" class="wrap zo2-menu navbar navbar-default" role="navigation"><div class="container">';
        $prefix .= '<div class="navbar-header"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                          <span class="sr-only">ZO2</span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                    </button>
                    </div>
                    <div class="navbar-collapse collapse">';
        $suffix = '</div></div></nav>';
        $html = '';
        $hover = ' data-hover="' . $this->_params->get('hover_type', 'hover') . '"';
        $animation = $this->_params->get('animation', '');
        $duration = $this->_params->get('duration', 400);
        $class = 'class="zo2-megamenu' . ($animation ? ' animate ' . $animation : '') . '"';
        $data = $animation && $duration ? ' data-duration="' . $duration . '"' : '';

        $keys = array_keys($this->_items);

        if (count($this->_items)) {
            $html .= "<div $class$data$hover>";
            $html .= $this->getMenu(null, $keys[0]);
            $html .= "</div>";
            if ($isAdmin == true) {
                return $html;
            } elseif ($isAdmin == false) {
               // return $prefix . $html . $suffix;
                return $html ;
            }
        }
        return '';
    }

    /**
     * Get child menus for parent menu
     * @param null $parent
     * @param int $start
     * @param int $end
     * @return string
     */
    function getMenu($parent = null, $start = 0, $end = 0)
    {

        $html = '';

        if ($start > 0) {
            if (!isset($this->_items[$start])) return;
            $parent_id = $this->_items[$start]->parent_id;
            $menus = array();
            $started = false;
            foreach ($this->children[$parent_id] as $item) {

                if ($started) {
                    if ($item->id == $end) break;
                    $menus[] = $item;
                } else {
                    if ($item->id == $start) {
                        $started = true;
                        $menus[] = $item;
                    }
                }
                if (!count($menus)) return;
            }

        } else if ($start === 0){
            $pid = $parent->id;
            if (!isset($this->children[$pid])) return ;
            $menus = $this->children[$pid];
        } else {
            return;
        }
        $class = '';
        if (!$parent) {
            $class .= 'nav navbar-nav level-top';
        } else {
            if (!$this->isAdmin) $class .= 'nav';
            $class .= ' mega-nav';
            $class .= ' level' . $parent->level;
        }

        if ($class) $class = 'class="'.trim($class).'"';

        $html .= '<ul '.$class.'>';

        foreach ($menus as $menu) {
            $html .= $this->getLiTag($menu);
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Get content of Li tag
     * @param $menu
     * @return string
     */
    function getLiTag($menu)
    {
        $html = '';
        $html .= $this->beginLi($menu);
        $html .= $this->getLinkTitle($menu);
        if ($menu->megamenu) {
            $html .= $this->getSubMenu($menu);
        }
        $html .= $this->endLi($menu);
        return $html;
    }

    /**
     * Get link type
     * @param $menu
     * @return string
     */
    function getLinkTitle($menu)
    {

        $config = $menu->config;

        $class = $menu->anchor_css ? $menu->anchor_css : '';
        $title = $menu->anchor_title ? 'title="' . $menu->anchor_title . '"' : '';
        $dropdown = '';
        $caption = '';
        $linktype = '';
        $icon = '';
        $caret = '<b class="caret"></b>';
        if ($menu->isdropdown && $menu->level < 2) {
            $class .= 'dropdown-toggle';
            $dropdown = ' data-toggle="dropdown" ';
        }

        if ($menu->show_group) {
            $class .= ' group-title';
        }

        if ($menu->menu_image) {
            if ($menu->params->get('menu_text', 1)) {
                $linktype = '<img src="' . $menu->menu_image . '" alt="' . $menu->title . '" /><span class="image-title">' . $menu->title . '</span>';
            } else {
                $linktype = '<img src="' . $menu->menu_image . '" alt="' . $menu->title . '" />';
            }
        } else {
            $linktype = $menu->title;
        }

        if (isset($config['xicon']) && $config['xicon']) {
            $icon = '<i class="'.$config['xicon'].'"></i>';
        }

        if (isset($config['caption']) && $config['caption']) {
            $caption = '<span class="mega-caption">' . $config['caption'] . '</span>';
        } else if ($menu->level==1 && isset($menu->top_level_caption) && $menu->top_level_caption) {
            $caption = '<span class="mega-caption mega-caption-empty"></span>';
        }

        $html = '';

        switch ($menu->type) {
            case 'separator':
                $class .= " separator";
                $html = "<span class=\"$class\">$icon$title $linktype$caption</span>";
                break;
            case 'component':

                switch ($menu->browserNav) {
                    default:
                    case 0:
                        $html = "<a class=\"$class\" href=\"{$menu->flink}\" $title $dropdown>$icon$linktype$caret$caption</a>";
                        break;
                    case 1:
                        // _blank
                        $html = "<a class=\"$class\" href=\"{$menu->flink}\" target=\"_blank\" $title $dropdown>$icon$linktype$caret$caption</a>";
                        break;
                    case 2:
                        // window.open
                        $html = "<a class=\"$class\" href=\"{$menu->flink}\" onclick=\"window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;\" $title $dropdown>$linktype $caret$caption</a>";
                        break;
                }
                break;
            case 'url':
                $flink = $menu->flink;
                $flink = JFilterOutput::ampReplace(htmlspecialchars($flink));
                switch ($menu->browserNav) {

                    default:
                    case 0:
                        $html = "<a class=\"$class\" href=\"$flink\" $title $dropdown>$icon$linktype$caret$caption</a>";
                        break;
                    case 1:
                        // _blank
                        $html = "<a class=\"$class\" href=\"$flink\" target=\"_blank\" $title $dropdown>$icon$linktype$caret$caption</a>";
                        break;
                    case 2:
                        // window.open
                        $options = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,' . $menu->params->get('window_open');
                        $html = "<a class=\"$class\" href=\"$flink\" onclick=\"window.open(this.href,'targetWindow','$options');return false;\" $title $dropdown>$icon$linktype$caret$caption</a>";
                        break;
                }

                break;
        }

        return $html;
    }

    /**
     * @param $menu
     * @return string
     */
    function beginLi($menu)
    {

        $config = $menu->config;
        $class = $menu->class;

        if ($menu->isdropdown) {
            $class .= $menu->level == 1 ? ' dropdown' : ' dropdown-submenu';
        }

        if ($menu->megamenu) $class .= ' mega';
        if ($menu->show_group) $class .= ' mega-group';

        $data = "data-id=\"{$menu->id}\" data-level=\"{$menu->level}\"";
        if ($menu->show_group) $data .= " data-group=\"1\"";
        if (isset($config['class'])) {
            $data .= " data-class=\"{$config['class']}\"";
            $class .= " {$config['class']}";
        }

        if (isset($config['alignsub'])) {
            $data .= " data-alignsub=\"{$config['alignsub']}\"";
            $class .= " mega-align-{$config['alignsub']}";
        }
        if (isset($config['hidesub'])) $data .= " data-hidesub=\"1\"";
        if (isset($config['xicon'])) $data .= " data-xicon=\"{$config['xicon']}\"";
        if (isset($config['caption'])) $data .= " data-caption=\"" . htmlspecialchars($config['caption']) . "\"";

        if (isset($config['hidesub'])) $data .= " data-hidesub=\"1\"";
        if (isset($config['caption'])) $data .= " data-caption=\"" . htmlspecialchars($config['caption']) . "\"";
        if (isset($config['hidewcol'])) {
            $data .= " data-hidewcol=\"1\"";
            $class .= " sub-hidden-collapse";
        }
        $class = 'class="' . $class . '"';
        return "<li $class $data>";

    }

    /**
     * @param $menu
     * @return string
     */
    function endLi($menu)
    {
        return "</li>";
    }

    /**
     * Get sub menus for parent menu
     * @param $parent
     * @return string
     */
    function getSubMenu($parent)
    {

        $html = '';
        $config = $parent->config;
        $submenu = $config['submenu'];
        $menus = isset($this->children[$parent->id]) ? $this->children[$parent->id] : array();
        //default first item
        $fitem = count($menus) ? $menus[0]->id : 0;

        $class = 'menu-child  ' . ($parent->isdropdown ? 'dropdown-menu mega-dropdown-menu' : 'mega-group-content');
        $data = '';
        $style = '';

        if (isset($config['class'])) $data .= " data-class=\"{$config['class']}\"";
        if (isset($config['alignsub']) && $config['alignsub'] == 'justify') {
            if ($this->isAdmin) {
                $class .= " span12";
            } else {
                $class .= " col-md-12";
            }
        } else {
            if (isset($submenu['width'])) {
                if ($parent->isdropdown) {
                    $style = " style=\"width:{$submenu['width']}px\"";
                }
                $data .= " data-width=\"{$submenu['width']}\"";
            }
        }

        if ($class) $class = 'class="'.trim($class).'"';

        $html .= "<div $style $class $data><div class=\"mega-dropdown-inner\">";

        $endItems = array();
        $k1 = $k2 = 0;
        foreach ($submenu['rows'] as $row) {

            foreach ($row as $column) {
                if (!isset($column['module_id'])) {
                    if ($k1) {
                        $k2 = $column['item'];
                        if (!isset($this->_items[$k2]) || $this->_items[$k2]->parent_id != $parent->id) break;
                        $endItems[$k1] = $k2;
                    }
                    $k1 = $column['item'];
                }
            }
        }

        $endItems[$k1] = 0;
        $firstitem = true;
        $rowClass = 'row-fluid';
        $colClass = 'span';
        if (!$this->isAdmin) {
            $rowClass = 'row';
            $colClass = 'col-md-';
        }
//        if ($parent->id == 475) {
//            var_dump($submenu['rows']);die;
//        }
        foreach ($submenu['rows'] as $key => $row) {
            //start row
            $html .= '<div class="'.$rowClass .'">';
            foreach ($row as $column) {
                $width = isset($column['width']) ? $column['width'] : '12';
                $data = "data-width=\"$width\"";
                $class = "$colClass$width";
                if (isset($column['module_id'])) {
                    $class .= " mega-col-module";
                    $data .= " data-module_id=\"{$column['module_id']}\"";
                } else {
                    $class .= " mega-col-nav";
                }
                if (isset($column['class'])) {
                    $class .= " {$column['class']}";
                    $data .= " data-class=\"{$column['class']}\"";
                }
                if (isset($column['hidewcol'])) {
                    $data .= " data-hidewcol=\"1\"";
                    $class .= " hidden-collapse";
                }
                // start column
                $html .= "<div class=\"$class\" $data><div class=\"mega-inner\">";

                if (isset($column['module_id'])) {
                    $html .= $this->getModule($column['module_id']);
                } else {
                    if (!isset($endItems[$column['item']])) continue;
                    $endId = $endItems[$column['item']];
                    $startId = $firstitem ? $fitem : $column['item'];
                    var_dump($startId, $endId);
                    $html .= $this->getMenu($parent, (int) $startId, (int)$endId);
                    $firstitem = false;
                }

                $html .= "</div></div>"; // end column
            }

            $html .= "</div>"; //end row
        }

        $html .= "</div></div>";
        return $html;
    }

    /**
     *
     * @param $id
     * @return string
     */
    function getModule($id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('m.*');
        $query->from('#__modules AS m');
        $query->where('m.published = 1');
        if (is_numeric($id)) {
            $query->where('m.id = ' . $id);
        }
        $query->where('m.client_id = 0');
        $query->order('position, ordering');
        $db->setQuery($query);
        $module = $db->loadObject();

        if ($module && $module->id) {
            $style = 'ZO2Xhtml';
            $content = JModuleHelper::renderModule($module, array('style' => $style));
            return $content . "\n";
        }

    }
}