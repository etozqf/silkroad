(function(){

    function init(form, dialog) {
        var select = dialog.find('[data-role=select]'),
            area = dialog.find('[data-role=item]'),
            contentid = dialog.find('[data-role=contentid]');
        select.click(function(){
            $.datapicker({
                multiple:false,
                picked:function(items){
                    var item = items[0];
                    area.html('<a href="' + item.url + '" target="_blank" title="' + item.title + '">' + item.title.substr(0, 20) + '</a>');
                    select.text('重新选择');
                    contentid.val(item.contentid);
                },
                url:'?app=system&controller=port&action=picker&modelid=2'
            });
        });
    }

    function setTemplate(dialog, form, engine) {
        var url = '?app=special&controller=online&action=getTemplate&engine='+engine;
        var a = dialog.find('#template').click(function(){
            var textarea = $('<textarea style="width:100%;" wrap="off" name="data[template]" ></textarea>');
            a.replaceWith(textarea);
            textarea.editplus({
                buttons: 'fullscreen,wrap,|,loop,ifelse,elseif',
                height:150
            });
            if (form[0].widgetid && form[0].widgetid.value) {
                url += '&widgetid='+form[0].widgetid.value;
            }
            $.get(url, function(html){
                textarea.val(html);
            });
        });
    }

    DIY.registerEngine('picture_group', {
        addFormReady:function(form, dialog) {
            init(form, dialog);
            setTemplate(dialog, form, 'picture_group');
        },
        editFormReady:function(form, dialog) {
            init(form, dialog);
            setTemplate(dialog, form, 'picture_group');
        },
        afterRender:function(widget){
        },
        beforeSubmit:function(form, dialog){
            form.find('tbody:hidden')
                .find('input,select,textarea').each(function(){
                    if (!this.disabled) {
                        this.setAttribute('notsubmit','1');
                        this.disabled = true;
                    }
                });
        },
        afterSubmit:function(form, dialog){
            form.find('tbody:hidden')
                .find('input,select,textarea')
                .filter('[notsubmit]').removeAttr('disabled');
        }
    });

})();