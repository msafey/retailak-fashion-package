<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Retailak admin panel for managing the platform and orders">
<meta name="author" content="Coderthemes">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- App Favicon -->
<link rel="shortcut icon" href="http://khotwh.com/assets/img/favicon.png">


<!-- App title -->


<title>{{ config('app.name', 'Khotwh') }}</title>



<!--Morris Chart CSS -->
<!-- <link rel="stylesheet" href="{{url('public/admin/plugins/morris/morris.css')}}"> -->

<!-- Switchery css -->
<link href="{{url('public/admin/css/bootstrap-datetimepicker-standalone.css')}}" rel="stylesheet" />

<link href="{{url('public/admin/css/bootstrap-datetimepicker.css')}}" rel="stylesheet" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="{{url('public/admin/plugins/switchery/switchery.min.css')}}" rel="stylesheet"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
<!-- App CSS -->
<link href="{{url('public/admin/css/style.css')}}" rel="stylesheet" type="text/css"/>

<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
<!-- Modernizr js -->
<script src="{{url('public/admin/js/jquery.min.js')}}"></script>
<script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
<script>
    window.Laravel = <?php echo json_encode([
        'csrfToken' => csrf_token(),
    ]); ?>
</script>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}


    .hljs-pre {
        background: #f8f8f8;
        padding: 3px;
    }

    /*.input-group {
        width: 110px;
        margin-bottom: 10px;
    }*/
</style>





