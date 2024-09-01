<b>Projektplanung</b><br>

<?php echo $Categories->count(); ?>

{{ $Categories }}
<br>
@foreach($Categories as $Category)
{{$Category->id}}  / {{$Category->name}}
	@if($Category->in_category_id != '')
		<ul>
		@foreach($Categories as $SubCategory)
		
			@if($SubCategory->in_category_id == $Category->id)
			<li>{{ $SubCategory->name }}</li>
			@endif
		@endforeach
		</ul>
	@endif
	<br>
@endforeach

