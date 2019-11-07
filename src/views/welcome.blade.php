<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Retailak Fashion</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Styles -->
     
    </head>
    <body>
      <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Retailak Fashion</a>
            </div>
            <ul class="nav navbar-nav ">
	            
                <li class="active"><a href="#">Home</a></li>
                <li><a href="{{url('/login')}}">Login</a></li>
                <!-- <li><a href="{{url('/register')}}">Register</a></li> -->
         
            </ul>
        </div>
    </nav>

            <div class="content">
                <div class="title m-b-md">
	                @if( ! empty($data['result2']))
                   <?php var_dump($result2); echo '<br /> '; 
	                   ?>
	                @endif
                 
                </div>

         
            </div>
        </div>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
