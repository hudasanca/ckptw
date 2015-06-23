<div class="comment-form"> 
@if(Route::currentRouteName()=="{username}.status.comment.edit")
{{Form::open(['id'=>'edit-comment-form','style'=>'max-width:700px;width:100%','url'=>route('{username}.status.comment.update',[$status->username,$status->id,$comment->id]),'method'=>'put'])}}
@else
{{Form::open(['id'=>'comment-form','style'=>'max-width:700px;width:100%','url'=>route('{username}.status.comment.store',[$status->username,$status->id])])}}
@endif
{{Form::textarea('comment',Route::currentRouteName()!="{username}.status.comment.edit" ? Input::old('comment') : $comment->comment,['placeholder'=>'comment'])}}
{{Form::submit('Post',['id'=>'post','class'=>'btn btn-lg btn-black'])}}
{{Form::close()}}
</div>