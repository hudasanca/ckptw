var deleteStatus = "";
$(document).ready(function(){
	$('#status-form').submit(function(event){
		var formData = $(this).serialize();
		$.ajax({
			'url':root+username+'/status',
			'method':'post',
			'data':formData,
			'beforeSend':function(){
				$('#status-form #post').button('loading');
			},
			'success':function(data,status){
				if(route!='{username}.status.show'){
					$('#status').before(data);
					hideMe();
				}
				$('#status-form #post').button('reset');
				$('#status-form')[0].reset();
				skip++;
				pushInitiate();
				jumlahStatus = parseInt(jumlahStatus)+1;
			},
			'complete':function(){
				modalAktif = 'status-form';
				tutupModal();
				// alert(modalAktif);
			}
		});
		event.preventDefault();
	});

	$('#status-form-mobile').submit(function(event){
		var formData = $(this).serialize();
		$.ajax({
			'url':root+username+'/status',
			'method':'post',
			'data':formData,
			'beforeSend':function(){
				$('#post').button('loading');
			},
			'success':function(data,status){
				var boleh ='http://ckptw.com/'+username+'/status';
				if(route!='{username}.status.show'&&(path==boleh||route=="root")){
					$('#status').before(data);
					hideMe();
				}
				$('#post').button('reset');
				$('#status-form-mobile')[0].reset();
				skip++;
				pushInitiate();
				jumlahStatus = parseInt(jumlahStatus)+1;
			}
		});
		event.preventDefault();
	});

	$('#comment-form').submit(function(event){
		var data = $(this).serialize();
		$.ajax({
			'url':$(this).attr('action'),
			'data':data,
			'method':'post',
			'beforeSend':function(){
				$('#post').button('loading');
			},
			'success':function(data,status){
				$('#post').button('reset');
				$('.comment-form').before(data);
				$('#comment-form')[0].reset();
				pushInitiate();
			}
		});
		event.preventDefault();
	});

	pushInitiate();
});

function pushInitiate(){
	$('a#love-status').click(function(event){
		var me = $(this);
		var id = me.attr('id');
		var act = me.attr('act');
		event.preventDefault();
		$.ajax({
			'url':me.attr('href'),
			'method':'get',
			'beforeSend':function(){
				if (act=="love") {
					me.css({
						'color':'#5947B7'
					});
					me.attr('act','unlove');
				}
				else{
					me.css({
						'color':'#646464'
					});
					me.attr('act','love');
				}
			},
			'success':function(data,status){
				// alert($(this).attr('id'));
			}
		});
	});

	$('a#love-comment').click(function(event){
		var me = $(this);
		var id = $(this).attr('id');
		var act = $(this).attr('act');
		$.ajax({
			'url':$(this).attr('href'),
			'method':'get',
			'beforeSend':function(){
				if (act=="love") {
					me.css({
						'color':'#5947B7'
					});
					me.attr('act','unlove');
				}
				else{
					me.css({
						'color':'#646464'
					});
					me.attr('act','love');
				}
			},
			'success':function(data,status){
				// alert($(this).attr('id'));
			}
		});
		event.preventDefault();
	});

	$('a#delete-confirmation-button').click(function(){
    	var me = $(this);
    	var act = me.attr('act');
    	if (act=='no') {
    		tutupModal();
    	}
    	else{
    		$.ajax({
    			'url': href,
    			'method':'get',
    			'type':'json',
    			'beforeSend':function(){
    				me.button('loading');
    			},
    			'success':function(data){
    				$.ajax({
		    			'url': data.link,
		    			'method':'post',
		    			'data':'_method=delete&_token='+data.token,
		    			'success':function(data2){
		    				// alert(data2);
    						deleteStatus.parent().parent().parent().slideUp(400, function(){
    							deleteStatus.parent().parent().parent().remove();
    						});
    						deleteStatus = "";
    						jumlahStatus = parseInt(jumlahStatus)-1;
		    			},
		    			'complete':function(){
		    				me.button('reset');
		    				tutupModal();
		    			}
		    		});
    			}
    		});
    	}
    });

	modalAktif = 0;

    $('a#btn').click(function(event){
		var me = $(this);
		var attr = me.attr('id-impact');
		// $(this).attr('href','javascript:void');
		// $('#leftbar-outer').css('display')=='block'
		if (modalAktif==0&&$('#leftbar-outer').css('display')=='block') {
			href = me.attr('href');
			openModal(attr);
			if (attr=="delete-pop-up") {
				deleteStatus = me;
			}
			// event.preventDefault();
		}
		// else{
		// 	tutupModal();
		// }
		if ($('#leftbar-outer').css('display')=='block') {
			event.preventDefault();
			notifUp();	
		}
	});

}

function statusSubmit(){
	$('#status-form').submit();
}