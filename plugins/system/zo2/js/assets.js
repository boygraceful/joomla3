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
            $.get(info.url, { jvalue: form.values(form.getElement(name))[0], _: $.now() }, function(rsp){
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

    var JAFileConfig = window.JAFileConfig = window.JAFileConfig || {

        vars: {
        },

        initialize: function(optionid){
            var vars = this.vars;
            vars.group = 't3form';
            vars.el = document.getElementById(optionid);

            var adminlist = $('#module-sliders').find('ul.adminformlist:first');
            if(adminlist.length){
                $('<li class="clearfix level2"></li>').appendTo(adminlist);
            }
        },

        changeProfile: function(profile){
            if(profile == ''){
                return;
            }

            this.vars.active = profile;
            this.fillData();

            if(T3Depend && T3Depend.update){
                T3Depend.update();
            }
        },

        serializeArray: function(){
            var vars = this.vars,
                els = [],
                allelms = document.adminForm.elements,
                pname1 = vars.group + '\\[params\\]\\[.*\\]',
                pname2 = vars.group + '\\[params\\]\\[.*\\]\\[\\]';

            for (var i = 0, il = allelms.length; i < il; i++){
                var el = $(allelms[i]);

                if (el.name && ( el.name.test(pname1) || el.name.test(pname2))){
                    els.push(el);
                }
            }

            return els;
        },

        fillData: function (){
            var vars = this.vars,
                els = this.serializeArray(),
                profile = T3Depend.profiles[vars.active],
                form = this;

            if(els.length == 0 || !profile){
                return;
            }

            $.each(els, function(){
                var name = this.getName(this),
                    values = (profile[name] != undefined) ? profile[name] : '';

                form.setValues(this, $.makeArray(values));
            });
        },

        valuesFrom: function(els){
            var vals = [];

            $(els).each(function(){
                var type = this.type,
                    val = $.makeArray(((type == 'radio' || type == 'checkbox') && !this.checked) ? null : $(this).val());

                for (var i = 0, l = val.length; i < l; i++){
                    if($.inArray(val[i], vals) == -1){
                        vals.push(val[i]);
                    }
                }
            });

            return vals;
        },

        setValues: function(el, vals){
            var jel = $(el);

            if(jel.prop('tagName').toUpperCase() == 'SELECT'){
                jel.val(vals);

                if($.makeArray(jel.val())[0] != vals[0]){
                    jel.val('-1');
                }
            }else {
                if(jel.prop('type') == 'checkbox' || jel.prop('type') == 'radio'){
                    jel.prop('checked', $.inArray(el.value, vals) != -1);
                } else {
                    jel.attr('placeholder', vals[0]);
                    jel.val(vals[0]);
                }
            }
        },

        getName: function(el){
            var matches = el.name.match(this.vars.group + '\\[params\\]\\[([^\\]]*)\\]');
            if (matches){
                return matches[1];
            }

            return '';
        },


        deleteProfile: function(){
            if(confirm(JAFileConfig.langs.confirmDelete)){
                this.submitForm(JAFileConfig.mod_url + '?dptask=delete&profile=' + this.vars.active + '&template='+ JAFileConfig.template, {}, 'profile');
            }
        },

        duplicateProfile: function (){
            var nname = prompt(JAFileConfig.langs.enterName);

            if(nname){
                nname = nname.replace(/[^0-9a-zA-Z_-]/g, '').replace(/ /, '').toLowerCase();
                if(nname == ''){
                    alert(JAFileConfig.langs.correctName);
                    return this.cloneProfile();
                }

                JAFileConfig.profiles[nname] = JAFileConfig.profiles[this.vars.active];

                this.submitForm(JAFileConfig.mod_url + '?dptask=duplicate&profile=' + nname + '&from=' + this.vars.active + '&template=' + JAFileConfig.template, {}, 'profile');
            }
        },

        saveProfile: function (task){

            if(task){
                JAFileConfig.profiles[this.vars.active] = this.rebuildData();
                this.submitForm(JAFileConfig.mod_url + '?dptask=save&profile=' + this.vars.active, JAFileConfig.profiles[this.vars.active], 'profile', task);
            }
        },

        submitForm: function(url, request, type, task){
            if(JAFileConfig.run){
                JAFileConfig.ajax.cancel();
            }

            JAFileConfig.run = true;

            JAFileConfig.ajax = $.ajax({
                type: 'post',
                url: url,
                data: request,
                complete: function(result){

                    JAFileConfig.run = false;

                    if(result == ''){
                        return;
                    }

                    var vars = JAFileConfig;

                    alert(json.error || json.successfull);

                    if(result.profile){
                        switch (result.type){
                            case 'new':
                                Joomla.submitbutton(document.adminForm.task.value);
                                break;

                            case 'delete':
                                if(result.template == 0){
                                    var opts = vars.el.options;

                                    for(var j = 0, jl = opts.length; j < jl; j++){
                                        if(opts[j].value == result.profile){
                                            vars.el.remove(j);
                                            break;
                                        }
                                    }
                                } else {
                                    JAFileConfig.profiles[result.profile] = JAFileConfig.tempprofiles[result.profile];
                                }

                                vars.el.options[0].selected = true;
                                JAFileConfig.changeProfile(vars.el.options[0].value);
                                break;

                            case 'duplicate':
                                vars.el.options[vars.el.options.length] = new Option(result.profile, result.profile);
                                vars.el.options[vars.el.options.length - 1].selected = true;
                                JAFileConfig.changeProfile(result.profile);
                                break;

                            default:
                        }
                    }
                }
            });
        },

        rebuildData: function (){
            var els = this.serializeArray(this.group),
                form = this,
                json = {};

            $.each(els, function(){
                var values = form.valuesFrom(this);
                if(values.length){
                    json[this.getName(this)] = this.name.substr(-2) == '[]' ? values : values[0];
                }
            });

            return json;
        }
    };

}(jQuery);
