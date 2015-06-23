var modalAktif = 0;
var page = 1;
var skip = 0;
var isLoadingStatus = false;
var href = '';
var jumlahStatus = 0;
var jumlahNotif = 0;
$(document).ready(function(){
	inisialisasi();
});

function inisialisasi(){
	$('#notification-bar').click(function(){
		notifToggle();
	});

	hideMe();

	$('#tirai').click(function(){
		tutupModal();
	});

	/* Every time the window is scrolled ... */
    $(window).scroll( function(){
    
        hideMe();
    
    });

    $('#status-form').click(function(event){
    	// alert(modalAktif);
    	// event.preventDefault();
    });
}

function hideMe(){
	/* Check the location of each desired element */
	$('.hideme').each( function(i){
		var bottom_of_object = $(this).position().top; //+ $(this).outerHeight();
		var bottom_of_window = $(window).scrollTop() + $(window).height();
		/* If the object is completely visible in the window, fade it it */
		if( bottom_of_window > bottom_of_object ){
			// $(this).animate({'opacity':'1'},500);    
			$(this).slideDown();
		}
	});
}

function notifToggle(){
	if ($('#notification-bar').position().top==0) {
		notifDown();
	}
	else{
		notifUp();
	}
}

function notifDown(){
	$('#notification-bar').animate({
		'top':$(window).height()-$($('#notification-bar')).height()-20
	});
	$('#notification-body').css({
		'top':'0',
		'z-index':'97'
	});
	$('#notification-body').animate({
		'height':$(window).height()-$($('#notification-bar')).height()-20,
		'opacity':1
	}, 400, function(){
		$('#messages').fadeToggle();
		$('#messages').css({
			'height':'70%'
		});
	});
	$('#notification-bar').css({
		'background': 'rgba(0,0,0,0.9)',
	});
	$('#arrow').attr('class','glyphicon glyphicon-chevron-up');
}

function notifUp(){
	if ($('#notification-bar').position().top!=0) {
		$('#notification-bar').animate({
			'top':0,
		});
		$('#notification-body').animate({
			'height':0,
			'opacity':0
		}, 400, function(){
			$('#notification-body').css({
				'top':'-100px',
				'z-index':'-100'
			});
		});
		$('#messages').fadeToggle();
		$('#notification-bar').css({
			'background': '',
		});
		$('#arrow').attr('class','glyphicon glyphicon-chevron-down');
	}
}

function openModal(id){
	var tinggi = $(window).height();
	var lebar = $(window).width();
	$('#tirai').fadeIn();
	var modal = document.getElementById(id);
	$('#'+id).css({
		'display':'block',
		'position':'fixed',
		'top':'30%',
		'left':(lebar-(lebar*25/100))/2+(lebar*25/100)-($('#'+id).width()/2)
	});
	$('#'+id).animate({
		'top':tinggi/2-($('#'+id).height()/2),
		'opacity':'1'
	},500);
	modalAktif = id;
	$('#'+id+' textarea').focus();
	notifUp();
	// alert(modalAktif);
}

function tutupModal(){
	// var modal = document.getElementById(modalAktif);
	$('#'+modalAktif).fadeOut(400, function(){
		$('#tirai').fadeOut();
	});
	modalAktif = 0;
	// alert(modalAktif);
}