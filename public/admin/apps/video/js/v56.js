/* 
56视频上传接口
*/
(function(){
	var baseUrl = '';
	var videoUploadDialog;
	var v56Uploader = function() {};
	cmstop.fet(IMG_URL+'apps/baoliao/css/video.css');
	cmstop.fet(IMG_URL+'js/lib/dialog/style.css');
	cmstop.fet(IMG_URL+'js/lib/cmstop.dialog.js', function(){
		v56Uploader = function(callback) {
			var refreshCount = 0;
			if (!videoUploadDialog) {
				videoUploadDialog = new window.dialog({
					width: 370,
					height: 344,
					hasOverlay: 1,
					hasCloseIco: 1,
					html: '<iframe id="v56_frame" border="0" frameborder="0"></iframe>'
				});
			}
			videoUploadDialog.open();

			$('#v56_frame').attr('src', APP_URL+'?app=cloud&controller=video56&sid=video').css({
				width: '370px',
				height: '346px'
			}).bind('load', function(){
				if (refreshCount++ == 6) {
					videoUploadDialog.close();
					var json = eval('('+$.cookie(COOKIE_PRE+'video')+')');
					callback(json);
				}
			});
		};	
	});
	$.fn.extend({
		v56Uploader: function(callback) {
			this.bind('click', function(event) {
				event.preventDefault();
				$.cookie(COOKIE_PRE + 'video', null, {
					'expires': -1,
					'domain': COOKIE_DOMAIN
				});
				v56Uploader(callback);
			});
		}
	});
})();