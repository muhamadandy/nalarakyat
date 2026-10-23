<?php

namespace App\Http\Controllers;

use App\Models\ArticleNews;
use App\Models\Author;
use App\Models\BannerAdvertisement;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $featured = ArticleNews::with(['category', 'author'])
            ->where('is_featured', 'featured')
            ->latest()
            ->take(3)
            ->get();

        $latest = ArticleNews::with('category')
            ->latest()
            ->take(6)
            ->get();

        $authors = Author::withCount('news')
            ->orderByDesc('news_count')
            ->take(6)
            ->get();

        $categorySections = Category::all()
            ->map(function ($category) {
                $category->setRelation(
                    'news',
                    $category->news()->latest()->take(7)->get()
                );

                return $category;
            })
            ->filter(fn ($category) => $category->news->isNotEmpty())
            ->take(3);

        $banners = BannerAdvertisement::where('is_active', 'active')
            ->get()
            ->groupBy('type');

        return view('front.index', compact(
            'featured',
            'latest',
            'authors',
            'categorySections',
            'banners'
        ));
    }

    public function details(ArticleNews $articleNews)
    {
        $articleNews->load(['category', 'author']);

        $related = ArticleNews::with('category')
            ->where('category_id', $articleNews->category_id)
            ->where('id', '!=', $articleNews->id)
            ->latest()
            ->take(3)
            ->get();

        $moreFromAuthor = ArticleNews::with('category')
            ->where('author_id', $articleNews->author_id)
            ->where('id', '!=', $articleNews->id)
            ->latest()
            ->take(3)
            ->get();

        $banners = BannerAdvertisement::where('is_active', 'active')
            ->get()
            ->groupBy('type');

        $content = $articleNews->content;

        if (!empty($banners['article']) && $banners['article']->isNotEmpty()) {
            $adHtml = view('front.partials.inline-ad', [
                'banner' => $banners['article']->random(),
            ])->render();

            $content = $this->injectAd($content, $adHtml, 2);
        }

        return view('front.details', compact('articleNews', 'related', 'moreFromAuthor', 'banners', 'content'));
    }

    public function category(Category $category)
    {
        $articles = $category->news()
            ->with('category')
            ->latest()
            ->paginate(6);

        $banners = BannerAdvertisement::where('is_active', 'active')
            ->get()
            ->groupBy('type');

        return view('front.category', compact('category', 'articles', 'banners'));
    }

    public function author(Author $author)
    {
        $articles = $author->news()
            ->with('category')
            ->latest()
            ->paginate(6);

        $banners = BannerAdvertisement::where('is_active', 'active')
            ->get()
            ->groupBy('type');

        return view('front.author', compact('author', 'articles', 'banners'));
    }

    public function search(Request $request)
    {
        $keyword = trim((string) $request->input('keyword'));

        $articles = ArticleNews::with(['category', 'author'])
            ->when($keyword !== '', fn ($query) => $query->where('name', 'like', "%{$keyword}%"))
            ->latest()
            ->paginate(1)
            ->withQueryString();

        $banners = BannerAdvertisement::where('is_active', 'active')
            ->get()
            ->groupBy('type');

        return view('front.search', compact('articles', 'keyword', 'banners'));
    }

        public function sitemap()
    {
        $articles = ArticleNews::select('slug', 'updated_at')
            ->latest('updated_at')
            ->get();

        $categories = Category::select('slug', 'updated_at')->get();

        $authors = Author::select('slug', 'updated_at')->get();

        return response()
            ->view('front.sitemap', compact('articles', 'categories', 'authors'))
            ->header('Content-Type', 'application/xml');
    }

    private function injectAd(string $html, string $adHtml, int $afterParagraph = 2): string
    {
        $parts = preg_split('/(<\/p>)/i', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

        $output = '';
        $count = 0;

        foreach ($parts as $part) {
            $output .= $part;

            if (strcasecmp($part, '</p>') === 0) {
                $count++;

                if ($count === $afterParagraph) {
                    $output .= $adHtml;
                }
            }
        }

        return $output;
    }
}
