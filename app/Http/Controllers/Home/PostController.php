<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){

        $posts = Post::where('status',1)->latest('id')->paginate(8);

        return view('home.posts.index', compact('posts'));
    }

    public function show(Post $post){

        $similares = Post::where('category_id', $post->category_id)
                    ->where('status',2)
                    ->where('id', '!=', $post->id)
                    ->latest('id')
                    ->take(6)
                    ->get();

        return view('home.posts.show',compact('post', 'similares'));
    }

    public function category(Category $category){

        $posts = Post::where('category_id', $category->id)
                        ->where('status', 2)
                        ->latest('id')
                        ->paginate('6');

        return view('home.categories.index', compact('posts', 'category'));
    }

    public function tag(Tag $tag){

        $posts =  $tag->posts()
                    ->where('status', 2)
                    ->latest('id')
                    ->paginate('6');

        return view('home.tags.index', compact('posts', 'tag'));

    }
}
