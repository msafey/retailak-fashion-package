  <div class="modal-dialog modal-lg selling_modal_exist">
    <div class="modal-content">
    <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">History Of Selling Price Of Sales Order</h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">


          <div class="form-group">
              
                            <!-- <label>Buying History </label> -->
                            <table class="table" style="width:100%">

                                <tr>
                                  <th>Sales Order ID</th>
                                    <th>Selling Price</th>
                                    <th><b>Buying Price</b></th>
                                    <th>Profit</th>
                                </tr>

                                <tr>


                                      <!-- <table > -->

                                          @foreach (json_decode($sales_order_details) as $detail)

                                              <tr class="removeborder">
                                                <a href="{{url('admin/sales-details/'.$detail->order_id)}}">
                                                  <td>{{$detail->order_id}}</td>
                                                  </a>
                                              <!-- </tr> -->
                                              <!-- <tr class="removeborder"> -->
                                                  <td>{{$detail->rate}}</td>
                                              <!-- </tr> -->

                                              <!-- <tr class="removeborder"> -->
                                                  <td>{{$detail->buying_price}}</td>
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

