<div class="col-lg-7" style="max-height: 300px;" class="ItemCard1" id="{{$item['key']}}">
 	<div class="card card-block" style="max-height: 300px;min-height: 300px;overflow-y: scroll;overflow-x: hidden;">
 		@if(isset($item['method_view']) && $item['method_view'] == 'cart' || $item['method_view'] == 'edit_cart')
			<button class="btn btn-danger btn-xs delItem {{$item['reference']}}" type="button" style="float: right;" value=""><i class="fa fa-trash"></i></button>
			<button class="btn btn-primary btn-xs updateCart" type="button"  style="float: right;margin-right: 5px;" value=""><i class="fa fa-edit"></i></button>
			<button class="btn btn-success btn-xs save_updates"  type="button"  style="float: right;margin-right: 5px;display: none;" value=""><i class="fa fa-check"></i></button>
			<input type="text" hidden="" name="method_view"  id="method_view{{$item['key']}}" disabled="" value="{{$item['method_view']}}">
		@endif

		@if(isset($item['order_item']))
			<input type="text" hidden="" name="method_view"  id="order_item{{$item['key']}}" disabled="" value="{{$item['order_item']}}">
		@endif


	<input type="text" hidden="" disabled="" name="object_to_remove" value="{{ json_encode($item) }}" class="object_to_remove" id="object_to_remove{{$item['key']}}">	
			<div class="col-sm-12" style="margin-bottom: 10px;">	
					<label for="">Product:</label>
					<label for=""><span style="color: red;">{{$item['parent_name']}}</span></label>
					<input type="text" hidden="" id="item{{$item['key']}}" name="ItemCart[]" value="{{ json_encode($item) }}">
			</div>
		<div class="row">
			<div class="col-sm-12">
				<table class="table" style="margin:10px;width: 90%;    margin-bottom: 0 !important;">
					<thead>
							<tr style="width: 90%">
								@if($item['type'] == 'options')
									<td><b>Option</b></td>
								@else
									<td><b>Size</b></td>
								@endif
								<td><b>Qty</b></td>
								<td><b>Price</b></td>
							</tr>
					</thead>
					<tbody>
						<tr style="width: 90%">
							<div class="col-sm-12">
								<div class="col-sm-4">
									<td><input type="text" disabled="" id="option_value" class="form-control m-0"  value="{{$item['option']}}"></td>
								</div>
								<div class="col-sm-4"><td><input type="number" min="1" disabled="" id="order_qty" class="form-control m-0 change_qty order_qty" value="{{$item['qty']}}"></td></div>
								<div class="col-sm-4"><td>
									<input type="text" disabled="" id="" class="form-control order_rate m-0" value="{{$item['item_rate']}} LE">
								</td></div>
							</div>
						</tr>
					</tbody>
				</table>
				
			</div>
		</div>

		@if(count($item['extras'])>0)
		<div class="row">
			<div class="col-sm-12">
				<table class="table" style="margin:10px;width: 90%;    margin-bottom: 0 !important;">
					<thead>
						<tr style="width: 90%">
							<td>Extra</td>
							<td>Qty</td>
							<td>Price</td>
						</tr>
					</thead>
					<tbody>
						<?php $i=1;?>
						@foreach($item['extras'] as $extra)
							@if($extra['accepted'] == 1)
								<tr style="width: 90%;">
									<div class="col-sm-12">
										<div class="col-sm-4">
											<td><input type="text" class="form-control" disabled="" id="extra_name{{$i}}" value="{{$extra['extra_name']}}"></td>
										</div>
										<div class="col-sm-4">
											<td><input type="number" class="form-control extra_qty change_qty" disabled="" min="1" id="extra_qty{{$i}}" value="{{$extra['qty']}}"></td>

											<input type="text" hidden="" disabled="" id="extra_id{{$i}}" class="extra_id_value" value="{{$extra['id']}}">
										</div>
										<div class="col-sm-4">
										<td><input type="text" id="extra_rate{{$i}}" disabled="" class="extra_rate form-control" value="{{$extra['rate']}} LE"></td>
											
										</div>
									</div>
								</tr>
							@endif
							<?php $i++; ?>
						@endforeach
					</tbody>
				</table>
				
			</div>
		</div>
		@endif
		<div class="row" style="margin:10px 10px 0 10px">
			<h3>Total:</h3>
			<input type="text" class="form-control total_cart_item" value="{{$item['total']}} LE" disabled="">
		</div>
	</div>
</div>