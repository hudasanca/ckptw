<div style="width:-moz-max-content;width: -webkit-max-content;padding-top:10px">
	@foreach($media->data as $key)
		<a href="#"><img class="insta-image" id="instagram-media" real-src="{{$key->images->standard_resolution->url}}" style="height:100px;width:100px" src="{{$key->images->thumbnail->url}}"></a>
	@endforeach
</div>