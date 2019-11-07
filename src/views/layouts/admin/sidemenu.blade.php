<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
      integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul>
                <li class="text-muted menu-title">Navigation</li>
                <li class="has_sub">
                    <a href="{{url('/admin/home')}}" class="waves-effect"><i class="zmdi zmdi-view-dashboard"></i><span> Dashboard </span>
                    </a>
                </li>
            </ul>
        </div>
        <div id="sidebar-menu">
            <ul>
                @if(Auth::guard('admin_user')->user()->can('users'))


                    <li>
                        <a href="{{url('/admin/users')}}" class="waves-effect"><i class="zmdi zmdi-accounts"></i> <span> Customers </span>
                        </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('frontend_collections'))
                    <li>
                        <a href="{{url('admin/collections')}}" class="waves-effect"><i class="zmdi zmdi-widgets"></i>
                            <span> Frontend Collections </span>
                        </a>
                    </li>
                @endif
                @if(Auth::guard('admin_user')->user()->can('slider'))
                    <li>
                        <a href="{{url('/admin/slides')}}" class="waves-effect"><i class="zmdi zmdi-tv"></i> <span> Frontend Slider </span>
                        </a>
                    </li>
                @endif
                @if(Auth::guard('admin_user')->user()->can('time'))
                    <li>
                        <a href="{{url('/admin/time/section')}}" class="waves-effect"><i class="zmdi zmdi-time"></i>
                            <span> Time </span> </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('promocodes'))
                    <li>
                        <a href="{{url('/admin/promocodes')}}" class="waves-effect"><i class="fas fa-barcode"></i>
                            <span> Promocodes </span> </a>
                    </li>
                @endif
            </ul>
        </div>
        <div id="sidebar-menu">
            <ul>

                @if(Auth::guard('admin_user')->user()->can('purchase_orders'))

                    <li>
                        <a href="{{url('admin/purchase-orders')}}" class="waves-effect"><i
                                class="fas fa-file-invoice-dollar"></i> <span> Purchase Orders </span>
                        </a>
                    </li>
                @endif
                @if(Auth::guard('admin_user')->user()->can('sales_orders'))

                    <li>
                        <a href="{{url('admin/sales-orders')}}" class="waves-effect"><i
                                class="fas fa-shopping-cart"></i> <span> Sales Orders </span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <div id="sidebar-menu">
            <ul>
                @if(Auth::guard('admin_user')->user()->can('categories'))

                    <li>
                        <a href="{{url('admin/categories')}}" class="waves-effect"><i class="zmdi zmdi-widgets"></i>
                            <span> Categories </span>
                        </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('products'))

                    <li>
                        <a href="{{url('admin/products')}}" class="waves-effect"><i class="fas fa-layer-group"></i>
                            <span> Products </span>
                        </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('brands'))

                    <li>
                        <a href="{{url('admin/brands')}}" class="waves-effect"><i class="fas fa-store"></i> <span> Brands </span>
                        </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('variations'))
                    <?php
                    $product_configurations = config('configurations.products');
                    if (isset($product_configurations['variations']) && $product_configurations['variations'] == true) {
                    ?>

                    <li>
                        <a href="{{url('admin/variations')}}" class="waves-effect"><i class="zmdi zmdi-widgets"></i>
                            <span> Variations </span>
                        </a>
                    </li>
                    <?php }?>
                @endif
                @if(Auth::guard('admin_user')->user()->can('uom'))
                    <li>
                        <a href="{{url('/admin/uom')}}" class="waves-effect"><i class="fas fa-balance-scale"></i> <span> Units Of Measure </span>
                        </a>
                    </li>
                @endif
                @if(Auth::guard('admin_user')->user()->can('companies'))

                    <li>
                        <a href="{{url('admin/companies')}}" class="waves-effect"><i class="fas fa-building"></i> <span> Companies </span>
                        </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('suppliers'))

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="fas fa-luggage-cart"></i>
                            <span> Suppliers </span> <span class="menu-arrow"></span></a>
                        <ul class="list-unstyled" style="display: none;">

                            <li>
                                <a href="{{url('/admin/suppliers')}}" class="waves-effect">
                                    <span> Suppliers </span></a></li>
                            <li>
                                <a href="{{url('/admin/supplier-types')}}" class="waves-effect">
                                    <span> Supplier Types </span></a></li>
                            <li>
                        </ul>
                    </li>

                @endif
            </ul>
        </div>
        <div id="sidebar-menu">
            <ul>
                @if(Auth::guard('admin_user')->user()->can('couriers'))
                    <li>
                        <a href="{{url('/admin/delivery/man')}}" class="waves-effect"><i
                                class="zmdi zmdi- zmdi-directions-run"></i> <span> Couriers</span>
                        </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('run_sheets'))
                    <li>
                        <a href="{{url('admin/runsheet')}}" class="waves-effect"><i class="zmdi zmdi-receipt"></i>
                            <span> Run-Sheets </span> </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('line_haul_batch'))

                    <li>
                        <a href="{{url('admin/line-haul-batch')}}" class="waves-effect"><i
                                class="fas fa-align-justify"></i> <span> Line Haul Batch </span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <div id="sidebar-menu">
            <ul>
                @if(Auth::guard('admin_user')->user()->can('districts'))

                    <li>
                        <a href="{{url('admin/districts')}}" class="waves-effect"><i class="fas fa-map"></i> <span> Districts </span>
                        </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('warehouses'))

                    <li>
                        <a href="{{url('admin/warehouses')}}" class="waves-effect"><i class="fas fa-home"></i> <span> Warehouses </span>
                        </a>
                    </li>
                @endif
                @if(Auth::guard('admin_user')->user()->can('price_list'))

                    <li>
                        <a href="{{url('admin/price-list')}}" class="waves-effect"><i
                                class="fas fa-dollar-sign"></i><span> Price List </span>
                        </a>
                    </li>
                @endif


                @if(Auth::guard('admin_user')->user()->can('taxs'))

                    <li>
                        <a href="{{url('admin/taxs')}}" class="waves-effect"><i class="fas fa-money-bill-wave-alt"></i>
                            <span> Taxes </span>
                        </a>
                    </li>
                @endif


                @if(Auth::guard('admin_user')->user()->can('shipping_rules'))

                    <li>
                        <a href="{{url('admin/shipping-rules')}}" class="waves-effect"><i class="fas fa-truck"></i>
                            <span> Shipping Rules </span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <div id="sidebar-menu">
            <ul>
                @if(Auth::guard('admin_user')->user()->can('reports'))

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="fas fa-chart-line"></i>
                            <span> Reports </span> <span class="menu-arrow"></span></a>
                        <ul class="list-unstyled" style="display: none;">

                            <li>
                                <a href="{{url('/admin/reports/dashboard')}}" class="waves-effect">
                                    <span> Dashboard </span></a></li>
                            <li>
                                <a href="{{url('/admin/products-profit')}}" class="waves-effect">
                                    <span> Products Profit </span></a></li>
                            <li>
                            <!-- <a href="{{url('/admin/products-report')}}" class="waves-effect"> -->
                                <!-- <span> Products </span></a> </li> <li> -->
                                <a href="{{url('/admin/districts-report')}}" class="waves-effect">
                                    <span> Districts </span></a></li>
                            <li>
                                <a href="{{url('/admin/couriers-report')}}" class="waves-effect">
                                    <span> Couriers </span></a></li>

                            <li>
                                <a href="{{url('/admin/stockReports')}}" class="waves-effect">
                                    <span> Stock  </span></a></li>
                            <li>
                                <a href="{{url('/admin/salesReports')}}" class="waves-effect">
                                    <span> Sales  </span></a></li>

                            <li>
                                <a href="{{url('/admin/reports/end-of-day')}}" class="waves-effect">
                                    <span> End of day  </span></a>
                            </li>

                            <li>
                                <a href="{{url('/admin/reports/invoice')}}" class="waves-effect">
                                    <span> Invoice  </span></a>
                            </li>

                            <li>
                                <a href="{{url('/admin/admin-Stock-imports')}}" class="waves-effect">
                                    <span> Stock Imported  </span></a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        <div id="sidebar-menu">
            <ul>
                @if(Auth::guard('admin_user')->user()->can('cmsusers'))
                    <li>
                        <a href="{{url('/admin/cmsusers')}}" class="waves-effect"><i class="fas fa-user-secret"></i>
                            <span> CMS Users </span> </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('roles'))
                    <li>
                        <a href="{{url('/admin/roles')}}" class="waves-effect"><i class="zmdi zmdi-accounts"></i> <span> Roles </span>
                        </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('permissions'))
                    <li>
                        <a href="{{url('/admin/permissions')}}" class="waves-effect"><i class="zmdi zmdi-accounts"></i>
                            <span> Permissions </span> </a>
                    </li>
                @endif
            </ul>
        </div>


        <div id="sidebar-menu">
            <ul>
                @if(Auth::guard('admin_user')->user()->can('payment_methods'))

                    <li>
                        <a href="{{url('admin/payment-methods')}}" class="waves-effect"><i class="fas fa-wallet"></i>
                            <span> Payment Methods </span>
                        </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('activities'))

                    <li>
                        <a href="{{url('admin/activities')}}" class="waves-effect"><i class="far fa-keyboard"></i><span> Activities </span>
                        </a>
                    </li>
                @endif

                @if(Auth::guard('admin_user')->user()->can('settings'))

                    <li>
                        <a href="{{url('/admin/settings')}}" class="waves-effect"><i class="zmdi zmdi-settings"></i>
                            <span> Settings </span> </a>
                    </li>
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>
    </div>
</div>
