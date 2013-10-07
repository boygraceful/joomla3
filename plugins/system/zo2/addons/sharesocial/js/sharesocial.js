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

(function($){

    $.fn.Zo2ShareSocial = function (options) {

        var $config = $.extend({}, {
            
            buttons: 'facebook,twitter,linkedin,gplus',
            style: 'default', // default or floating
            button_layout: 'vertical', // vertical or horizontal
            position: 'left',
            socialWidth: 200,
            socialHeight: 200,
            topPosition: 100,
            leftPosition: 0,
            rightPosition: 0,
            
            enablePopup: false,
            popupParams: {
                sClose: 10,
                dPopup: 1,
                sPopup: 3, // Time for showing popup window
                domain: ''
            },
            socialParams: {
                facebook: {
                    fb_url: '',
                    fb_send: false,
                    fb_action: 'like'
                },
                twitter: {
                    tw_username: '',
                    tw_recommended: '',
                    tw_hashtags: ''
                },
                gplus: {

                },
                linkedin: {

                }
            }
        }, options);

        var $links = {
            facebook: 'http://www.facebook.com/sharer.php?u={URL}&amp;title={TITLE}',
            twitter: 'http://twitter.com/share?text={TITLE}&amp;url={URL}',
            gplus: 'https://plus.google.com/share?url={URL}',
            linkedin: 'http://www.linkedin.com/shareArticle?mini=true&amp;url={URL}&amp;title={TITLE}'
        };

        if ($config.enablePopup) {
            $config.button_layout = 'horizontal';
        }

        return this.each (function() {

            var count = 0;
            var counter = '';
            var $this = this;
            var $container = $(this);
            var $socials = $config.buttons.split(',');
            
            if ($.isArray($socials)) {
                
                $.each($socials, function(key, value) {
                    var $button = getHtmlButton(value);
                    if ($button != null) {
                        $container.append($button);
                    }
                });

                if ($config.enablePopup) {

                    var show = $.cookie('show_modal');

                    if (show != 'true') {
                        window.setTimeout(
                            function () {
                                $('#SocialModal').addClass($config.button_layout).modal('show');
                                $.cookie('show_modal', true, { expires: parseInt($config.popupParams.dPopup), path: '/'});
                            },
                            $config.popupParams.sPopup * 1000
                        )
                    }

                    if (parseInt($config.popupParams.sClose) > 0) {
                        count = $config.popupParams.sClose;
                        counter = setInterval(function () {
                            closePopup()
                        }, 1000);
                    }

                } else {

                    if ($config.button_layout == 'default') {

                        var sPosition = 0;
                        if ($config.position == 'left') {
                            sPosition = 'margin-left:' + $config.leftPosition + 'px';
                        } else if ($config.position == 'right') {
                            sPosition = 'margin-right:' + $config.rightPosition + 'px';
                        }
                        var sWidth = ($config.socialWidth == '') ? 'auto' : $config.socialWidth + 'px';
                        var sHeight = ($config.socialHeight == '') ? 'auto' : $config.socialHeight + 'px';
                        $container.attr('style', 'position: relative;margin: 0; padding: 0; z-index: 9999; top: ' + $config.topPosition + 'px;float:' + $config.position + ';' + sPosition + ';width: ' + sWidth + ';height: ' + sHeight + ';');
                    }

                }
               
                
            }

            function closePopup() {
                count--;
                if (count <= 0) {
                    clearInterval(counter);
                    $('#SocialModal').modal('hide');
                    return;
                }
            }
            
            function getHtmlButton(type) {

                var $html = '';
                var $beforeHtml = '<div class="zo2-social-box"><div class="zo2-social-inner">';
                var $afterHtml = '</div></div>';
                var $params = $config.socialParams[type];

                var $url = getUrl(type);

                switch (type) {

                    case 'facebook':
                        var $fblayout = '';
                        if ($config.button_layout == 'vertical') {
                            $fblayout = 'box_count';
                        } else {
                            $fblayout = 'button_count';
                        }

                        $html += '<div id="fb-root"></div>' +
                            '<a href="' + $url + '" class="socialite facebook-like" data-href="' + $url + '" data-layout="' + $fblayout + '" data-send="' + $params.fb_send + '" data-action="' + $params.fb_action + '" data-width="80" data-show-faces="false" data-font="arial" target="_blank">' +
                            '<span class="zo2-share-btn">Share on Facebook</span></a>';

                        break;
                    case 'twitter':

                        var $countLayout = 'vertical';

                        if ($config.button_layout == 'horizontal') {
                            $countLayout = 'horizontal';
                        }

                        $html += '<a href="' + $url + '" class="socialite twitter-share" data-url="' + $url + '" data-count="' + $countLayout + '" target="_blank">' +
                            '<span class="zo2-share-btn">Share on Twitter</span></a>';

                        break;
                    case 'gplus':

                        var $gShareAnnotation = '';

                        if ($config.button_layout == 'vertical') {
                            $gShareAnnotation = 'vertical-bubble';
                        } else {
                            $gShareAnnotation = "bubble";
                        }

                        $html += '<a href="' + $url + '" class="socialite googleplus-share" data-action="share" data-annotation="' + $gShareAnnotation + '" data-href="' + $url + '" target="_blank">' +
                            '<span class="zo2-share-btn">Share on Google+</span></a>';

                        break;
                    case 'linkedin':

                        var $linkedinCount = 'right';

                        if ($config.button_layout == "vertical") {
                            $linkedinCount = 'top';
                        }

                        $html += '<a href="' + $url + '" class="socialite linkedin-share" data-url="' + $url + '"  data-counter="' + $linkedinCount + '"  data-showZero="true" target="_blank">' +
                            '<span class="zo2-share-btn">Share on LinkedIn</span></a>';

                        break;

                }

                return $beforeHtml + $html + $afterHtml;
            }

            function getUrl($type) {

                var $params = $config.socialParams[type];
                var $itemUrl = $container.data('url');
                var $itemId = $container.data('id');
                var $itemTitle = $container.data('title');
                var $url = '';

                if (typeof $itemUrl != "undefined" && $itemUrl != '') {
                    $url = $links[type].replace('{URL}', encodeURIComponent($itemUrl)).replace('{TITLE}', encodeURIComponent((typeof($itemTitle) != "undefined") ? $itemTitle : document.title));
                } else {
                    $url = $links[type].replace('{URL}', encodeURIComponent(location.href)).replace('{TITLE}', encodeURIComponent(document.title));
                }

                switch (type) {
                    case 'facebook':
                        if ($params.fb_url != '' && $config.enablePopup) {
                            $url = $params.fb_url;
                        }
                        break;
                    case 'twitter':
                        if ($params.tw_username != '') {
                            $url += '&via=' + $params.tw_username;
                        } else if ($params.tw_recommended != '') {
                            $url += '&related=' + $params.tw_recommended;
                        } else if ($params.tw_hashtags != '') {
                            $url += '&hashtags=' + $params.tw_hashtags;
                        }
                        break;
                }

                return $url;
            }

            Socialite.load();
            return this;

        });

    }

})(jQuery);