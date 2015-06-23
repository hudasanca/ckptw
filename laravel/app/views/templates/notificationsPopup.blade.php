<div class="notif-popup">
@for($i=0;$i<$limit;$i++)
	@include('templates.notificationsSingle',['notif'=>$notifications[$i]])
@endfor
</div>