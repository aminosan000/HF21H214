function favoritefunc(obj){
	// 送るデータ
	var name = obj.getAttribute('data-imagename');
	var condition = obj.getAttribute('data-condition');
	var data = {"imageName": name, "condition": condition};
	var path = "./php/favoriteFunc.php";
	
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
		if(data == "success"){
			if(condition == "false"){
				obj.setAttribute("data-condition", "true");
				obj.innerHTML = "<i class=\"material-icons red-text text-darken-1 md-36\">favorite</i>";
			}else if(condition == "true"){
				obj.setAttribute("data-condition", "false");
				obj.innerHTML = "<i class=\"material-icons red-text text-darken-1 md-36\">favorite_border</i>";
			}
		}
		return false;
    })
	// 失敗時
    .fail(function(jqXHR, textStatus, errorThrown){
		console.log(data);
		return false;
	});
}