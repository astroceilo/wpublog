<?php

use App\Http\Controllers\PostDashboardController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('home', ['title' => 'Home Page']);
});

Route::get('/posts', function () {
    // $posts = Post::with(['author', 'category'])->latest()->get();
    $posts = Post::latest()->filter(request(['search', 'category', 'author']))->paginate(9)->onEachSide(1)->withQueryString();

    return view('posts', ['title' => 'Blog', 'posts' => $posts]);
});

Route::get('/posts/{post:slug}', function(Post $post){
    // dd($id);
    return view('post', ['title' => 'Single Post', 'post' => $post]);
});

// Route::get('/authors/{user:username}', function (User $user) {
//     // $posts = $user->posts->load('category', 'author');

//     // return view('posts', ['title' => count($posts) . ' Article by. ' . $user->name, 'posts' => $posts]);
//     return view('posts', ['title' => count($user->posts) . ' Article by. ' . $user->name, 'posts' => $user->posts]);
// });

// Route::get('/categories/{category:slug}', function (Category $category) {
//     // $posts = $category->posts->load('category', 'author');

//     // return view('posts', ['title' => 'Category: ' . $category->name, 'posts' => $posts]);
//     return view('posts', ['title' => 'Category: ' . $category->name, 'posts' => $category->posts]);
// });

Route::get('/about', function () {
    return view('about', ['title' => 'About']);
});

Route::get('/contact', function () {
    return view('contact', ['title' => 'Contact Us']);
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth', 'verified')->group(function() {
    Route::get('/dashboard', [PostDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [PostDashboardController::class, 'store']);
    Route::get('/dashboard/create', [PostDashboardController::class, 'create']);
    Route::delete('/dashboard/{post:slug}', [PostDashboardController::class, 'destroy']);
    Route::get('/dashboard/{post:slug}/edit', [PostDashboardController::class, 'edit']);
    Route::patch('/dashboard/{post:slug}', [PostDashboardController::class, 'update']);
    Route::get('/dashboard/{post:slug}', [PostDashboardController::class, 'show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/upload', [ProfileController::class, 'upload']);
});

require __DIR__.'/auth.php';
