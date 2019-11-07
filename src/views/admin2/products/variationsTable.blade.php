<table class="table" style="border: 1px solid;padding: 10px;">
	<thead>
		<tr>
			<td class="col-md">Variations</td>
			<td class="col-md">Price</td>
			<td class="col-md">Cost</td>
			<td class="col-md">Images</td>
		</tr>
	</thead>
	<tbody>
		<?php $i=1; ?>
		@foreach($size_variation as $key => $value)
		<tr>
			<td class="col-md">
				<input type="text" disabled="" class="form-control" value="{{$value}}">
				<input type="text" hidden="" name="variation_item{{$i}}" value="{{$size_variation_id[$key]}}">
			</td>
			<td class="col-md">
				<input type="text" class="form-control variant_price" value="{{$parent_price}}" name="variation_price{{$i}}">
			</td>
			<td class="col-md">
				<input type="text" class="form-control variant_cost" value="{{$parent_cost}}" name="variation_cost{{$i}}">
			</td>
			<td class="col-md">
				<input type="file" multiple="" name="variation_image{{$i}}[]"  class="form-control">
			</td>
		</tr>
		<?php $i++;?>
		@endforeach
	</tbody>
</table>