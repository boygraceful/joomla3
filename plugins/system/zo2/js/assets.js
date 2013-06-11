/**
 * Zo2 Framework (http://zo2framework.org)
 *
 * @link         http://github.com/aploss/zo2
 * @package      Zo2
 * @author       Hiep Vu <vqhiep2010@gmail.com>
 * @copyright    Copyright ( c ) 2008 - 2013 APL Solutions
 * @license      http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */

!function($){

    var Assets = window.Assets = window.Assets || {

        ajaxs: {},

        ajax: function(name, info){
            var ajaxs = this;
            info = $.extend({
                url: Assets.url
            }, info);

            if(!ajaxs[name]){
                ajaxs[name] = {};
                var inst = this;
                ajaxs[name].indicator = this.getElement(name).on('change.less', function(e){
                    inst.callAjax(this);
                }).after('' +
                        '<div class="progress progress-striped progress-mini active">' +
                        '<div class="bar" style="width: 100%"></div>' +
                        '</div>').next().hide();
            }

            ajaxs[name].info = info;
            this.ajaxs[name] = ajaxs[name];
        },

        callAjax: function(ctrlelm){

            var ajaxs = this.ajaxs,
                name = ctrlelm.name,
                ctrl = ajaxs[name],
                form = this;

            if(!ctrl){
                ctrl = ajaxs[name.substr(0, name.length - 2)];
            }

            if(!ctrl){
                return false;
            }
//
            var info = ctrl.info;
            if(!info){
                return false;
            }

            if(ctrl.indicator.next('.chzn-container').length){
                ctrl.indicator.insertAfter(ctrl.indicator.next('.chzn-container'));
            }

            if(ctrl.indicator.next('#t3-admin-layout-clone-btns').length){
                ctrl.indicator.insertAfter($('#t3-admin-layout-clone-btns'));
            }

            ctrl.indicator.show();
            $.get(info.url, { menutype: form.values(form.getElement(name))[0], _: $.now() }, function(rsp){
                ctrl.indicator.hide();
                T3AdminMegamenu.t3megamenu(form, ctrlelm, ctrl, rsp);
            });
        },
        getElement: function(name) {

            var el = document.adminForm[name];
            if(!el){
                el = document.adminForm[name + '[]'];
            }

            return $(el);
        },
        values: function(name){
            var vals = [];

            $(name).each(function(){
                var type = this.type,
                    val = $.makeArray(((type == 'radio' || type == 'checkbox') && !this.checked) ? null : $(this).val());

                for (var i = 0, l = val.length; i < l; i++){
                    if($.inArray(val[i], vals) == -1){
                        vals.push(val[i]);
                    }
                }
            });

            return vals;
        }

    };

}(jQuery);
