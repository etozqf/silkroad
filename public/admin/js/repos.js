window.fet && fet.register({
	'admin.editor':{
		assets:'tiny_mce/editor.js',
		test:'jQuery.fn.editor',
		depends:'lib.jQuery admin.tinyMCE admin.ImageEditor'
	},
	'admin.tinyMCE':{
		assets:'tiny_mce/tiny_mce.js',
		test:'window.tinyMCE'
	},
    'admin.ImageEditor':{
        assets:'imageEditor/cmstop.imageEditor.js',
        test:'window.ImageEditor'
    },
	'admin.uploader':{
		assets:'uploader/cmstop.uploader.js',
		test:'window.Uploader',
		depends:'lib.jQuery'
	},
	'admin.tabview':{
		assets:'js/cmstop.tabview.js',
		test:'jQuery.tabview',
		depends:'lib.jQuery'
	},
	'admin.tabnav':{
		assets:'js/cmstop.tabnav.js',
		test:'jQuery.fn.tabnav',
		depends:'lib.jQuery'
	},
	'admin.superAssoc':{
		assets:'css/backend.css js/cmstop.superassoc.js',
		test:'window.superAssoc',
		depends:'lib.jQuery lib.tree admin.tabview'
	},
	'admin.fileManager':{
		assets:'js/cmstop.filemanager.js',
		test:'cmstop.fileManager',
		depends:'lib.jQuery lib.dialog cmstop admin.ImageEditor admin.uploader'
	},
    'admin.datapicker':{
        assets:'apps/system/js/datapicker.js',
        test:'jQuery.datapicker',
        depends:'lib.jQuery lib.uidialog cmstop'
    }
});