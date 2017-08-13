$(function(){
	$("#uploadimg").uploader({
			script : '?app=system&controller=image&action=upload',
			fileDataName : 'ctimg',
			fileDesc	 : '图像',
			fileExt		 : '*.jpg;*.jpeg;*.gif;*.png;*.bmp;',
			buttonImg	 :'images/upload.gif',
            jsonType     : 1,
			multi        :false,
			progress:function(data) {
			},
			complete:function(json, data){
                if (json && json.state) {
                    $('#src').val(json.file);
                    ImageDialog.showPreviewImage(json.file);
                } else {
                    ct && ct.error(json && json.error || '上传失败')
                }
			}
	});
});