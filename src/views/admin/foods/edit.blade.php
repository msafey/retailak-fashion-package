<div id="all_foods_options">

    <!-- One Size -->
    <div class="row col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 " style="margin-bottom: 10px;">
        <div class="col-lg-1">
            <input type="radio" value="one_size" class="food_types"
                   @if(isset($product) && $product->food_type=='one_size')checked @endif id="one_size_type"
                   name="food_type">
        </div>
        <div class="col-lg-2" style="margin-left: -40px;">
            <label style="margin-bottom: 0;" class="form-group" for="from">One Size
            </label>
        </div>

        <div id="one_size_price" @if(isset($product) && $product->food_type=='one_size')style="display: block" @else style="display:none;" @endif>
            <div class="col-lg-1">
                <label style="margin-bottom: 0;" class="form-group" for="from">Price
                </label>
            </div>

            <div class="col-lg-2">
                <input type="text" value="@if(isset($product)){{$product->standard_rate}}@endif" class="form-control" name="one_size_price">
            </div>

        </div>
    </div>

    @if(isset($product) && count($foods_array)>0 && $product->food_type == 'one_size')
        <div id="one_size_field" style="margin-bottom: 10px ;border: 1px solid;"
             @forelse($foods_array as $key => $array)
             class="col-lg-12 card card-block">
            <div class="col-md-2" style="float: right;  margin-right: 10px;margin-top:12px">
                <label style="" for="">Add Extra</label>
                <button id="one_size_extra" type="button"
                        class="btn btn-sm btn-primary fa fa-plus add_one_size_extra"></button>
            </div>
            <?php $i = 1;?>
            @foreach($array['extras'] as $arr)
                <div class=" row oneSizeExtraRow" id="onesize{{$i}}">
                    <div class="col-md-1">
                        <label style="margin-bottom: 0;" class="form-group" for="from">
                            Extras
                        </label>
                    </div>
                    <div class="col-md-2" style=" ">
                        <select name="one_size_extra_id[]" class="form-control one_size_extra get_extra_price"
                                id="one_size_extra{{$i}}">
                            <option value="">Choose Extra</option>
                            @foreach($extra_products as $extra)
                            <option value="{{$extra->id}}" @if($arr->extra_product_id == $extra->id)selected @endif>{{$extra->name_en}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label style="margin-bottom: 0;" class="form-group" for="from">
                            + Price
                        </label>
                    </div>
                    <div class="col-md-2" style="">
                        <input type="text" name="one_size_extra_price[]"
                               id="one_size_extra_price{{$i}}" @if(isset($arr))value="{{$arr->food_extra_price}}"
                               @endif class="form-control one_size_price">
                    </div>
                    <div class="col-md-1">
                        <label for="">Optional?</label>
                    </div>
                    <div class="col-md-1">
                        <input type="checkbox" style="margin-top: 6px;"
                               class="form-control optional" value="1"
                               name="one_size_is_optional[]" @if(isset($arr) && $arr->is_optional ==1)checked
                               @else disabled @endif
                               id="one_size_checked_optional{{$i}}">
                        <input id='one_size_unchecked_optional{{$i}}' @if(isset($arr) && $arr->is_optional ==1)disabled
                               @endif class="un_optional"
                               type='hidden' value='0' name='one_size_is_optional[]'>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-danger removeBTN" style="float: right;" type="button"
                                id="onesize_remove{{$i}}"><i class="fa fa-minus"
                                                        aria-hidden="true"></i></button>
                    </div>
                </div>
                <?php $i++;?>
            @endforeach
        </div>
        @empty
        @endforelse
    @else
        <div id="one_size_field" style="margin-bottom: 10px ;border: 1px solid;display: none" class="col-lg-12 card card-block"> 
          @include('admin.foods.one_size')
        </div>
    @endif

    <!-- End One Size -->







    <div class="row col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 "
         style="margin-bottom: 10px;">
        <div class="col-lg-1">
            <input type="radio" name="food_type" class="food_types" id="multi_size_type"
                   @if(isset($product) && $product->food_type=='multi_sizes')checked
                   @endif  value="multi_sizes">
        </div>
        <div class="col-lg-3" style="margin-left: -40px;">
            <label style="margin-bottom: 0;" class="form-group" for="from">Multi Size
            </label>
        </div>
        <div class="col-lg-2" id="m-add"
             style="float: right;margin-right:-44px">
            <label style="margin-bottom: 0;" class="form-group" for="from">Add Size
            </label>
            <button id="add_m_size" type="button"
                    class="btn btn-sm btn-primary fa fa-plus add_m_extra"></button>
        </div>
    </div>
    <div id="multi_size_field" @if(isset($product) && $product->food_type=='multi_sizes') style="margin-bootom:10px"
         @else style="margin-bottom:10px;display:none;" @endif>
        <?php $i = 1; ?>
        @if(isset($product) && $product->food_type=='multi_sizes')
            @forelse($foods_array as $key=>$array)
                <div class="col-lg-12 card card-block multiSizeRow" id="multi_size{{$i}}"
                     style="border: 1px solid;">
                    <div class="row">
                        <button style="float: right;margin-top:-26px;margin-right:-5px;"
                                type="button"
                                class="btn btn-sm btn-danger removeMultiRow">x
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <label style="margin-bottom: 0;" class="form-group" for="from">
                                Size
                            </label>
                        </div>
                        <div class="col-md-2" style="">
                            <select name="size[]" id="multi_size_{{$i}}" class="form-control multi_size">
                                @if($array['product']->name_en == "small")
                                <option value="small" selected >Small</option>
                                @endif
                                @if($array['product']->name_en == "medium") 
                                <option value="medium" selected >
                                    Medium
                                </option>
                                @endif
                                @if($array['product']->name_en == "large")
                                <option value="large" selected>Large</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label style="margin-bottom: 0;" class="form-group" for="from">
                                + Price
                            </label>
                        </div>
                        <div class="col-md-2" style="">
                            <input type="text" name="multi_size_price[]"
                                   id="multi_size_price{{$i}}" value="{{$array['product']->standard_rate}}"
                                   class="form-control">
                        </div>
                        <div class="col-md-3" style="">
                            <input type="text" placeholder="optional note" name="multi_size_note-{{$array['product']->name_en}}"
                                   id="multi_size_note{{$i}}" value="{{$array['product']->food_optional_note}}"
                                   class="form-control">
                        </div>
                        <div class="col-md-2" style="float: right;    margin-right: 10px;">
                            <label style="" for="">Add Extra</label>

                            <button id="multi_size_extra{{$i}}" type="button"
                                    class=" btn btn-sm btn-primary fa fa-plus add_m_extra_row"></button>
                        </div>
                    </div>
                    <?php $j = 1;?>
                    @foreach($array['extras'] as $extra)
                        <div class="row multiSizeExtraRow{{$i}}" id="multisize{{$i}}_{{$j}}">
                            <div class="col-md-1">
                                <label style="margin-bottom: 0;" class="form-group" for="from">
                                    Extras
                                </label>
                            </div>
                            <div class="col-md-2" style=" ">
                                <select name="multi_size_extra_id-{{$array['product']->name_en}}[]" class="form-control multi_extra get_extra_price"
                                        id="multi_size_extra{{$i}}_{{$j}}">
                                   <option value="">Choose Extra</option>
                                    @foreach($extra_products as $extra_product)
                                    <option value="{{$extra_product->id}}" @if($extra->extra_product_id == $extra_product->id)selected @endif>{{$extra_product->name_en}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label style="margin-bottom: 0;" class="form-group" for="from">
                                    + Price
                                </label>
                            </div>
                            <div class="col-md-2" style="">
                                <input type="text" name="multi_size_extra_price-{{$array['product']->name_en}}[]"
                                       id="multi_size_extra_price{{$i}}_{{$j}}" class="form-control multi_price"
                                       value="{{$extra->food_extra_price}}">
                            </div>
                            <div class="col-md-1">
                                <label for="">Optional?</label>
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" style="margin-top: 6px;"
                                       class="form-control optional" value="1"
                                       name="multi_size_is_optional-{{$array['product']->name_en}}[]" @if($extra->is_optional ==1) checked @endif
                                       id="multi_size_checked_optional{{$i}}_{{$j}}">
                                <input id='multi_size_unchecked_optional{{$i}}_{{$j}}'
                                       @if($extra->is_optional ==1) disabled @endif
                                       class="un_optional"
                                       type='hidden' value='0' name='multi_size_is_optional-{{$array['product']->name_en}}[]'>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-danger removeBTN" style="float: right;"
                                        type="button"
                                        id="multisize_remove{{$i}}_{{$j}}"><i class="fa fa-minus" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        <?php $j++;?>
                    @endforeach
                </div>
                <?php $i++;?>
            @empty
            @endforelse
        @else
            @include('admin.foods.multi_size')
        @endif
    </div>

    <div class="row col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 "
         style="margin-bottom: 10px;">
        <div class="col-lg-1">
            <input type="radio" value="options" name="food_type" class="food_types" id="option_type" @if(isset($product) && $product->food_type === "options")checked @endif >
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


    <div id="options_field" @if(isset($product) && $product->food_type=='options') style="margin-bootom:10px"
         @else style="margin-bottom: 10px;display: none;"@endif>
    

        @if(isset($product) && $product->food_type=='options')
            <?php $i = 1;?>
            @forelse($foods_array as $key => $array)
                <div class="col-lg-12 card card-block optionRow" id="type_option{{$i}}"
                     style="border: 1px solid;">
                    <div class="row">
                        <button style="float: right;margin-top:-26px;margin-right:-5px;"
                                type="button"
                                class="btn btn-sm btn-danger removeMultiRow">x
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <label style="margin-bottom: 0;" class="form-group" for="from">
                                Option Name
                            </label>
                        </div>
                        <div class="col-md-2" style="">
                            <input type="text" class="form-control option_name"
                                   value="@if(isset($array['product'])){{$array['product']->name_en}} @endif"
                                   name="option_name[]"
                                   id="option_name{{$i}}">
                        </div>
                        <div class="col-md-1">
                            <label style="margin-bottom: 0;" class="form-group" for="from">
                                + Price
                            </label>
                        </div>
                        <div class="col-md-2" style="">
                            <input type="text" name="option_price[]"
                                   value="@if(isset($array['product'])){{$array['product']->standard_rate}} @endif"
                                   id="option_price{{$i}}"
                                   class="form-control">
                        </div>
                        <div class="col-md-3" style="">
                            <input type="text" placeholder="optional note" name="option_note-{{$array['product']->name_en}}"
                                   id="option_note{{$i}}"
                                   @if(isset($array['product'])) value="{{$array['product']->food_optional_note}}"
                                   @endif class="form-control">
                        </div>
                        <div class="col-md-2" style="float: right;margin-right: 10px;">
                            <label style="" for="">Add Extra</label>
                            <button id="add_option_extra1" type="button"
                                    class="btn btn-sm btn-primary fa fa-plus add_option_row"></button>
                        </div>
                    </div>
                    <?php $j = 1; ?>
                    @foreach($array['extras'] as $arr)
                        <div class="row optionExtraRow{{$i}}" id="option{{$i}}_{{$j}}">
                            <div class="col-md-1">
                                <label style="margin-bottom: 0;" class="form-group" for="from">
                                    Extras
                                </label>
                            </div>
                            <div class="col-md-2" style=" ">
                                <select name="option_extra_id[]" class="form-control option_extra get_extra_price"
                                        id="option_extra{{$i}}_{{$j}}">
                                        <option value="">Choose Extra</option>
                                         @foreach($extra_products as $extra_product)
                                         <option value="{{$extra_product->id}}" @if($arr->extra_product_id == $extra_product->id)selected @endif>{{$extra_product->name_en}}</option>
                                         @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label style="margin-bottom: 0;" class="form-group" for="from">
                                    + Price
                                </label>
                            </div>
                            <div class="col-md-2" style="">
                                <input type="text" name="multi_size_extra_price[]"
                                       id="option_extra_price{{$i}}_{{$j}}"
                                       value="@if(isset($arr)){{$arr->food_extra_price}} @endif"
                                       class="form-control option_price">
                            </div>
                            <div class="col-md-1">
                                <label for="">Optional?</label>
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" style="margin-top: 6px;"
                                       class="form-control optional" @if($arr->is_optional==1)checked @endif value="1"
                                       name="option_is_optional[]"
                                       id="option_checked_optional{{$i}}_{{$j}}">
                                <input id='option_unchecked_optional{{$i}}_{{$j}}' class="un_optional"
                                       type='hidden' value='0' @if($arr->is_optional == 1) disabled
                                       @endif name='option_is_optional[]'>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-danger removeBTN" style="float: right;"
                                        type="button"
                                        id="option_remove{{$i}}_{{$j}}"><i class="fa fa-minus"
                                                                           aria-hidden="true"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
            @endforelse
        @else
            @include('admin.foods.options')
        @endif
    </div>
</div>
@include('admin.foods.scripts')