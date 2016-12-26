function historyfunc(imageName){
	// 送るデータ
	var name = imageName;
	var data = {"imageName": name};
	var path = "./php/historyfunc.php";
	
	// jqueryの.ajaxでAjax実行
	$.ajax({
		type: "GET",
		url: path,
		cache: false,
		data: data
	})
	// 成功時
    .done(function(data, textStatus, jqXHR){
        	console.log(data);
		var node = document.getElementById("modal1");
		if(data == "success"){
			node.children[0].innerHTML = "<h5>調理完了しました</h5>";
			node.children[1].innerHTML = "<a class=\"modal-action modal-close waves-effect waves-light btn-flat\" onclick=\"closefunc()\">閉じる</a>";
		}else{
			node.children[0].innerHTML = "<h5 style=\"color:#f00\">調理失敗しました</h5>";
			node.children[1].innerHTML = "<a class=\"modal-action modal-close waves-effect waves-light btn-flat\" onclick=\"closefunc()\">閉じる</a>";
		}
		return false;
    })
	// 失敗時
    .fail(function(jqXHR, textStatus, errorThrown){
		console.log(data);
		return false;
	});
}