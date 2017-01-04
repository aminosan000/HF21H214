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
			var favorite = document.getElementById("favorite").children[1].children[0];
			var id = "food" + (name.slice(0, 10));
			if(condition == "false"){
				obj.setAttribute("data-condition", "true");
				obj.innerHTML = "<i class=\"material-icons red-text text-darken-1 md-36\">favorite</i>";
				var card = document.getElementById(id).cloneNode(true);
				var favbutton = card.children[0].children[0].children[2].children[0].children[0];
				var modal = card.children[1];
				var combutton = card.children[0].children[0].children[2].children[0].children[2];
				card.setAttribute("id", "favorite" + (name.slice(0, 10)));
				favbutton.setAttribute("data-condition", "true");
				modal.setAttribute("id", "modal-favorite-comment" + combutton.getAttribute("data-target").slice(-10)); 
				combutton.setAttribute("data-target", "modal-favorite-comment" + combutton.getAttribute("data-target").slice(-10));
				favorite.children[0].innerHTML = "お気に入り" + (parseInt(favorite.children[0].innerHTML.match(/\d+/), 10) + 1) + "件";
				favorite.insertBefore(card, favorite.children[1]);
				// ボタンとモーダルを関連付ける
				$('.modal-trigger').leanModal({
					dismissible: true,  // 画面外のタッチによってモーダルを閉じるかどうか
					opacity: 0.4,       // 背景の透明度
					in_duration: 400,   // インアニメーションの時間
					out_duration: 400,  // アウトアニメーションの時間
					// 開くときのコールバック
					ready: function() {
						console.log('ready');
					},
					// 閉じる時のコールバック
					complete: function() {
						console.log('closed');
					}
				});
			}else if(condition == "true"){
				var button = document.getElementById(id).children[0].children[0].children[2].children[0].children[0];
				button.setAttribute("data-condition", "false");
				button.innerHTML = "<i class=\"material-icons red-text text-darken-1 md-36\">favorite_border</i>";
				favorite.children[0].innerHTML = "お気に入り" + (parseInt(favorite.children[0].innerHTML.match(/\d+/), 10) - 1) + "件";
				var card = document.getElementById("favorite" + (name.slice(0, 10)));
				favorite.removeChild(card);
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