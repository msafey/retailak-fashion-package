<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

<!-- Plugins css-->
    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>


    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>

</head>


<body class="fixed-left">
<!-- Begin page -->
<div id="wrapper">
    <!-- Top Bar Start -->
@include('layouts.admin.topbar')
<!-- Top Bar End -->
    <!-- ========== Left Sidebar Start ========== -->
@include('layouts.admin.sidemenu')
<!-- Left Sidebar End -->

    <!-- Start right Content here -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <!-- Bread Crumb And Title Section -->
<!--             @include('layouts.admin.breadcrumb')
 -->            <!--End Bread Crumb And Title Section -->
                <div class="row">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                {!! Form::open(['url' => '/admin/delivery/man', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">

                        {{--Orders--}}
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="seasons_crew">Delivery-Man<span style="color:red;">*</span>:
                                </label>

                                <select name="delivery_man" class="select2 form-control select2-multiple"
                                        id="dropdown" id="multiple"
                                        data-placeholder="Choose Orders ...">
                                        <option value="0">Osama</option>
                                        <option value="0">Amir</option>
                                        <option value="0">Bshier</option>
                                        <option value="0">Michael</option>
                                </select>
                            </div>
                        </div>

                        {{--Choose Time--}}
                        <div class="row">
                            <div class="col-md-12  form-group">

                                <label style="margin-left: 12px" class="form-group" for="gender">Time Section
                                </label> <br>
                                <div class="col-sm-4">
                                    <div class="radio radio-info radio-inline">
                                        <input data-parsley-group="block1" type="radio" id="inlineRadio1" value=""
                                               name="current_time">
                                        <label for="inlineRadio1"> Now </label>
                                    </div>
                                    <div class="radio radio-inline">

                                        <input hidden data-parsley-group="block2" type="radio" id="inlineRadio2" value="1"
                                               name="gender">
                                        <label for="inlineRadio2"> Choose Time </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--Hidden Time Section--}}
                        <div  id="demo" class="row">
                            <div class="form-group col-sm-4">
                                <label for="seasons_crew">Time Section<span style="color:red;">*</span>:
                                </label>

                                <select name="time_section" class="select2 form-control select2-multiple"
                                        id="dropdown3" id="multiple"
                                        data-placeholder="Choose Time ...">
                                    <option value="0">From 12:05 To 1:00</option>
                                    <option value="0">From 13:05 To 1:00</option>
                                    <option value="0">From 14:05 To 1:00</option>
                                    <option value="0">From 15:05 To 1:00</option>
                                    <option value="0">From 16:05 To 1:00</option>

                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">

                            </div>

                        </div>

                        <div class="wrapper">
                            <ul id="results"><!-- results appear here --></ul>
                            <div class="ajax-loading"><img  style="width: 50px;" height="50px;" src="{{ url('public/imgs/loading.gif') }}" /></div>
                        </div>
                        <script>
                            var page = 1; //track user scroll as page number, right now page number is 1
                            load_more(page); //initial content load
                            $(window).scroll(function() { //detect page scroll
                                if($(window).scrollTop() + $(window).height() >= $(document).height()) { //if user scrolled from top to bottom of the page
                                    page++; //page number increment
                                    load_more(page); //load content
                                }
                            });
                            function load_more(page){
                                $.ajax(
                                    {
                                        url: '?page=' + page,
                                        type: "get",
                                        datatype: "html",
                                        beforeSend: function()
                                        {
                                            $('.ajax-loading').show();
                                        }
                                    })
                                    .done(function(data)
                                    {
                                        if(data.length == 0){
                                            console.log(data.length);

                                            //notify user if nothing to load
                                            $('.ajax-loading').html("No more records!");
                                            return;
                                        }
                                        $('.ajax-loading').hide(); //hide loading animation once data is received
                                        $("#results").append(data); //append data into #results element
                                    })
                                    .fail(function(jqXHR, ajaxOptions, thrownError)
                                    {
                                        alert('No response from server');
                                    });
                            }
                        </script>


                        <div class="row">
                            <button style="margin-left: 25px" type="submit" class="btn btn-primary"><i
                                        class="zmdi zmdi-plus-circle-o"></i>
                                Add Dlivery Man
                            </button>
                        </div>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>


    </div>


</div>


</div>


</div>
</div> <!-- container -->
</div> <!-- content -->
</div>
<!-- End content-page -->
<!-- Footer Area -->
@include('layouts.admin.footer')

<!-- End Footer Area-->
</div>
<!-- END wrapper -->
<script>
    var resizefunc = [];
</script>

<!-- JAVASCRIPT AREA -->



@include('layouts.admin.javascript')

<script src="{{url('components/select2/dist/js/select2.js')}}"></script>

<script src="{{url('/public/')}}/prasley/parsley.js"></script>

<!-- Laravel Javascript Validation -->
<script>

    $(document).on('change', '.checkbox', function() {
        if(this.checked) {
//            alert($(this).val);
            var mytr = $(this).closest('tr');
            var tdname=$(this).closest("tr").find("td:eq(2)").text();
            mytr.hide();


          alert(  tdname);

//            alert(mytr.html("td:nth-child(3)"));

//           alert( $(this).closest("tr").html());
            // checkbox is checked td:nth-child(2)
        }
    });
</script>

<script type="text/javascript">
    $("#dropdown").select2();
</script>

<script type="text/javascript">
    $(".dropdown2").select2();
</script>


<script type="text/javascript">
    $(".dropdown3").select2();
</script>

<script>
    $(document).ready(function(){
        $("#demo").hide();

        $("#inlineRadio2").click(function(){
            $("#demo").show();
        });
    });
</script>



<!-- JAVASCRIPT AREA -->
</body>
</html>