<!-- Start One Size -->
<div class="row col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 " style="margin-bottom: 10px;">
        <div class="col-lg-1">
            <input type="radio" value="one_size" class="food_types" id="one_size_type" name="food_type">
        </div>
        <div class="col-lg-2" style="margin-left: -40px;">
            <label style="margin-bottom: 0;" class="form-group" for="from">One Size
            </label>
        </div>

        <div id="one_size_price"  style="display:none;">
            <div class="col-lg-1">
                <label style="margin-bottom: 0;" class="form-group" for="from">Price
                </label>
            </div>

            <div class="col-lg-2">
                <input type="text" value="" class="form-control" name="one_size_price">
            </div>

        </div>

    </div>
 <div id="one_size_field" style="margin-bottom: 10px ;border: 1px solid;display: none" class="col-lg-12 card card-block"> 
  @include('admin.foods.one_size')
</div>
<!-- End One SIZE -->

<!-- Start Multi -->
    <div class="row col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 "
         style="margin-bottom: 10px;">
        <div class="col-lg-1">
            <input type="radio" name="food_type" class="food_types" id="multi_size_type"
                 value="multi_sizes">
        </div>
        <div class="col-lg-3" style="margin-left: -40px;">
            <label style="margin-bottom: 0;" class="form-group" for="from">Multi Size
            </label>
        </div>
        <div class="col-lg-2" id="m-add"
             style="float: right;display:none;margin-right:-44px">
            <label style="margin-bottom: 0;" class="form-group" for="from">Add Size
            </label>
            <button id="add_m_size" type="button"
                    class="btn btn-sm btn-primary fa fa-plus add_m_extra"></button>
        </div>
    </div>
    <div id="multi_size_field" style="display: none;margin-bottom: 10px">
        @include('admin.foods.multi_size')
    </div>
<!-- End Multi -->


<!-- Start Option -->
    <div class="row col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 "
         style="margin-bottom: 10px;">
        <div class="col-lg-1">
            <input type="radio" value="options" class="food_types" id="option_type"  name="food_type">
        </div>
        <div class="col-lg-3" style="margin-left: -50px;">
            <label style="margin-bottom: 0;" class="form-group" for="from">Option
            </label>
        </div>
        <div class="col-lg-3" id="opt-add"
             style="float: right;display: none;margin-right: -98px;">
            <label style="margin-bottom: 0;" class="form-group" for="from">Add Option
            </label>
            <button id="add_option" type="button"
                    class="btn btn-sm btn-primary fa fa-plus add_options"></button>
        </div>
    </div>
<div id="options_field"  style="margin-bottom: 10px;display: none;">
    @include('admin.foods.options')
</div>
<!-- End Options -->

@include('admin.foods.scripts')