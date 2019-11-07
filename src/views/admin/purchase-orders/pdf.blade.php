
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pam</title>
    <meta name="generator" content="frappe">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link type="text/css" rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"><style>
    @media screen {
    .print-format-gutter {
        background-color: #ddd;
        padding: 15px 0px;
    }
    .print-format {
        background-color: white;
        box-shadow: 0px 0px 9px rgba(0,0,0,0.5);
        max-width: 8.3in;
        min-height: 11.69in;
        padding: 0.75in;
        margin: auto;
    }

    .print-format.landscape {
        max-width: 11.69in;
        padding: 0.2in;
    }

    .page-break {
        padding: 30px 0px;
        border-bottom: 1px dashed #888;
    }

    .page-break:first-child {
        padding-top: 0px;
    }

    .page-break:last-child {
        border-bottom: 0px;
    }

    /* mozilla hack for images in table */
    body:last-child .print-format td img {
        width: 100% !important;
    }

    @media(max-width: 767px) {
        .print-format {
            padding: 0.2in;
        }
    }
}

@media print {
    .print-format p {
        margin-left: 1px;
        margin-right: 1px;
    }
}

.data-field {
    margin-top: 5px;
    margin-bottom: 5px;
}

.data-field .value {
    word-wrap: break-word;
}

.important .value {
    font-size: 120%;
    font-weight: bold;
}

.important label {
    line-height: 1.8;
    margin: 0px;
}

.table {
    margin: 20px 0px;
}

.square-image {
    width: 100%;
    height: 0;
    padding: 50% 0;
    background-size: contain;
    /*background-size: cover;*/
    background-repeat: no-repeat !important;
    background-position: center center;
    border-radius: 4px;
}

.print-item-image {
    object-fit: contain;
}

.pdf-variables,
.pdf-variable,
.visible-pdf {
    display: none !important;
}

.print-format {
    font-size: 9pt;
    font-family: "Helvetica Neue", Helvetica, Arial, "Open Sans", sans-serif;
    -webkit-print-color-adjust:exact;
}

.page-break {
    page-break-after: always;
}

.print-heading {
    border-bottom: 1px solid #aaa;
    margin-bottom: 10px;
}

.print-heading h2 {
    margin: 0px;
}
.print-heading h4 {
    margin-top: 5px;
}

table.no-border, table.no-border td {
    border: 0px;
}

.print-format label {
    /* wkhtmltopdf breaks label into multiple lines when it is inline-block */
    display: block;
}

.print-format img {
    max-width: 100%;
}

.print-format table td > .primary:first-child {
    font-weight: bold;
}

.print-format td, .print-format th {
    vertical-align: top !important;
    padding: 6px !important;
}

.print-format p {
    margin: 3px 0px 3px;
}

table td div {
    
    /* needed to avoid partial cutting of text between page break in wkhtmltopdf */
    page-break-inside: avoid !important;
    
}

/* hack for webkit specific browser */
@media (-webkit-min-device-pixel-ratio:0) {
    thead, tfoot { display: table-row-group; }
}

[document-status] {
    margin-bottom: 5mm;
}

.signature-img {
    background: #fff;
    border-radius: 3px;
    margin-top: 5px;
    max-height: 150px;
}

.print-heading {
    text-align: right;
    text-transform: uppercase;
    color: #666;
    padding-bottom: 20px;
    margin-bottom: 20px;
    border-bottom: 1px solid #d1d8dd;
}

.print-heading h2 {
    font-size: 24px;
}

.print-format th {
    background-color: #eee !important;
    border-bottom: 0px !important;
}

/* modern format: for-test */
    </style>
<script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/190915DC-15F2-3248-B1DA-6F03967C204F/main.js" charset="UTF-8"></script></head>
<body>
    <div class="print-format-gutter">
        <div class="print-format">
        
<div class="">
    <div  id="header-html" class="hidden-pdf" >
        
    
    <div class="print-heading">
        <h2>Purchase Order<br>
            <small>PO-{{$purchase_order_id}}</small>
        </h2>
    </div>
    
    </div>

    
    <div id="footer-html" class="visible-pdf">
        
        <p class="text-center small page-number visible-pdf">
            Page <span class="page"></span> of <span class="topage"></span>
        </p>
    </div>
       
       <div class="row section-break">
               <div class="col-xs-6 column-break">
               
               </div>
           
               <div class="col-xs-6 column-break">
               
                   <div class="row">
       
           <div class="col-xs-5">
               <h3>Company</h3>
                {{$company_name}}

           </div>
           <div class="col-xs-4 text-right">
           </div>
       
   </div>
               
               </div>
           
       </div>
       
       <div class="row section-break">
               <div class="col-xs-6 column-break">
               
               </div>
           
               <div class="col-xs-6 column-break">
               
                   <div class="row  data-field" data-fieldname="grand_total" data-fieldtype="Currency" style="margin-bottom: 20px;">
               <div class="col-xs-5">

                   
                   <h3>Date</h3>
                   31/7/2018

                   
               </div>
               <div class="col-xs-4
               text-right value">
                   
       
               </div>
           </div>
               
                   <div class="row  data-field" data-fieldname="in_words" data-fieldtype="Data">
               <div class="col-xs-5">

                   
                   <h3>Required By Date</h3>
                   {{$required_by_date}}

                   
               </div>
               <div class="col-xs-4
                text-right value">
                   
       
               </div>
           </div>
               
               </div>
           
       </div>
    
    
    <div class="row section-break">
            <div class="col-xs-12 column-break">
            
                <div data-fieldname="items" data-fieldtype="Table">
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr>
                        <th style="width: 40px" class="table-sr">Sr</th>
                        
                        
                            <th style="width: 150px;" class="" data-fieldname="items" data-fieldtype="Table">
                                Item Name</th>
                        
                        
                        
                        
                            <th style="width: 60px;" class="text-right" data-fieldname="items" data-fieldtype="Table">
                                Quantity</th>
                        
                        
                        @if($query==0)
                            <th style="width: 80px;" class="text-right" data-fieldname="items" data-fieldtype="Table">
                                Rate</th>
                        
                        
                        
                            <th style="width: 80px;" class="text-right" data-fieldname="items" data-fieldtype="Table">
                                Amount</th>
                        @endif
                        
                    </tr>
                </thead>
                <tbody>
                    @if(count($item_details) > 0)
                    @foreach($item_details as $order_item)
                    <tr>
                        <td class="table-sr">1</td>
                        
                        
                            <td class="" data-fieldname="items" data-fieldtype="Table">
                                <div class="value">
        {{$order_item->item_name}}
    </div></td>
                                               
                        
                        
                            <td class="text-right" data-fieldname="items" data-fieldtype="Table">
                                <div class="value">
{{$order_item->qty}}
    </div></td>
                        
                        
   @if($query==0)                     
                            <td class="text-right" data-fieldname="items" data-fieldtype="Table">
                                <div class="value">
{{$order_item->rate}}
    </div></td>
                        
                        
                        
                            <td class="text-right" data-fieldname="items" data-fieldtype="Table">
                                <div class="value">
{{$order_item->total_amount}}
    </div></td>
                        
                    @endif    
                      
                        
                        
                    </tr>
                        @endforeach
                @endif
                </tbody>
            </table>
        </div>
            
            </div>
        
    </div>



@if($query == 0)
    <div class="row section-break">
                <div class="col-xs-6 column-break">
                        <div class="row">
                            <div class="col-xs-5">
                                <label>Total Money Before Taxes and shipping and discounts</label></div>
                            <div class="col-xs-7 text-right">
                                {{$total_on_order}}
                            </div>
                        </div>
                </div>            
    </div>
    
    <div class="row section-break">
        
        <div class="col-xs-6 column-break"> 
            <div class="row">
                <div class="col-xs-5">
                    <label>Discount Rate</label></div>
                <div class="col-xs-7 text-right">
                    @if($discount_type =='persentage')
                       Have {{$discount}} %  Discount On Total with rate                             {{$discount_rate}}
                    @else
                    Have {{$discount}} Pounds  Discount On Total 
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row section-break">
            <div class="col-xs-6 column-break">
            </div>
            <div class="col-xs-6 column-break">
                <div class="row">
                    <div class="col-xs-5">
                            <label>Shipping Rate</label></div>
                    <div class="col-xs-7 text-right">
                            {{$shipping_rate}}
                    </div>
                </div>
            </div>
    </div>
    
    <div class="row section-break">
            <div class="col-xs-6 column-break">
            </div>
        
            <div class="col-xs-6 column-break">
            
                <div class="row  data-field" data-fieldname="grand_total" data-fieldtype="Currency">
                <div class="col-xs-5">

                    
                    <label>Taxs and Charges</label>       
                </div>
                <div class="col-xs-7 text-right value">
                    
                     {{$taxs_rate}}
                </div>
                </div>
                    
                <div class="row  data-field" data-fieldname="in_words" data-fieldtype="Data">
                    <div class="col-xs-5">

                        
                        <label>Grand Total After Taxes And Discounts</label>
                        
                        
                    </div>
                    <div class="col-xs-7
                     text-right value">
                        
                        {{$total_after_discount}}
            
                    </div>
                </div>
                
            </div>
    </div>
    
@endif


</div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // const page_div = document.querySelector('.page-break');

            page_div.style.display = 'flex';
            page_div.style.flexDirection = 'column';

            const footer_html = document.getElementById('footer-html');
            footer_html.classList.add('hidden-pdf');
            footer_html.classList.remove('visible-pdf');
            footer_html.style.order = 1;
            footer_html.style.marginTop = '20px';
        });
    </script>
</body><!-- amamdouh@panarab-media.com --></html>