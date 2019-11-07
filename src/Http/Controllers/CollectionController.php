<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

use App\Models\Brands;
use App\Models\Collection;
use App\Models\CollectionItem;
use App\Http\Transformers\CollectionTransformer;
use App\Models\Products;
use DB;
use Illuminate\Http\Request;
use Response;
use Auth;
use Yajra\Datatables\Datatables;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('frontend_collections')) {
            return view('admin.un-authorized');
        }
        $collections = Collection::get();
        return view('admin.collections.list', compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('frontend_collections')) {
            return view('admin.un-authorized');
        }

        $latesCollection = Collection::orderBy('id', 'desc')->first();
        if ($latesCollection) {
            $SortNo = $latesCollection->sortno + 1;
        } else {
            $SortNo = 1;
        }

        return view('admin.collections.create', compact('SortNo'));
    }

    public function manage($id)
    {
        if (!Auth::guard('admin_user')->user()->can('frontend_collections')) {
            return view('admin.un-authorized');
        }

        $collection = Collection::find($id);

        if ($collection) {
            $collectionItems = CollectionItem::where('collection_id', $collection->id)->orderBy('sortno', 'asc')->get();
            // dd($collectionItems);
            foreach ($collectionItems as $collectionItem) {
                $itemCode = $collectionItem->item_code;
                $product = Products::where('item_code', $itemCode)->isVariant()->first();
                $brand = Brands::find($product->brand_id);
                if ($product) {
                    $collectionItem->name = $product->name_en;
                    $collectionItem->brand = $brand->name_en;
                }

            }
            return view('admin.collections.create', compact('collection', 'collectionItems', 'id'));
        } else {
            return redirect()->back();
        }
    }

    public function itemsList()
    {
        if (!Auth::guard('admin_user')->user()->can('frontend_collections')) {
            return view('admin.un-authorized');
        }

        $products = Products::where('active', 1)->isVariant()->select('id', 'name_en as name', 'item_code', 'brand_id')->get();
        foreach ($products as $product) {
            $brand = Brands::find($product->brand_id);
            if ($brand) {
                $product->brand = $brand->name_en;
            } else {
                $product->brand = "";
            }

        }
        return DataTables::of($products)->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::guard('admin_user')->user()->can('frontend_collections')) {
            return view('admin.un-authorized');
        }

        $colName = $request->input('name');
        $colNameAr = $request->input('name_ar');
        $colSort = $request->input('sortno');

        $collection = new Collection;
        $collection->name = $colName;
        $collection->name_ar = $colNameAr;
        $collection->sortno = $colSort;
        if ($request->has('maincol') && $request->input('maincol') !== "") {
            DB::table('collections')->update(array('main_col' => false));

            $collection->main_col = true;
        }
        $collection->save();
        return Response::json(['colId' => $collection->id, 'colName' => $colName, 'colNameAr' => $colNameAr]);
    }

    public function updateCollection(Request $request, $collectionId)
    {
        if (!Auth::guard('admin_user')->user()->can('frontend_collections')) {
            return view('admin.un-authorized');
        }

        $colName = $request->input('name');
        $colNameAr = $request->input('name_ar');

        $collection = Collection::find($collectionId);
        if ($collection) {
            $collection->name = $colName;
            $collection->name_ar = $colNameAr;

            if ($request->has('maincol') && $request->input('maincol') === "on") {
                DB::table('collections')->update(array('main_col' => false));

                $collection->main_col = true;
            } else {
                $collection->main_col = false;
            }
            $collection->save();
            return Response::json('done', 200);
        } else {
            return Response::json('collection not found', 500);
        }
    }

    public function collectionReorder()
    {
        if (!Auth::guard('admin_user')->user()->can('frontend_collections')) {
            return view('admin.un-authorized');
        }
        $rowIndex = $_REQUEST['rowIndex'];
        $rowId = $_REQUEST['rowID'];
        $collection = Collection::find($rowId);
        $collection->sortno = $rowIndex;
        $collection->save();
        return Response::json("Success", 200);
    }

    public function collectionItemReorder($collectionId)
    {
        if (!Auth::guard('admin_user')->user()->can('frontend_collections')) {
            return view('admin.un-authorized');
        }

        $rowIndex = $_REQUEST['rowIndex'];
        $rowId = $_REQUEST['rowID'];

        $collectionItem = CollectionItem::where('collection_id', $collectionId)->where('item_code', $rowId)->first();
        $collectionItem->sortno = $rowIndex;
        $collectionItem->save();
        return Response::json("Success", 200);
    }

    public function storeCollectionItem(Request $request, $collectionId)
    {
        if (!Auth::guard('admin_user')->user()->can('frontend_collections')) {
            return view('admin.un-authorized');
        }

        $item_code = $request->input('item_code');
        if (!empty($item_code)) {
            $product = Products::where('item_code', $item_code)->first();
            if ($product) {
                $collection_item = new CollectionItem;
                $collection_item->collection_id = $collectionId;
                $collection_item->product_id = $product->id;
                $collection_item->item_code = $product->item_code;
                $collection_item->product_id = $product->id;
                $collection_item->save();
            }
        }
        // $product = Products::where('item_code', $item_code)->first();
        $brand = Brands::find($product->brand_id);
        $productName = $product->name_en;
        $productBrand = $brand->name_en;
        return Response::json(['id' => $collection_item->id, 'productName' => $productName, 'productBrand' => $productBrand]);

    }

    public function deleteCollectionItem($collectionId, $itemId)
    {
        if (!Auth::guard('admin_user')->user()->can('frontend_collections')) {
            return view('admin.un-authorized');
        }

        $colItem = CollectionItem::where('collection_id', $collectionId)->where('item_code', $itemId)->first();
        if ($colItem) {
            $colItem->delete();
        }

        return "Btn" . $itemId;
    }

    public function delete($collectionId)
    {
        if (!Auth::guard('admin_user')->user()->can('frontend_collections')) {
            return view('admin.un-authorized');
        }

        $collection = Collection::find($collectionId);

        if ($collection) {
            CollectionItem::where('collection_id', $collection->id)->delete();
            $collection->delete();
            return Response::json("Success", 200);
        }

    }

    // select district id  from address
    // select warehouse_id from warehouses where district id
    //save el warehouse id in session

    //  warehouse => warehouse_id
    // product_id

    public function getApiCollections()
    {


        $collections = Collection::with('products')->get();
        // dd($collections);

        $collection_transformer = new CollectionTransformer;

        return $collection_transformer->transformCollection($collections->toArray());

    }

    public function getApiCollections2()
    {


        $districtId = null;
        $token = request()->header('Authorization');
        if ($token == null) {
            $token = request()->header('token');
        }

        $headers = getallheaders();
        $lang = app('request')->header('lang');

        if (getFromCache('CollectionsArray')) {

//            return getFromCache('CollectionsArray');

        }
        $collections = Collection::orderBy('sortno', 'asc')->get();

        $collectionsArray = array();
        if ($collections && $collections->count() > 0) {
            $collectionArray = array();

            foreach ($collections as $collection) {
                $collectionArray['items'] = array();
                $collectionArray['id'] = $collection->id;

                if ($lang == 'ar') {
                    if (!is_null($collection->name_ar)) {
                        $collectionArray['name'] = $collection->name_ar;
                    } else {
                        $collectionArray['name'] = $collection->name;
                    }

                } else {
                    $collectionArray['name'] = $collection->name;
                }

                $collectionArray['sortno'] = $collection->sortno;
                if (isset($_GET['type']) && ($_GET['type'] == 'ALL')) {
                    $collectionItems = CollectionItem::where('collection_id', $collection->id)->orderBy('sortno', 'asc')->get();
                } else {
                    $collectionItems = CollectionItem::where('collection_id', $collection->id)->orderBy('sortno', 'asc')->take(10)->get();
                }

                if ($collectionItems && $collectionItems->count() > 0) {
                    foreach ($collectionItems as $key => $collectionItem) {
                        $itemCode = $collectionItem->item_code;

                        $product = Products::where('item_code', $itemCode)
                            ->isVariant()->where('active', 1)
                            ->select('id', 'name_en as name', 'description_en as description', 'item_group', 'item_code', 'standard_rate', 'uom', 'weight',
                                'brand_id', 'stock_qty', 'has_attributes', 'has_variants')->first();

                        if ($product) {
                            $brand = Brands::find($product->brand_id);
                            if ($brand) {
                                $product->brand = $brand->name_en;
                            }
                            if ($token != null) {
                                $product = isFavouriteProduct($product, $token);
                                $product = productCartQty($token, $product);
                            }
                            $product = handleMultiImages($product);

                            $standard_rate = itemSellingPrice($product->id);
                            $product->standard_rate = $standard_rate;

                        } else {
                            $product = "Product ($itemCode) is not available any more";
                        }

                        $product = getProductWithVariations([$product], $lang, 1);
                        if (is_array($product) && !empty($product)) {
                            $collectionArray['items'][] = $product;
                        }

                    }

                } else {
                    $collectionArray['items'] = array();
                }
                $collectionsArray[] = $collectionArray;
            }
            print_r($collectionsArray);
//            return Response::json(putInCache('CollectionsArray', $collectionsArray), 200);

        } else {
            return Response::json([], 200);
        }
    }

    public function getMainCollection()
    {
        $lang = app('request')->header('lang');
        $token = request()->header('Authorization');
        if ($token == null) {
            $token = request()->header('token');
        }

        $collection = Collection::where('main_col', 1)->first();
        $collectionsArray = [];

        if ($collection) {
            $collectionsArray['id'] = $collection->id;
            if ($lang == 'ar') {
                if (!is_null($collection->name_ar)) {
                    $collectionsArray['name'] = $collection->name_ar;
                } else {
                    $collectionsArray['name'] = $collection->name;
                }

            } else {
                $collectionsArray['name'] = $collection->name;
            }

            $collectionsArray['sortno'] = $collection->sortno;

            $collectionItemArray = array();
            $collectionItems = CollectionItem::where('collection_id', $collection->id)->orderBy('sortno', 'asc')->get();
            if ($collectionItems && $collectionItems->count() > 0) {
                foreach ($collectionItems as $collectionItem) {
                    $itemCode = $collectionItem->item_code;
                    if ($lang == 'ar') {
                        $product = Products::where('item_code', $itemCode)->select('id', 'name', 'description', 'item_group', 'min_order_qty', 'item_code', 'max_discount', 'standard_rate', 'uom', 'weight', 'brand_id', 'stock_qty')->first();
                        if ($product) {
                            if ($token != null) {
                                $product = isFavouriteProduct($product, $token);
                                $product = productCartQty($token, $product);
                            }
                            if ($token != null) {
                                $product = isFavouriteProduct($product, $token);
                                $product = productCartQty($token, $product);
                            }
                            $product = handleMultiImages($product);
                            $standard_rate = itemSellingPrice($product->id);
                            $product->standard_rate = $standard_rate;
                            $brand = isset($product->brand_id) ? Brands::find($product->brand_id) : null;
                            $product->brand = $brand->name_en;
                            $product->stock_qty = getApiProductStocks($product);
                        } else {
                            continue;
                        }
                    } else {
                        $product = Products::where('item_code', $itemCode)->select('id', 'name_en as name', 'description_en as description', 'item_group', 'min_order_qty', 'item_code', 'max_discount', 'standard_rate', 'uom', 'weight', 'brand_id', 'stock_qty')->first();
                        if ($product) {
                            if ($token != null) {
                                $product = isFavouriteProduct($product, $token);
                                $product = productCartQty($token, $product);
                            }
                            $brand = Brands::find($product->brand_id);
                            $product->brand = $brand->name_en;
                            $product = handleMultiImages($product);
                            $standard_rate = itemSellingPrice($product->id);
                            $product->standard_rate = $standard_rate;
                            $product->stock_qty = getApiProductStocks($product);
                        } else {
                            continue;
                        }
                    }
                    if ($product && isset($product->stock_qty) && $product->stock_qty > 0) {
                        $collectionsArray['items'][] = $product;
                    }
                }

            } else {
                $collectionsArray['items'] = array();
            }
//            print_r($collectionsArray);
            return Response::json($collectionsArray, 200);

        } else {
            return Response::json([], 200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Collection $collection
     * @return \Illuminate\Http\Response
     */
    public function show(Collection $collection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Collection $collection
     * @return \Illuminate\Http\Response
     */
    public function edit(Collection $collection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Collection $collection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collection $collection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Collection $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collection $collection)
    {
        //
    }
}
