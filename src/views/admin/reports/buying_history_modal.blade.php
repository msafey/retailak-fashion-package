  <div class="modal-dialog modal-lg email_modal_exist">
    <div class="modal-content">
    <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">History Of Buying Price Of Purchase Order</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">


          <div class="form-group">
              
                            <!-- <label>Buying History </label> -->
                            <table class="table" style="width:100%">

                                <tr>
                                  <th>Purchase Order ID</th>
                                    <th><b>Buying Price</b></th>
                                    <th>Selling Price</th>
                                    <th>Profit</th>
                                </tr>

                                <tr>


                                      <!-- <table > -->

                                          @foreach (json_decode($purchased_product_details) as $detail)

                                              <tr class="removeborder">
                                                  <td>{{$detail->purchase_order_id}}</td>
                                              <!-- </tr> -->
                                              <!-- <tr class="removeborder"> -->
                                                  <td>{{$detail->item_rate}}</td>
                                              <!-- </tr> -->

                                              <!-- <tr class="removeborder"> -->
                                                  <td>{{$detail->selling_price}}</td>
                                              <!-- </tr> -->
                                              <!-- <tr class="removeborder"> -->
                                                  <td>{{$detail->profit}}</td>
                                              </tr>

                                          @endforeach
                                      <!-- </table> -->
                                  </td>



                                


                             

                                </tr>


                            </table>
          </div>



        
                   
          </div>
    </div>
  </div>

