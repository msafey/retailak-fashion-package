  <div class="modal-dialog modal-lg import_modal_exist">
    <div class="modal-content">
    <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Sales Invoice</h5>
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
        
          <div class="form-group">
              <label style="margin-bottom: 0;"  class="form-group" for="from">Total Files: </label>    
              
                <input type='text'  disabled="disabled"  value="{{$total}}" class="form-control"/>

          </div>

          <div class="form-group">
              <label style="margin-bottom: 0;"  class="form-group" for="from">Total Imported Files: </label>    
                <input type='text'  disabled="disabled"  value="{{$imported}}" class="form-control"/>
          </div>

      


          <div class="form-group">
              
                            <label>Duplicates </label>
                            <table class="table" style="width:100%">

                                <tr>
                                    <th>phone</th>
                                    <th>Duplicate Times</th>
                                 
                                </tr>

                                <tr>

                                    <td>

                                            @foreach ($duplicates as $key =>$value)

                                                <tr class="removeborder">
                                                    <td>{{$key}}</td>
                                                </tr>

                                    </td>

                                    <td>

                                                <tr class="removeborder">
                                                    <td>{{$value}}</td>
                                                </tr>

                                    </td>
                                    @endforeach

                                

                                </tr>


                            </table>
          </div>



       
          
         
          </div>
    </div>
  </div>
<script type="text/javascript">

</script>