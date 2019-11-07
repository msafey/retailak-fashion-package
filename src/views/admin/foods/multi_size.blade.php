    <div class="col-lg-12 card card-block multiSizeRow" id="multi_size1"
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
                        <select name="size[]" id="multi_size_1" class="form-control multi_size">
                            <option value="">Choose Size</option>
                            <option value="small">Small</option>
                            <option value="medium">Medium</option>
                            <option value="large">Large</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label style="margin-bottom: 0;" class="form-group" for="from">
                            + Price
                        </label>
                    </div>
                    <div class="col-md-2" style="">
                        <input type="text" name="multi_size_price[]"
                               id="multi_size_price1"
                               class="form-control">
                    </div>
                    <div class="col-md-3" style="">
                        <input type="text" placeholder="optional note" name="multi_size_note[]"
                               id="multi_size_note1" class="form-control">
                    </div>
                    <div class="col-md-2" style="float: right;    margin-right: 10px;
">
                        <label style="" for="">Add Extra</label>

                        <button id="multi_size_extra1" type="button"
                                class=" btn btn-sm btn-primary fa fa-plus add_m_extra_row"></button>
                    </div>

                </div>
                <div class="row multiSizeExtraRow1" id="multisize1_1">
                    <div class="col-md-1">
                        <label style="margin-bottom: 0;" class="form-group" for="from">
                            Extras
                        </label>
                    </div>
                    <div class="col-md-2" style=" ">
                        <select name="multi_size_extra_id[]" class="form-control multi_extra get_extra_price" id="multi_size_extra1_1">
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
                        <input type="text" name="multi_size_extra_price[]"
                               id="multi_size_extra_price1_1" class="form-control multi_price">
                    </div>
                    <div class="col-md-1">
                        <label for="">Optional?</label>
                    </div>
                    <div class="col-md-1">
                        <input type="checkbox" style="margin-top: 6px;"
                               class="form-control optional" value="1"
                               name="multi_size_is_optional[]" checked
                               id="multi_size_checked_optional1_1">
                        <input id='multi_size_unchecked_optional1_1' disabled
                               class="un_optional"
                               type='hidden' value='0' name='multi_size_is_optional[]'>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-danger removeBTN" style="float: right;"
                                type="button"
                                id="multisize_remove1_1"><i class="fa fa-minus"
                                                            aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
