<?php



	header("Cache-Control: no-cache, must-revalidate"); //Not caching jack shit. Caching errors just aren't worth my time. After finalization this can be reverted to normal.
	header("Expires: Thurs, 8 Sep 1994 05:00:00 GMT"); //Birthday
	header('Content-Type: text/html; charset=UTF-8');

?>


<html>
<head>
	<script src='//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' ></script>
	<link rel="stylesheet" type="text/css" href="music.css">
	<style>
	label {margin: 0.0px 0.0px 6px 11px; text-indent: -11px; line-height: 12px; font: 14px 'Arial', Helvetica, san-serif}
	div.content {
		max-height:87%;
		min-width:900px;
		overflow-y:scroll;
	}
	div.content div.row {
		border:1px solid black;
		max-width:1250px;
		padding:3px;
	}
	span.approval1 {
		background-image: url('images/approved.gif');
	}
	span.approval {
		height: 27px;
		width: 30px;
		border: 1px solid red;
		overflow: hidden;
		margin: -3px 5px 0px 0px;
		float: right;
	}
</style>
</head>
<body>
	<button class='help' onclick='alert("To modify fields simply change a value, then hit save. \nI have not yet implemented the \"Close without saving?\" window.\n To add a venue/performer, fill in the top row and click \"Add\".\n If it is a performer, just leave the [city, phone] field blank.\n Delete button is on it&apos;s way.")'>Help</button>
	<button onclick='call("performer"); '>Performers</button> &nbsp; <button onclick='call("venue"); '>Venues</button>
	<!-- $(".city, .phone").css("visibility", "hidden").css("width", "0px") -->
	<div class='content'>
		<div class='row'>
			<label>Account:
				<input size='30' class='performer'>
			</label>
			<label>Type:
				<select class='type'>
					<option value='performer'>Performer</option>
					<option selected value='venue'>Venue</option>
				</select>
			</label>
			<label>URL:
				<input size='50' class='url'>
			</label>
			<label class='city'>City:
				<input size='50' class='city'>
			</label>
			<label class='phone'>Phone:
				<input size='50' class='phone'>
			</label>
			<label class='cover'>Cover:
				<input size='50' class='cover'>
			</label>
			<button class='add' onclick='approve(this.parentNode, "addAd")'>Add</button>
			<span class='approval'>
		</div>
	</div>
	








	<script>
//YES. THERE ARE INLINE EVENT HANDLERS AT THE TOP. NOT WORTH FIRING AN ONLOAD OR READY EVENT TO ME JUST TO ADD THOSE SO THEY WILL STAY
	function mysqlEsc (str) { //Had unexpected issues before. May as well go overkill on it
		return str.replace(/[\0\x08\x09\x1a\n\r"'\\\%]/g, function (char) { 
			switch (char) {
				case "\0":
					return "\\0";
				case "\x08":
					return "\\b";
				case "\x09":
					return "\\t";
				case "\x1a":
					return "\\z";
				case "\n":
					return "\\n";
				case "\r":
					return "\\r";
				case "\"":
				case "'":
				case "\\":
				case "%":
					return "\\"+char; // prepends a backslash to backslash, percent,
									  // and double/single quotes
			}
		});
	}


	function extract(row) { //Ah the extractor... The indexes is a bit more impressive
		var res = {id:row.id, type:row.querySelector('select').value};
		var children = row.querySelectorAll('input');
		for (var i = 0; i < children.length; i++) {
			res[children[i].className] = encodeURIComponent(mysqlEsc(children[i].value));
		}
		console.log(res);
		return res;
	}


	function addRow(res) {
		var tmp = JSON.parse(res);
		var row = document.getElementsByClassName('row')[0].cloneNode(true);
		row.querySelector('input.performer').value = tmp['performer'];
		row.querySelector('input.url').value = tmp['url'];
		row.querySelector('input.city').value = tmp['city'];
		row.querySelector('input.phone').value = tmp['phone'];
		row.querySelector('select.type').value = tmp['type'];
		row.querySelector('input.cover').value = tmp['cover'];
		row.id = tmp['id'];
		row.querySelector('.approval').className += ' approval'+tmp['link'];
		$(row.querySelector('.approval')).on('click', function() {approve(this.parentNode, 'apprAd')})
		var oldBut = row.querySelector('.add');
		var but = document.createElement('button');
			but.innerText = 'save', but.className = 'save';
			$(but).on('click', function() {
				approve(this.parentNode, 'modAd')
			}).hide();
		var delBut = document.createElement('button');
		$(delBut).on('click', function() {
			approve(this.parentNode, 'adDelete')
		}).text('Delete');
		$(row).append(delBut);
		row.replaceChild(but, oldBut);
		$('.content').append(row);
		 console.log(JSON.parse(res));
		 $(row.querySelectorAll('input')).on('keydown', function() {
		 	$(this.parentNode).css('background-color', 'red');
		 	$(this.parentNode.parentNode).css('background-color', '#ffe680');
		 	$(this.parentNode.parentNode).children('.save').show();
		 })
		 

	}
	function call(type) {
		if (type === 'performer') {
			$(".city, .phone").css("visibility", "hidden").css("width", "0px");
			document.querySelector('label.cover').firstChild.data = 'Genre: ';
		} if (type === 'venue') {
			$(".city, .phone").css("visibility", "").css("width", "");
			document.querySelector('label.cover').firstChild.data = 'Cover: '; 
		} //Both of these are a bit redundant seeming, but that's what it takes
		if (!type) type = lastType;
		lastType = type;
		type = 'type='+type;
		var tmp = $('.row')[0].cloneNode(true); //I guess the best way is to clone an initial row? Building it from scratch within JS seems silly to me
		$('.content').html('').append(tmp);
		$.ajax({
			type: "POST",
			url: "update.php?action=adPerfs",
			data: type,
			success: function(data) {
				results = data.split('^');
				for (var i = 0; i < results.length; i++) {
					addRow(results[i]);
					console.log('looped');
				}

			} 
		});
	}
	function approve(row, flag) {
		var act;
		var content = '';
		if (flag === 'apprAd') {
			var id = row.id;
			var apprs = {0:'nApproved', 1:'approved'}
			var cAppr = (row.querySelector('.approval').className[row.querySelector('.approval').className.length-1] ^= 1); //This ^ operator is interesting... Thanks @rlemon
			act = flag;
			content = 'id=' + id + '&appr=' + (cAppr);
		} else if (flag === 'modAd' || flag === 'addAd' || flag === 'adDelete') { //Whew

			var data = extract(row);
			for (var i in data) {
				content += i+'='+data[i]+'&';
			}
			act = flag;
		}
		if (flag === 'adDelete') { //Yeah... People tend to click places by accident and cause problems
			var con = confirm('Are you sure you want to delete ' + data['performer'] + ' ?')
			if (con === false) return false;
		}
		console.log(content)
		$.ajax({
			type: "POST",
			url: "update.php?action="+act,
			data: content,
			success: function(resp){
				if (act == 'apprAd') {
					var appr = row.querySelector('.approval');
					appr.className = appr.className.replace(cAppr ^= 1, cAppr ^= 1);
				} else if (act == 'modAd') {
					console.log(resp);
				}
				$(row).css('background-color', 'white');
				$(row).children('.save').hide();
				$(row.querySelectorAll('label')).css('background-color', 'white');
				if (act === 'addAd') call();
				if (act === 'addAd') {
					var orig = document.getElementsByClassName('row')[0];
					$(orig).children('label').children('input').val('');

				}
			}
		});
	}



	</script>































