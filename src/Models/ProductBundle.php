<?php

namespace App\Models;

use App\ProductBundleItem;
use Illuminate\Database\Eloquent\Model;

class ProductBundle extends Model
{
    protected $fillable=['product_id','name','description'];
    protected $table='product_bundles';


    public function path($product){
        return 'admin/products/'.$product.'/manage_bundle';
    }

    public function productBundleItems(){
         return $this->hasMany(ProductBundleItem::class,'bundle_id');
    }

    public function deattachBundle($bundle_id){
        if($bundle_id){
            $bundles_item = ProductBundleItem::where('bundle_id',$bundle_id)->get();
            foreach($bundles_item as $bundle){
                $bundle->delete();
            }
        }
    }

    public function bundle($item_id,$bundle_id,$qty)
    {
        $attributes = ['item_id' => $item_id,'bundle_id' => $bundle_id,'qty' => $qty];
        if (!$this->productBundleItems()->where($attributes)->exists()) {
            $bundle = $this->productBundleItems()->create($attributes);
            return $bundle;
        }
    }

}
