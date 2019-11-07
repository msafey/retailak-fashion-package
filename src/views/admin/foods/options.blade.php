 <div class="col-lg-12 card card-block optionRow" id="type_option1"
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
                        <input type="text" class="form-control option_name" name="option_name[]"
                               id="option_name1">
                    </div>
                    <div class="col-md-1">
                        <label style="margin-bottom: 0;" class="form-group" for="from">
                            + Price
                        </label>
                    </div>
                    <div class="col-md-2" style="">
                        <input type="text" name="option_price[]" id="option_price1"
                               class="form-control">
                    </div>
                    <div class="col-md-3" style="">
                        <input type="text" placeholder="optional note" name="option_note[]"
                               id="option_note1" class="form-control">
                    </div>
                    <div class="col-md-2" style="float: right;margin-right: 10px;">
                        <label style="" for="">Add Extra</label>
                        <button id="add_option_extra1" type="button"
                                class="btn btn-sm btn-primary fa fa-plus add_option_row"></button>
                    </div>
                </div>
                <div class="row optionExtraRow1" id="option1_1">
                    <div class="col-md-1">
                        <label style="margin-bottom: 0;" class="form-group" for="from">
                            Extras
                        </label>
                    </div>
                    <div class="col-md-2" style=" ">
                        <select name="option_extra_id[]" class="form-control option_extra get_extra_price"
                                id="option_extra1_1">
                            <option value="">Choose Extra</option>
                                         @foreach($extra_products as $extra_product)
                                         <option value="{{$extra_product->id}}">{{$extra_product->name_en}}</option>
                                         @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label style="margin-bottom: 0;" class="form-group" for="from">
                            + Price
                        </label>
                    </div>
                    <div class="col-md-2" style="">
                        <input type="text" name="option_price[]"
                               id="option_extra_price1_1" class="form-control option_price">
                    </div>
                    <div class="col-md-1">
                        <label for="">Optional?</label>
                    </div>
                    <div class="col-md-1">
                        <input type="checkbox" style="margin-top: 6px;"
                               class="form-control optional" value="1"
                               name="option_is_optional[]" checked
                               id="option_checked_optional1_1">
                        <input id='option_unchecked_optional1_1' class="un_optional"
                               type='hidden' value='0' disabled name='option_is_optional[]'>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-danger removeBTN" style="float: right;"
                                type="button"
                                id="option_remove1_1"><i class="fa fa-minus"
                                                         aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>