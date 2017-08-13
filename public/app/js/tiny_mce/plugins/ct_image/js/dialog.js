var CmsTopImageDialog = {
	init : function() {
        var f = document.forms[0],
            nl = f.elements,
            ed = tinyMCEPopup.editor,
            dom = ed.dom,
            n = ed.selection.getNode();

        tinyMCEPopup.resizeToInnerSize();

        $('#upload').uploader({
            script:APP_URL+'?app=editor&controller=image&action=upload',
            fileDesc:'图片文件',
            fileExt:'*.gif;*.png;*.jpeg;*.jpg',
            sizeLimit:parseInt(window['UPLOAD_MAX_FILESIZE']) * 1024 * 1024,
            multi:false,
            jsonType:1,
            selectEnd:function() {
                nl.src.value = '';
            },
            complete:function(json, data) {
                if (json.state) {
                    nl.src.value = json.file;
                    nl.alt.value = data.file.name.indexOf('.') > -1 ? data.file.name.substr(0, data.file.name.lastIndexOf('.')) : data.file.name;
                } else {
                    alert('文件上传失败'+(json.error ? '，'+json.error : ''));
                }
            },
            error:function(data) {
                ct.error(data.error.type+':'+data.error.info);
            }
        });

        if (n && n.nodeName == 'IMG') {
            nl.src.value = dom.getAttrib(n, 'src');
            nl.width.value = dom.getAttrib(n, 'width');
            nl.height.value = dom.getAttrib(n, 'height');
            nl.alt.value = dom.getAttrib(n, 'alt');
        }
	},

    insert : function() {
        var ed = tinyMCEPopup.editor,
            t = this,
            f = document.forms[0];

        if (f.src.value === '') {
            if (ed.selection.getNode().nodeName == 'IMG') {
                ed.dom.remove(ed.selection.getNode());
                ed.execCommand('mceRepaint');
            }
            tinyMCEPopup.close();
            return;
        }

        t.insertAndClose();
    },

    insertAndClose : function() {
        var ed = tinyMCEPopup.editor,
            f = document.forms[0],
            nl = f.elements,
            args = {},
            el;

        tinyMCEPopup.restoreSelection();

        // Fixes crash in Safari
        if (tinymce.isWebKit)
            ed.getWin().focus();

        tinymce.extend(args, {
            src : nl.src.value,
            width : nl.width.value,
            height : nl.height.value,
            alt : nl.alt.value
        });

        el = ed.selection.getNode();
        if (el && el.nodeName == 'IMG') {
            ed.dom.setAttribs(el, args);
        } else {
            var attributes = '';
            for (var attr in args) {
                if (args[attr] !== '') {
                    attributes += attr + '="' + (args[attr]) + '"';
                }
            }
            ed.execCommand('mceInsertContent', false, '<p style="text-align:center;text-indent:0;"><img '+attributes+' /></p><p style="text-align:center;text-indent:0;">'+args.alt+'</p>');
        }

        tinyMCEPopup.close();
    }
};
tinyMCEPopup.onInit.add(CmsTopImageDialog.init, CmsTopImageDialog);
