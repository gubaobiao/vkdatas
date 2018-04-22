
// 截图功能
var testend;
$("#avatar-modal .avatar-save").hide();
 $('#avatarInput').on('change', function (e) {
 		var filemaxsize = 1024 * 5;
 		var target = $(e.target);
        var Size;
        if(target[0].files[0]!=undefined){
        	Size = target[0].files[0].size / 1024;
        }else{
        	return false;
        }
		if(Size>filemaxsize){
				alert('图片过大，请重新选择!');
				$(".avatar-wrapper").childre().remove;
				return false;
		}
		if (!this.files[0].type.match(/image.*/)) {
			 	alert('请选择正确的图片!')
        }else{
        	var filename = document.querySelector("#avatar-name");
        	var texts = document.querySelector("#avatarInput").value;
			var teststr = texts;//你这里的路径写错了
		    testend = teststr.match(/[^\\]+\.[^\(]+/i);//直接完整文件名的
    		filename.innerHTML = testend;
        	$(".avatar-save").show();
        }
        

        
    });
$(document).ready(function () {
    $(".avatar-save").on("click", function () {
        // 当有图片的时候才能进行截图
        
        if($(this).hasClass("baiduimg")){
	        if ($('#imageHead').attr("src") != 'none') {
	//          event.preventDefault();
	            // 小的显示框
	            var w = $("#imageHead").width();
				var h = $("#imageHead").height();
				
	            var img_lg = document.getElementById('imageHeads');
	            // 截图小的显示框内的内容
	            html2canvas(img_lg, {
	                allowTaint: true,
	                taintTest: false,
	                onrendered: function (canvas) {
	                    //生成base64图片数据
	
	                    var dataUrl = canvas.toDataURL("image/png",1);
                        if($(".avatar-save").hasClass('adImg')){
                            document.getElementById('fileFM_hover').value = dataUrl;
                        }else{
                            $("#edui_input_j1d7i9pg").click();
                            imagesAjaxs(dataUrl);
                        }
	                    
	                }
	            });
	        }
        }else{
            if ($('#imageHead').attr("src") != 'none') {
    //          event.preventDefault();
                // 小的显示框
                var w = $("#imageHead").width();
    			var h = $("#imageHead").height();
    			
                // 截图小的显示框内的内容
                /*
                var croppedCanvas = $('#cropImg').cropper('getCroppedCanvas');
                var roundedCanvas = getRoundedCanvas(croppedCanvas);
                var dataUrl = roundedCanvas.toDataURL();
                $('#fileFM').val(dataUrl);
                */
                
                
                
                 var img_lg = document.getElementById('imageHeads');
            // 截图小的显示框内的内容
            html2canvas(img_lg, {
                allowTaint: true,
                taintTest: false,
                onrendered: function (canvas) {
                    //生成base64图片数据

                    var dataUrl = canvas.toDataURL("image/png",1);
                    document.getElementById('fileFM').value = dataUrl;
                }
            });
                
                
            }
        
        }
    });

    function getRoundedCanvas(sourceCanvas) {
      var canvas = document.createElement('canvas');
      var context = canvas.getContext('2d');
      var width = sourceCanvas.width;
      var height = sourceCanvas.height;

      canvas.width = width;
      canvas.height = height;
      context.beginPath();
     context.arc(width / 2, height / 2, Math.min(width, height) / 2, 0, 2 * Math.PI);
      debugger
      context.strokeStyle = 'rgba(0,0,0,0)';
      context.stroke();
      context.clip();
      context.drawImage(sourceCanvas, 0, 0, width, height);

      return canvas;
    }
});

