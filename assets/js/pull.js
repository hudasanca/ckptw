$(document).ready(function(){
	$('#load-more-status-button').click(function(){
		loadMoreStatus();
	});

	$(window).scroll(function(){
		var tombol = document.getElementById('load-more-status-button').getBoundingClientRect();
		var tombolTop = tombol.bottom-($('#load-more-status-button').height()*5);
		if (tombolTop<$(window).height()&&$('#leftbar-outer').css('display')=='block'&&!isLoadingStatus) {
			isLoadingStatus = true;
			loadMoreStatus();
		}
	});

	$.ajax({
		url: root+'poll',
		method: 'POST',
		type: 'json',
		data: 'msg=jumlah&route='+route+"&path="+path,
		success: function(data){
			jumlahStatus = data.jumlahStatus;
			// alert(jumlahStatus);
			jumlahNotif = data.jumlahNotif;
			// alert(jumlahNotif);
			poll();
		}
	});

	$('#get-instagram').click(function(event){
		$.ajax({
			'url':'http://ckptw.com/get/photos/from/instagram',
			'method':'get',
			'success':function(data,status){
				// alert(data);
				$('#instagram-result').html(data);
				$('#status-form').animate({
					'top':'-=100'
				});
				modalAktif = 'status-form';
			},
			'complete':function(){
				$('img#instagram-media').click(function(event){
					$("#status-form #photo").val($(this).attr('real-src'));
					$('img#instagram-media[class=insta-image-active]').attr('class','ista-image');
					$(this).attr('class','insta-image-active');
					event.preventDefault();
				});
				// $('img#instagram-media').blur(function(){
				// 	$(this).attr('class','insta-image');
				// });
			}
		});
		event.preventDefault();
	});
});

function loadMoreStatus(){
	$.ajax({
		'url':path,
		'method':'get',
		'data':'page='+page+'&skip='+skip,
		'beforeSend':function(){
			$('#load-more-status-button').button('loading');
		},
		'success':function(data,status){
			if (route!='{username}.followers') {
				$('#load-more-status-button').before(data);
			}
			else{
				$('#followers-box').append(data);
			}
			hideMe();
			page++;
			// alert(page);
			$('#load-more-status-button').button('reset');
			if (data=='') {
				$('#load-more-status-button').html('No more statuses');
			}
			pushInitiate();
			setTimeout(function(){
				isLoadingStatus = false;
			},1000);
		}
	});
}


function poll(){
	setTimeout(function(){
		$.ajax({
			url: root+'poll',
			type: 'POST',
			data: 'msg=update&statusClient='+jumlahStatus+'&notifClient='+jumlahNotif+'&route='+route+"&path="+path,
			dataType: 'json',
			success: function(data){
				if(route=='{username}.status.index'||route=='root'){
					//status
					$('#status').before(data.status);
					hideMe();
					pushInitiate();
	
					jumlahStatus = data.jumlahStatus;
				}

				//notifikasi
				if (parseInt(data.marginNotif)>0) {
					// alert("jumlahNotif:"+jumlahNotif+"\ndata.jumlahNotif:"+data.jumlahNotif+"\nNotif:"+data.notif);
					notifPopup(data.notif);
					$('#messages').prepend(data.notifGede);
					$('#arrow').after(" "+data.notifIcon);
				}
				jumlahNotif = data.jumlahNotif;
				poll();
			}
		});
	},10000);
}

function notifPopup(popup){
	if ($('#leftbar-outer').css('display')=='block') {
		$('#content').before(popup);
		$('.notif-popup').fadeIn(1000, function(){
			setTimeout(function(){
				$('.notif-popup').fadeOut();
			},2000);
		});
	}
}