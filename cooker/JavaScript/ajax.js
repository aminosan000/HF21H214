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
			node.children[0].innerHTML = "<div class=\"center\"><img src=\"../Images/cooking.gif\"></div>";
			setTimeout(rewhite, 7000);
			var row = document.getElementById("history").children[2];
			var card = document.createElement("div");
			card.setAttribute("class", "col s6");
			card.innerHTML = "<div class=\"card\"><div class=\"card-image\"><a href=\"\" data-lity=\"data-lity\"><img src=\"\"></a><span class=\"card-title\">1回前</span></div></div>";
			card.children[0].children[0].children[0].href = "../Images/Upload/" + name;
			card.children[0].children[0].children[0].children[0].src = "../Images/Thumbnail/" + name;
			for(var i = 0; i < row.children.length; i++){
				row.children[i].children[0].children[0].children[1].innerHTML = i + 2 + "回前";
			}
			if(row.children.length > 6){
				row.removeChild(row.children[6]);
			}
			row.insertBefore(card, row.children[0]);
			historychart();
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
	var profId = obj.getAttribute('data-profid');
	var prof = document.getElementById("prof" + profId);
	var formId = "form-prof" + profId;
	// ローディングマークを表示
	var elm = document.getElementById("loading-prof" + profId);
	elm.innerHTML = "<img src='../Images/load.gif'><br><p class='text'>登録中・・・</p>";
	// フォームデータを取得
	var formdata = new FormData(document.getElementById(formId));
	// XMLHttpRequestによるアップロード処理
	var xhttpreq = new XMLHttpRequest();
	xhttpreq.onreadystatechange = function() {
		if (xhttpreq.readyState == 4 && xhttpreq.status == 200) {
			elm.innerHTML = xhttpreq.responseText;
			// ボタンとモーダルを関連付ける
			var res = xhttpreq.responseText;
			console.log(res);
			var text = "";
			// 投稿成功時
			if(res == "success"){
				if(profId != 0){
					prof.children[0].children[0].children[0].innerHTML = document.getElementById("name" + profId).value;
					prof.children[0].children[0].children[2].innerHTML = document.getElementById("relation" + profId).value;
					prof.children[1].children[0].children[2].children[0].innerHTML = calculateAge(document.getElementById("birth" + profId).value) + "歳";
					prof.children[1].children[0].children[2].children[1].innerHTML = document.getElementById("favorite" + profId).value.replace(/\s+/g, " , ");
					prof.children[1].children[0].children[2].children[2].innerHTML = document.getElementById("notfavorite" + profId).value.replace(/\s+/g, " , ");
					prof.children[1].children[0].children[2].children[3].innerHTML = document.getElementById("allergy" + profId).value.replace(/\s+/g, " , ");
				}
				text = "<p class='text'>登録完了しました</p>";
			// 投稿失敗時はエラーごとにメッセージ表示
			}else if(res == "nameErr"){
				text = "<p class='err_text'>名前を入力してください</p>";
			}else if(res == "relationErr"){
				text = "<p class='err_text'>続柄を選択してください</p>";
			}else if(res == "birthNullErr"){
				text = "<p class='err_text'>生年月日を入力してください</p>";
			}else if(res == "birthErr"){
				text = "<p class='err_text'>生年月日は半角数字8桁で入力してください</p>";
			}else if(res == "dbErr"){
				text = "<p class='err_text'>データベースエラー</p>";
			}else if(res == "idErr"){
				text = "<p class='err_text'>USER IDエラー</p>";
			}
			elm.innerHTML = text;
		}
	};
	xhttpreq.open("POST", "./php/profilefunc.php", true);
	xhttpreq.send(formdata);
}

// 年齢計算
function calculateAge(birthday){
	var today=new Date();
	today=today.getFullYear()*10000+today.getMonth()*100+100+today.getDate()
	birthday=parseInt(birthday);
	return(Math.floor((today-birthday)/10000));
}

function rewhite(){
	var node = document.getElementById("modal1");
	node.children[0].innerHTML = "<h5>調理完了しました</h5>";
	node.children[1].innerHTML = "<a class=\"modal-action modal-close waves-effect waves-light btn-flat\" onclick=\"closefunc()\">閉じる</a>";
}