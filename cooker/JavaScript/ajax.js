// 調理履歴
function historyfunc(imageName, holoNum){
	// 送るデータ
	var name = imageName;
	var groupNo = holoNum;
	var data = {"imageName": name, "groupNo": groupNo};
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

// プロフィール
function profilefunc(obj){
	// クリックされたフォームのIDを取得
	var formId = obj.getAttribute('data-formid');
	// ローディングマークを表示
	var elm = document.getElementById("loading-" + formId);
	elm.innerHTML = "<img src='../Images/load.gif'><br><p class='text'>登録中・・・</p>";
	// フォームデータを取得
	var formdata = new FormData(document.getElementById(formId));
	// XMLHttpRequestによるアップロード処理
	var xhttpreq = new XMLHttpRequest();
	xhttpreq.onreadystatechange = function() {
		if (xhttpreq.readyState == 4 && xhttpreq.status == 200) {
			var res = xhttpreq.responseText;
			console.log(res);
			var text = "";
			// 投稿成功時
			if(res == "success"){
				text = "<p class='text'>登録完了しました</p>";
			// 投稿失敗時はエラーごとにメッセージ表示
			}else if(res == "nameErr"){
				text = "<p class='err_text'>名前を入力してください</p>"
			}else if(res == "relationErr"){
				text = "<p class='err_text'>続柄を選択してください</p>"
			}else if(res == "birthNullErr"){
				text = "<p class='err_text'>生年月日を入力してください</p>"
			}else if(res == "birthErr"){
				text = "<p class='err_text'>生年月日は半角数字8桁で入力してください</p>"
			}else if(res == "dbErr"){
				text = "<p class='err_text'>データベースエラー</p>"
			}else if(res == "idErr"){
				text = "<p class='err_text'>USER IDエラー</p>"
			}
			elm.innerHTML = text;
		}
	};
	xhttpreq.open("POST", "./php/profilefunc.php", true);
	xhttpreq.send(formdata);
}