
Hello ,

<br>
<br>
Error Detected in Retailak ,

<br>
<br>
<?php if(is_array($error)){ ?>
  Error Type : <b>Laravel</b> <br />
  @if(!is_array($error[0]))
  Error : {{$error[0]}} <br />
  @endif
    @if(!is_array($error[1]))
  Error File : {{$error[1]}}<br />
  @endif
    @if(!is_array($error[2]))
  Error Line : {{$error[2]}} 
    @endif
  @if(isset($error[3]))
  @if(!is_array($error[3]))
  <br />Request : {!!$error[3]!!}
  @endif
  @endif

<?php }else {?>
Error Type : <b>Erp Next</b> <br />
Error  :    {!!$error!!}.
<?php } ?>
<br>
<br>
<br>
Thanks,
<br>
Retailak Team
