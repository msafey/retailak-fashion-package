 <div class="row">
                            <div class="col-xs-12">
                                <div class="page-title-box">
                                    <h4 class="page-title">@if(isset($title)) {{ $title }} @endif</h4>
                                    <ol class="breadcrumb p-0">
                                        <li>
                                            <a href="{{url('/admin/home')}}">  @if(isset($slot1)) {{ $slot1 }} @endif </a>
                                        </li>
                                        @if(isset($current))
                                        <li class="active">
                                            {{ $current }}
                                        </li>
                                        @endif
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
