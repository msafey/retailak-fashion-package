<!-- Required datatable js -->
<script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Buttons examples -->
<script src="{{url('public/admin/plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/jszip.min.js')}}"></script>

<script src="{{url('public/admin/plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.colVis.min.js')}}"></script>
<!-- Responsive examples -->
<script src="{{url('public/admin/plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script>
	//This Functions related to Select 2 data-foo
	    //This Functions related to Select 2 data-foo
	       function stringMatch(term, candidate) {
	           return candidate && candidate.toLowerCase().indexOf(term.toLowerCase()) >= 0;
	       }

	       function matchCustom(params, data) {
	           // If there are no search terms, return all of the data
	           if ($.trim(params.term) === '') {
	               return data;
	           }
	           // Do not display the item if there is no 'text' property
	           if (typeof data.text === 'undefined') {
	               return null;
	           }
	           // Match text of option
	           if (stringMatch(params.term, data.text)) {
	               return data;
	           }
	           // Match attribute "data-foo" of option
	           if (stringMatch(params.term, $(data.element).attr('data-foo'))) {
	               return data;
	           }
	           // Return `null` if the term should not be displayed
	           return null;
	       }

	       function formatCustom(state) {
	           return $(
	               '<div><div>' + state.text + '</div><div class="foo">'
	                   + $(state.element).attr('data-foo')
	                   + '</div></div>'
	           );
	       }


	        Array.prototype.clean = function(deleteValue) {
	          for (var i = 0; i < this.length; i++) {
	            if (this[i] == deleteValue) {
	              this.splice(i, 1);
	              i--;
	            }
	          }
	          return this;
	       };

</script>
<footer class="footer text-right">
                2018 © Retailak.
            </footer>
