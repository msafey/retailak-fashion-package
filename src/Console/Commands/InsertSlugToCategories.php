<?php

namespace App\Console\Commands;

use App\Categories;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Console\Command;

class InsertSlugToCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:addSlug';

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
        $categories = Categories::all();

        foreach ($categories as $category) {
            $category->slug_en = SlugService::createSlug(Categories::class, 'slug_en', $category->name_en);
            $category->slug_ar = SlugService::createSlug(Categories::class, 'slug_ar', $category->name);
            $category->save();
        }
        echo "adding slug to categories done\n";
    }
}
