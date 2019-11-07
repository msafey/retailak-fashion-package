<?php

namespace App\Console\Commands;

use App\Products;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Console\Command;

class InsertSlugToProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:addSlug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insert slug to previously created products';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $products = Products::all();

        foreach ($products as $product) {
            $product->slug_en = SlugService::createSlug(Products::class, 'slug_en', $product->name_en);
            $product->slug_ar = SlugService::createSlug(Products::class, 'slug_ar', $product->name);
            $product->save();
        }
        echo "adding slug to products done\n";
    }
}
