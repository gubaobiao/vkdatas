;(function(){
	if($("#mp4-url").val() != "") {
		$("#xpc_video").parents().removeClass("hides");
		$("#mod_player").hide();
		commentKeydowFlag = true;
		var origins = [{
			"qiniu_url": $("#mp4-url").val(),
			"width": "1920",
			"height": "1080",
			"resolution": "1920x1080",
		}]
		isTranscoding = 0;
		$("#xpc_video").attr("src", $("#mp4-url").val())
		$("#xpc_video").children().attr("src", $("#mp4-url").val())
	} else if($("#swf-url").val() != "") {
		$("#xpc_video").hide();
		$('#mod_player > embed').remove();
		var str = '<embed id="video-url" class="hides" src="' + $("#swf-url").val() + '" allowfullscreen="true" quality="high" width="960" height="540" align="middle" allowscriptaccess="always" type="application/x-shockwave-flash">';
		$('#mod_player').html(str);
		$("#video-url").removeClass("hides");
		
	}else{
		$("#mod_player").hide();
		$("#xpc_video").attr("src", $("#taobao_720").val());
		$("#xpc_video").children().attr("src",$("#taobao_720").val());
		commentKeydowFlag = true;
		var origins = [{
				"qiniu_url": "",
				"width": "1920",
				"height": "1080",
				"resolution": "1920x1080",
		}, {
				"qiniu_url": "",
				"width": "1280",
				"height": "720",
				"resolution": "1280x720",
		},{
				"qiniu_url": "",
				"width": "640",
				"height": "360",
				"resolution": "640x360",

			}],
		isTranscoding = 0;
		origins[0].qiniu_url = $("#taobao_1080").val();
		origins[1].qiniu_url = $("#taobao_720").val();
		origins[2].qiniu_url = $("#taobao_360").val();
		
	}
	VideoJS.setupAllWhenReady({
      controlsBelow: false, // Display control bar below video instead of in front of
      controlsHiding: true, // Hide controls when mouse is not over the video
      defaultVolume: 0.85, // Will be overridden by user's last volume if available
      flashVersion: 9, // Required flash version for fallback
      linksHiding: true, // Hide download links when video is supported
      origin:origins,
      isTranscoding:isTranscoding
	});
})();