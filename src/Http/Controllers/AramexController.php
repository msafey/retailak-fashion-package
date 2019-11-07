<?php

namespace App\Http\Controllers;

use App\Models\AramexShipment;
use App\Models\Orders;
use App\Models\User;
use App\Models\Addresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SoapClient;

class AramexController extends Controller
{
    public function openAramexTrackView()
    {
        return view('admin.aramex.aramex_add');
    }

    public function createShipment($id)
    {
        try {
            $order = Orders::findOrFail($id);
            $user = User::where('id', $order->user_id)->first();
            $user_address = Addresses::where('user_id', $order->user_id)->first();
            $items = [];

            if (!isset($order->items)) {
                return redirect('admin/sales-orders')->with('error', 'There is no items.');

            }

            $item_descriptions = '';
            foreach ($order->items as $item) {
                $items[] =
                    [
                        'PackageType' => 'Clothes',
                        'Quantity' => $item->qty,
                        'Weight' => [
                            'Value' => $item->product && isset($item->product->weight) ? $item->product->weight : 0.1,
                            'Unit' => 'Kg',
                        ],
                        'Comments' => $item->product->name_en,
                        'Reference' => ''
                    ];
                $item_descriptions .= $item->qty . " ".$item->product->name_en." ,";
            }

            $user_address_country_code = "EG";
            error_reporting(E_ALL);
            ini_set('display_errors', '1');

            $soapClient = new SoapClient(storage_path('aramex/shipping-services-api-wsdl.wsdl'));

            // Shipper is Retailak Agent
            $params = array(
                'Shipments' => array(
                    'Shipment' => array(
                        'Shipper' => array(
                            'Reference1' => 'Ref No. ' . $id,
                            'Reference2' => '',
                            'AccountNumber' => env('ARAMEX_ACC_NUM'),
                            'PartyAddress' => array(
                                'Line1' => 'El-Haram, Giza',
                                'Line2' => '',
                                'Line3' => '',
                                'City' => 'Giza',
                                'StateOrProvinceCode' => '',
                                'PostCode' => '',
                                'CountryCode' => 'EG'
                            ),
                            'Contact' => array(
                                'Department' => 'Khotwh Team',
                                'PersonName' => 'Khotwh',
                                'Title' => 'Shipping Khotwh Products',
                                'CompanyName' => 'Khotwh',
                                'PhoneNumber1' => '01021900219',
                                'PhoneNumber1Ext' => '',
                                'PhoneNumber2' => '',
                                'PhoneNumber2Ext' => '',
                                'FaxNumber' => '',
                                'CellPhone' => '01021900219',
                                'EmailAddress' => 'info@khotwh.com',
                                'Type' => ''
                            ),
                        ),
                        'Consignee' => array(
                            'Reference1' => $order->payment_method == 'Cash on delivery'
                                ? 'Money Paid: 0EGP'
                                : "Money Paid: {$order->total_price}EGP" ,
                            'Reference2' => $order->payment_method == 'Cash on delivery'
                                ? "Money Collect: {$order->total_price}EGP"
                                : 'Money Collect: 0EGP'
                            ,
                            'AccountNumber' => '',
                            'PartyAddress' => array(
                                'Line1' => $user_address->title,
                                'Line2' => $user_address->street,
                                'Line3' => $user_address->nearest_landmark,
                                'City' => isset($user_address->city) ? $user_address->city : 'cairo',
                                'StateOrProvinceCode' => '',
                                'PostCode' => '',
                                'CountryCode' => $user_address_country_code
                            ),

                            'Contact' => array(
                                'Department' => '',
                                'PersonName' => $user->name,
                                'Title' => $user_address->title,
                                'CompanyName' => 'Khotwh',
                                'PhoneNumber1' => $user->phone,
                                'CellPhone' => $user->phone,
                                'EmailAddress' => $user->email,
                            ),
                        ),
                        'ThirdParty' => array(
                            'Reference1' => '',
                            'Reference2' => '',
                            'AccountNumber' => '',
                            'PartyAddress' => array(
                                'Line1' => '',
                                'Line2' => '',
                                'Line3' => '',
                                'City' => '',
                                'StateOrProvinceCode' => '',
                                'PostCode' => '',
                                'CountryCode' => ''
                            ),
                            'Contact' => array(
                                'Department' => '',
                                'PersonName' => '',
                                'Title' => '',
                                'CompanyName' => '',
                                'PhoneNumber1' => '',
                                'PhoneNumber1Ext' => '',
                                'PhoneNumber2' => '',
                                'PhoneNumber2Ext' => '',
                                'FaxNumber' => '',
                                'CellPhone' => '',
                                'EmailAddress' => '',
                                'Type' => ''
                            ),
                        ),
                        'Reference1' => '',
                        'Reference2' => '',
                        'Reference3' => '',
                        'ForeignHAWB' => '',
                        'TransportType' => 0,
                        'ShippingDateTime' => time(),
                        'DueDate' => time(),
                        'PickupLocation' => '',
                        'PickupGUID' => '',
                        'Comments' => 'Total products ' . count($items),
                        'AccountingInstrcutions' => '',
                        'OperationsInstructions' => '',
                        'Details' => array(
                            'Dimensions' => array(
                                'Length' => 10,
                                'Width' => 10,
                                'Height' => 10,
                                'Unit' => 'cm',

                            ),
                            'ActualWeight' => array(
                                'Value' => 0.1,
                                'Unit' => 'Kg'
                            ),
                            'ProductGroup' => 'EXP',
                            'ProductType' => 'PDX',
                            'PaymentType' => 'P',
                            'PaymentOptions' => '',
                            'Services' => 'CODS',
                            'NumberOfPieces' => 1,
                            'DescriptionOfGoods' => $item_descriptions,
                            'GoodsOriginCountry' => 'EG',
                            'CashOnDeliveryAmount' => array(
                                'Value' => 20,
                                'CurrencyCode' => ''
                            ),
                            'InsuranceAmount' => array(
                                'Value' => 0,
                                'CurrencyCode' => ''
                            ),
                            'CollectAmount' => array(
                                'Value' => 0,
                                'CurrencyCode' => ''
                            ),
                            'CashAdditionalAmount' => array(
                                'Value' => 0,
                                'CurrencyCode' => ''
                            ),
                            'CashAdditionalAmountDescription' => '',
                            'CustomsValueAmount' => array(
                                'Value' => 0,
                                'CurrencyCode' => ''
                            ),
                            'Items' => [
                            ]
                        ),
                    ),
                ),
                'ClientInfo' => array(
                    'AccountCountryCode' => env('ARAMEX_ACC_COUNTRY_CODE'),
                    'AccountEntity' => env('ARAMEX_ACC_ENTITY'),
                    'AccountNumber' => env('ARAMEX_ACC_NUM'),
                    'AccountPin' => env('ARAMEX_ACC_PIN'),
                    'UserName' => env('ARAMEX_ACC_USERNAME'),
                    'Password' => env('ARAMEX_ACC_PASSWORD'),
                    'Version' => env('ARAMEX_VERSION')
                ),
                'Transaction' => array(
                    'Reference1' => '001',
                    'Reference2' => '',
                    'Reference3' => '',
                    'Reference4' => '',
                    'Reference5' => '',
                ),
                'LabelInfo' => array(
                    'ReportID' => 9201,
                    'ReportType' => 'URL',
                ),
            );
            $params['Shipments']['Shipment']['Details']['Items'] = $items;

            try {
                $auth_call = $soapClient->CreateShipments($params);
                if ($auth_call->HasErrors) {
                    return redirect()->back()->withErrors('Could not create shipping , try again later.');
                }

                $aramex_shipment = new AramexShipment;
                $aramex_shipment->order_id = $id;
                $aramex_shipment->shipment_id = $auth_call->Shipments->ProcessedShipment->ID;
                $aramex_shipment->has_error = $auth_call->HasErrors;
                $aramex_shipment->transaction = json_encode($auth_call->Transaction);
                $aramex_shipment->notification = json_encode($auth_call->Notifications);
                $aramex_shipment->label_url = $auth_call->Shipments->ProcessedShipment->ShipmentLabel->LabelURL;
                $aramex_shipment->response = json_encode($auth_call);
                $aramex_shipment->save();

                return redirect('admin/sales-orders')->with('success', 'Create an Aramex shipment Successfully.');

            } catch (\SoapFault $fault) {
                return redirect()->back()->withErrors($fault->faultstring);
            }
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
        }

    }

    public function shipmentTracking($id)
    {
        $soapClient = new SoapClient(storage_path('aramex/shipments-tracking-api-wsdl.wsdl'));

        /*
          parameters needed for the trackShipments method , client info, Transaction, and Shipments' Numbers.
          Note: Shipments array can be more than one shipment.
      */
        $params = array(
            'ClientInfo' => array(
                'AccountCountryCode' => env('ARAMEX_ACC_COUNTRY_CODE'),
                'AccountEntity' => env('ARAMEX_ACC_ENTITY'),
                'AccountNumber' => env('ARAMEX_ACC_NUM'),
                'AccountPin' => env('ARAMEX_ACC_PIN'),
                'UserName' => env('ARAMEX_ACC_USERNAME'),
                'Password' => env('ARAMEX_ACC_PASSWORD'),
                'Version' => env('ARAMEX_VERSION')
            ),

            'Transaction' => array(
                'Reference1' => '001'
            ),
            'Shipments' => array(
                $id
            )
        );
        try {
            $auth_call = $soapClient->TrackShipments($params);

            if ($auth_call->HasErrors || !isset($auth_call->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult->UpdateDescription)) {
                return redirect()->back()->with('error', 'Could not track shipping , try again later.');
            }

            $status = $auth_call->TrackingResults->KeyValueOfstringArrayOfTrackingResultmFAkxlpY->Value->TrackingResult->UpdateDescription;
            AramexShipment::where('shipment_id', $id)->update(['shipment_track' => 1, 'status' => $status]);
            $shipment = AramexShipment::where('shipment_id', $id)->first();

            return view('admin.aramex.track', compact('shipment'));

        } catch (\SoapFault $fault) {
            return redirect()->back()->withErrors($fault->faultstring);
        }
    }
}
