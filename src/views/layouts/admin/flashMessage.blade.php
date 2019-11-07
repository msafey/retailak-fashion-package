<div  id="SessionMessage" class="flash-message" style="width: 50%; margin: auto;box-shadow: 1px 1px 2px #fff , -1px -1px 1px #fff;">
                    @if(Session::has('success'))
                        <p class="alert alert-success text-white" style="text-align: center;">{{ Session::get('success') }} &nbsp; <i class="fas fa-check-double"></i></p>
                    @endif
                </div>
