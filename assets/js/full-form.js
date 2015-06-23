var max = $('#form-ul').attr('lists');
var currentId = 1;
$(window).ready(function(){
	nextField();
	$('#continue').click(function(){
		nextField();
	});
	$('#back').click(function(){
		previousField();
	});
	$('#registerbtn').click(function(){
		$('#register-form-result').submit();
	});

	$('input').keyup(function(event){
		if (event.keyCode == 13) {
			$('#continue').click();
		};
	});
})

function previousField(){
	if (currentId>1) {
		$('#'+(currentId-2)).slideDown();
		$('#'+(currentId-1)).slideUp();
		currentId--;
		$('#messages').animate({
			'opacity':'0'
		});
		progress();
	}
}

function nextField(){
	var able = false;
	var slug;
	var name = $('#'+(currentId-1)+' input').attr('name');
	if (name=='name') {
		slug = 'method=name';
		if($('#'+(currentId-1)+' input').val()!=''){
			slug += '&name='+$('#'+(currentId-1)+' input').val();
		}
	}
	else if (name=='username') {
		slug = 'method=username';
		if($('#'+(currentId-1)+' input').val()!=''){
			slug += '&username='+$('#'+(currentId-1)+' input').val();
		}
	}
	else if (name=='email') {
		slug = 'method=email';
		if($('#'+(currentId-1)+' input').val()!=''){
			slug += '&email='+$('#'+(currentId-1)+' input').val();
		}
	}
	else if (name=='password') {
		slug = 'method=password';
		if($('#'+(currentId-1)+' input').val()!=''){
			slug += '&password='+$('#'+(currentId-1)+' input').val();
		}
	}
	$.ajax({
		'url':root+'register/validate',
		'method':'post',
		'data':slug,
		'beforeSend':function(){
			$('#continue').button('loading');
		},
		'success':function(data){
			$('#continue').button('reset');
			if (data.validated==false) {
				able = false;
				$('#messages').html(data.messages);
			}
			else if(data.validated==true){
				able = true;
			}

			if (data.validated=='first'){
				able = true;
			}
		},
		'complete':function(data){
			if (name=='password_confirmation') {
				able = $('#password_confirmation').val()==$('#password').val();
				$('#messages').html('Your password did not match');
			}

			if(able){
				$('#'+currentId).slideDown();
				if(currentId>1){
					$('#'+(currentId-1)).slideUp();
					if (currentId==max) {
						var oldName = $('#register-form #name').val();
						var oldUsername = $('#register-form #username').val();
						var oldEmail = $('#register-form #email').val();
						var oldPassword = $('#register-form #password').val();
						var oldPasswordConfirmation = $('#register-form #password_confirmation').val();

						$('#register-form-result #name').val(oldName);
						$('#register-form-result #username').val(oldUsername);
						$('#register-form-result #email').val(oldEmail);
						$('#register-form-result #password').val(oldPassword);
						$('#register-form-result #password_confirmation').val(oldPasswordConfirmation);
						
						$('#continue').slideUp();
						$('#result').slideDown();
						$('#registerbtn').slideDown();

					}
					$('#back').slideDown();
				}
				$('#'+currentId+' input').focus();
				currentId++;
				$('#messages').animate({
					'opacity':'0'
				});
				progress();
			}
			else{
				$('#messages').animate({
					'opacity':'1'
				});
			}
		}
	});
}

function submit(){
	$('input[type=submit]').click();
}

function progress(){
	var panjang = (currentId-2)/(max)*100;
	$('.progress-bar').animate({
		'width':(panjang)+'%'
	});
}