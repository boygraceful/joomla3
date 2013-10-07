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

class Zo2Sharesocial
{

    function __construct($params) {
        $this->params = $params;
        $this->init();
    }

    function init() {
        $lang = JFactory::getLanguage();
        $lang->load('plg_system_zo2', JPATH_ADMINISTRATOR);

    }

    function loadScript($element) {

        $document = JFactory::getDocument();
        $type = $document->getType();
        static $bool = false;

        if ($type == 'html') {

            if (!$bool) {

                $document = JFactory::getDocument();

                $appear_popup = (int)$this->params->get('appear_popup', 1);
                $close_popup = (int)$this->params->get('close_popup', 10);
                $days_popup = (int)$this->params->get('days_popup_again', 1);

                $version = new JVersion();
                if ($version->isCompatible('3.0')) {
                    JHtml::_('bootstrap.framework');
                } else {
                    $document->addStyleSheet(JURI::base() . 'plugins/system/zt_social/assets/css/bootstrap.min.css');
                    $document->addScript(JURI::base() . 'plugins/system/zt_social/assets/js/jquery.min.js');
                    $document->addScript(JURI::base() . 'plugins/system/zt_social/assets/js/jquery-noconflict.js');
                    $document->addScript(JURI::base() . 'plugins/system/zt_social/assets/js/bootstrap.min.js');
                }

                $document->addStyleSheet(JURI::base() . 'plugins/system/zt_social/assets/css/social.css');
                $document->addScript(JURI::base() . 'plugins/system/zt_social/assets/js/jquery.cookie.js');
                $document->addScript(JURI::base() . 'plugins/system/zt_social/assets/js/socialite/socialite.min.js');
                $document->addScript(JURI::base() . 'plugins/system/zt_social/assets/js/socialite/extensions/socialite.pinterest.js');
                $document->addScript(JURI::base() . 'plugins/system/zt_social/assets/js/socialite/extensions/socialite.bufferapp.js');
                $document->addScript(JURI::base() . 'plugins/system/zt_social/assets/js/zt_social.min.js');

                $document->addScriptDeclaration('

                    jQuery(document).ready(
                        function($){
                            $("' . $element . '").Zo2ShareSocial({
                                buttons: "' . $this->params->get('buttons') . '",
                                style: "' . $this->params->get('social_style', 'default') . '",
                                button_layout: "' . $this->params->get('button_layout', 'vertical') . '",
                                socialPosition: "' . $this->params->get('social_position', 'left') . '",
                                socialWidth: "' . $this->params->get('social_width') . '",
                                socialHeight: "' . $this->params->get('social_height') . '",
                                topPosition: "' . $this->params->get('top_position', 100) . '",
                                leftPosition: "' . $this->params->get('left_position', 0) . '",
                                rightPosition: "' . $this->params->get('right_position', 0) . '",
                                enablePopup: ' . $this->params->get('enable_popup', 0) . ',
                                popupParams: {
                                    sClose: "' . $close_popup . '",
                                    sPopup: "' . $appear_popup . '",
                                    dPopup: "' . $days_popup . '",
                                    domain: "' . JUri::getInstance()->toString(array('scheme', 'host', 'port')) . '"
                                },
                                socialParams: {
                                    facebook: {
                                        fb_url: "' . $this->params->get('fb_url') . '",
                                        fb_send: ' . ($this->params->get('fb_send') ? 'true' : 'false') . ',
                                        fb_action: "' . $this->params->get('fb_action') . '"
                                    },
                                    twitter : {
                                        tw_username: "' . $this->params->get('tw_username') . '",
                                        tw_recommended: "' . $this->params->get('tw_recommended') . '",
                                        tw_hashtags: "' . $this->params->get('tw_hashtags') . '",
                                    },
                                     googleplus: {

                                    },
                                    linkedin: {

                                    }
                                }
                            });
                    });'
                );

                $bool = true;
            }

        }

    }
    function renderPopup($body, $element = '#zo2-social-popup') {

        if ($this->checkMenu()) {

            $this->loadScript($element);

            $html = '<div id="SocialModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="SocialModalLabel" aria-hidden="true">
                      <div class="modal-header">
                        <h2 id="SocialModalLabel">'.JText::_('PLG_ZT_SOCIAL_PLEASE_SUPPORT_US').'</h2>
                      </div>
                      <div class="modal-body">
                        <p>'.JText::_('PLG_ZT_SOCIAL_BY_CLICKING_ANY_OF_THESE_BUTTONS_YOU_HELP_OUR_SITE_TO_GET_BETTER').'</p>
                        <div id="zo2-social-popup"></div>
                      </div>
                    </div>';
            $body = str_replace('</body>', $html . '</body>', $body);

            return $body;

        } else {
            return $body;
        }

    }

    function renderSocial($content, $element = '.zo2-social-btn') {

        $url = '';
        $article = (array) $content;
        $option = JFactory::getApplication()->input->getCmd('option', '');
        $view = $this->getView();

        if ($this->showIn($view)) {

            $this->loadScript($element);

            if ($option == 'com_content') {
                $url = JUri::getInstance()->toString(array('scheme', 'host', 'port')) . JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catslug));
            } else if ($option == 'com_k2') {
                $url = JUri::getInstance()->toString(array('scheme', 'host', 'port')) . $article->link;
            }
            $layout = $this->params->get('button_layout', 'horizontal');
            $html = '<div class="zo2-social-btn ' . $layout . '" data-id="' . $article->id . '" data-url="' . $url . '" data-title="' . $article->title . '" ></div>';

            if ($view == 'article') {
                $article->text = $html . $article->text;
            } else if ($view == 'category' || $view == 'featured') {
                $article->introtext = $html . $article->introtext;
            }

        }

        return;
    }


    function checkMenu()
    {

        $app = JFactory::getApplication();
        $excl = $this->params->get('excl_menu');
        $menuexin = ($this->params->get('menuexin')) ? true : false;

        if (empty($excl)) {
            return true;
        }

        $menu = $app->getMenu();
        $active = $menu->getActive() ? $menu->getActive() : $menu->getDefault();
        if (count($excl)) {

            if (in_array($active->id, $excl)) {
                if ($menuexin) {
                    return false;
                } else {
                    return true;
                }

            } else {

                if ($menuexin) {
                    return true;
                } else {
                    return false;
                }

            }

        }

        return true;
    }

    public function showIn($view)
    {

        $bool = false;
        switch ($view) {
            case 'article':
                $bool = $this->params->get('show_in_article', 0) ? true : false;
                break;
            case 'category':
                $bool = $this->params->get('show_in_category', 0) ? true : false;
                break;
            case 'featured':
                $bool = $this->params->get('show_in_featured', 0) ? true : false;
                break;
            default:
                $bool = true;
                break;
        }


        return $bool;
    }


    public function getView()
    {

        $input = JFactory::getApplication()->input;

        $currentView = $input->get("view", '');
        if ($currentView == "item")
            $currentView = "article";
        else If ($currentView == "featured") {
            $currentView = "featured";
        }

        else if ($currentView == "itemlist" || $currentView == "latest")
            $currentView = "category";

        return $currentView;
    }

}