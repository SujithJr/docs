<?php

use App\PodcastEpisode;
use App\DocumentationPages;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Michelf\MarkdownExtra;

// Algolia Docsearch API Details.
View::share('docsearchApiKey', env('DOCSEARCH_API_KEY'));
View::share('docsearchIndexName', env('DOCSEARCH_INDEX'));

// Home Page.
Route::get('/', function () {
    return view('home', [
        'title' => 'Livewire',
    ]);
});

// Documentation.
Route::redirect('/docs', '/docs/quickstart');
Route::get('/docs/{page}', function ($slug) {
    if (! file_exists($path = resource_path('views/docs/'.$slug.'.blade.php'))) {
        abort(404);
    }

    $pages = new DocumentationPages($slug);
    $content = MarkdownExtra::defaultTransform(
        View::file($path)->render()
    );

    return view('docs', [
        'title' => $pages->title(),
        'slug' => $slug,
        'pages' => $pages,
        'content' => $content,
    ]);
});

// Podcast Index.
Route::get('/podcast', function () {
    return view('podcast', [
        'title' => 'Building Livewire Podcast',
        'social_image' => 'https://laravel-livewire.com/img/podcast-artwork.png',
        'description' => 'Follow along on the Livewire journey. We\'ll talk about where the project came from, where it\'s going, and all the fun problems to solve along the way!',
        'podcasts' => PodcastEpisode::all(),
    ]);
});

// Show Podcast Show.
Route::get('/podcasts/{slug}', function ($slug) {
    $podcast = PodcastEpisode::whereFilename($slug)->first();

    return view('show-podcast', [
        'title' => $podcast->title . ' | Building Livewire Podcast',
        'social_image' => 'https://laravel-livewire.com/img/podcast-artwork.png',
        'description' => 'Follow along on the Livewire journey. We\'ll talk about where the project came from, where it\'s going, and all the fun problems to solve along the way!',
        'podcast' => $podcast,
    ]);
});
