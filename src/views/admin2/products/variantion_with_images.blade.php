
<div class="col-md-12 card card-block " id="field1" style="border: 1px solid">
	<div class="row">
	    <button type="button" style="float: right;" id="del1" class="btn btn-danger delete_variation">
	    <span aria-hidden="true">&times;</span>
	    <!-- href="javascript:void(0)"  --> 
	  </button>
	</div>
<div class="row">

 	<div class="col-lg-12">
 	    <div class="col-lg-12">
 	        <label style="margin-bottom: 0;" class="form-group" for="from">Name: <span style="color:red;">*</span>
 	        </label>
 	    </div>
 	    <div class="col-lg-12" style="margin-top: 0px">
 	    	@foreach($variations_values as $value)
 	    	<div class="col-lg-4">
 	    		<div class='input-group date' style="display: inline;" id=''>
 	    		    <input type='text' required  disabled=""  value="{{$value->variation->name_en}}:{{$value->variant_options->variation_value_en}}"  class="form-control">
 	    		    <input type='text' required name="variation_item1[]" id="variation_item1" class="variation_item1" hidden="" value="{{$value->variation->id}}:{{$value->variant_options->id}}" class="form-control">
 	    		</div>
 	    	</div>
 	    	
 	        @endforeach
 	    </div>
 	</div>
</div>

 <div class="row">
 	<div class="col-lg-6">
  	  	<p>Upload Images For This Variation Only</p>
  	  	<input type="file" multiple="" name="variation_image1[]" id="variation_image1"  class="form-control variant_images">
 	</div>

 </div>

</div>

