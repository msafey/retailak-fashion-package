<?php

namespace App\Console\Commands;

use App\Brands;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Console\Command;

class InsertSlugToBrands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brands:addSlug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $brands = Brands::all();

        foreach ($brands as $brand) {
            $brand->slug_en = SlugService::createSlug(Brands::class, 'slug_en', $brand->name_en);
            $brand->slug_ar = SlugService::createSlug(Brands::class, 'slug_ar', $brand->name);
            $brand->save();
        }
        echo "adding slug to brands done\n";
    }
}
