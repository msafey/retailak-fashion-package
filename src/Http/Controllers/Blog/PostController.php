<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::guard('admin_user')->user()->can('blog')) {
            return view('admin.un-authorized');
        }
        return view('admin.blog.posts.index');
    }

    public function postsList()
    {
        if (!Auth::guard('admin_user')->user()->can('blog')) {
            return view('admin.un-authorized');
        }
        $posts = Post::orderBy('id', 'ASC')->get();
        return DataTables::of($posts)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::guard('admin_user')->user()->can('blog')) {
            return view('admin.un-authorized');
        }
        return view('admin.blog.posts.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|unique:posts, title|min:5|max:255',
            'image' => 'required|string',
            'body' => 'required|string',
            'tags' => 'required|array|min:1',
        ]);

        $post = new Post();
        try {
            $post->title = strtolower($request->input('title'));
            $post->slug = Str::slug($request->input('title') . '-' . Uuid::uuid());
            if ($request->image) {
                $file_name = saveImage($request->image, "posts");
                $post->thumbnailURL = url('/public/admin/imgs/posts/' . $file_name);
            }
            $post->body = strip_tags(
                $request->input('body'),
                '<p><h1><h2><h3><h4><a><ul><li><ol><img><br><b><hr><center><script>'
            );
            $post->save();

            $request_tagsList = $request->input('tags');
            if ($request_tagsList && count($request_tagsList) > 0) {
                $tagArray = [];
                foreach ($request_tagsList as $request_tag) {
                    $tag = Tag::firstOrCreate(['name' => $request_tag]);
                    array_push($tagArray, $tag->id);
                }
            }
            $post->tags()->attach($tagArray);

            return redirect('admin/blog/posts')->with('success', 'Post added Successfully');
        } catch (\Exception $exception) {
            return back()->withErrors('Cant add new post try again');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::guard('admin_user')->user()->can('blog')) {
            return view('admin.un-authorized');
        }

        $post = Post::with(['tags'])->findOrFail($id);


        return view('/admin/blog/posts/edit', compact(
            'post'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validation of postman request
        $request->validate([
            'title' => 'required|string|unique:posts, title|min:5|max:255',
            'image' => 'required|string',
            'body' => 'required|string',
            'tags' => 'required|array|min:1',
        ]);

        try {
            $post = Post::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return back()->withErrors('Post doesnt exist');
        }

        try {
            $post->title = strtolower($request->input('title'));
            $post->slug = Str::slug($request->input('title') . '-' . Uuid::uuid());
            if ($request->image) {
                $file_name = saveImage($request->image, "posts");
                $post->thumbnailURL = url('/public/admin/imgs/posts/' . $file_name);
            }
            $post->body = strip_tags(
                $request->input('body'),
                '<p><h1><h2><h3><h4><a><ul><li><ol><img><br><b><hr><center><script>'
            );
            $post->save();

            $request_tagsList = $request->input('tags');
            if ($request_tagsList && count($request_tagsList) > 0) {
                $tagArray = [];
                foreach ($request_tagsList as $request_tag) {
                    $tag = Tag::firstOrCreate(['name' => $request_tag]);
                    array_push($tagArray, $tag->id);
                }
            }
            $post->tags()->syncWithoutDetaching($tagArray);

            return redirect('admin/blog/posts')->with('success', 'Post updated Successfully');
        } catch (\Exception $exception) {
            return back()->withErrors('Cant update post try again');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            return back()->withErrors('Post not found');
        }

        try {
            if (!Post::destroy($id)) {
                return back()->withErrors('Cant delete this post');;
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            return back()->withErrors('Cant delete this post');
        }

        return redirect('admin/blog/posts')->with('success', 'Post Deleted Successfully');
    }
}
