
            <div class="col-md-2" style="float: right;  margin-right: 10px;margin-top:12px">
                <label style="" for="">Add Extra</label>
                <button id="one_size_extra" type="button"
                        class="btn btn-sm btn-primary fa fa-plus add_one_size_extra"></button>
            </div>
            <div class=" row oneSizeExtraRow" id="onesize1">
                <div class="col-md-1">
                    <label style="margin-bottom: 0;" class="form-group" for="from">
                        Extras
                    </label>
                </div>
                <div class="col-md-2" style=" ">
                    <select name="one_size_extra_id[]" class="form-control one_size_extra get_extra_price"
                            id="one_size_extra1">
                        <option value="">Choose Extra</option>
                            @foreach($extra_products as $extra)
                            <option value="{{$extra->id}}">{{$extra->name_en}}</option>
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
                           id="one_size_extra_price1" class="form-control one_size_price">
                </div>
                <div class="col-md-1">
                    <label for="">Optional?</label>
                </div>
                <div class="col-md-1">
                    <input type="checkbox" style="margin-top: 6px;"
                           class="form-control optional" value="1"
                           name="one_size_is_optional[]" checked
                           id="one_size_checked_optional1">
                    <input id='one_size_unchecked_optional1' disabled class="un_optional"
                           type='hidden' value='0' name='one_size_is_optional[]'>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-danger removeBTN" style="float: right;" type="button"
                            id="onesize_remove1"><i class="fa fa-minus"
                                                    aria-hidden="true"></i></button>
                </div>
            </div>
        