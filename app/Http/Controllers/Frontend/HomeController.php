<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use App\Models\News;
use App\Models\Video;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Subscriber;
use App\Models\VisitorLog;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $sliders = Slider::where('status', 'ACTIVE')->orderBy('index', 'asc')->get();
        $categories = Category::where('home_featured', 'ACTIVE')->where('status', 'ACTIVE')->orderBy('index', 'asc')->get();
        $products = Product::where('featured', 'ACTIVE')->where('status', 'ACTIVE')->orderBy('index', 'asc')->get();
        $collections = Collection::where('status', 'ACTIVE')->orderBy('index', 'asc')->get();
        $videos = Video::where('status', 'ACTIVE')->orderBy('index', 'asc')->get();
        $testimonials = Testimonial::where('status', 'ACTIVE')->orderBy('index', 'asc')->get();
        $blogs = Blog::where('status', 'ACTIVE')->orderBy('index', 'asc')->get();

        VisitorLog::updateOrCreate(
            ['ip' => $request->ip()],
            [
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'referrer' => $request->headers->get('referer'),
                'last_visited_at' => now(),
            ]
        )->increment('visit_count');

        return view('Frontend.Pages.home', compact('sliders', 'categories', 'products', 'collections', 'videos', 'testimonials', 'blogs'));
    }

    public function about()
    {
        $testimonials = Testimonial::where('status', 'ACTIVE')->orderBy('index', 'asc')->get();
        return view('Frontend.Pages.about', compact('testimonials'));
    }

    public function article()
    {
        return view('Frontend.Pages.article');
    }

    public function greenhouseArticle()
    {
        return view('Frontend.Pages.greenhouse-article');
    }
    public function products()
    {
        return view('Frontend.Products.products');
    }

    public function contact()
    {
        return view('Frontend.Pages.contact');
    }
    public function faq()
    {
        return view('Frontend.Pages.faq');
    }
    public function shipping()
    {
        return view('Frontend.Pages.shipping');
    }
    public function productDetails()
    {
        return view('Frontend.Products.product-details');
    }

    public function cart()
    {
        return view('Frontend.Pages.cart');
    }

    public function checkout()
    {
        return view('Frontend.Pages.checkout');
    }

    public function myAccount()
    {
        return view('Frontend.Pages.my-account');
    }

    public function orders()
    {
        return view('Frontend.Pages.orders');
    }

    public function profile()
    {
        return view('Frontend.Pages.profile');
    }

    public function privacyPolicy()
    {
        return view('Frontend.Pages.privacy-policy');
    }

    public function termsAndConditions()
    {
        return view('Frontend.Pages.terms-and-conditions');
    }

    public function returnExchange()
    {
        return view('Frontend.Pages.return-exchange');
    }

    public function blogs()
    {
        $blogs = Blog::where('status', 'ACTIVE')->orderBy('index', 'asc')->get();

        return view('Frontend.Pages.blogs', compact('blogs'));
    }

    public function blogDetails(Request $request, $slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();
        return view('Frontend.Pages.blog-details', compact('blog'));
    }

    public function news()
    {
        $news = News::where('status', 'ACTIVE')->orderBy('index', 'asc')->get();

        return view('Frontend.Pages.news', compact('news'));
    }

    public function newsDetails(Request $request, $slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        return view('Frontend.Pages.news-details', compact('news'));
    }

    public function subscribeEnquiry(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'product_request' => 'nullable|string',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Email should be a valid email',
        ]);

        $subscriber = new Subscriber();
        $subscriber->email = $request->email;
        $subscriber->product_details = $request->product_request;

        if ($request->hasFile('product_image')) {
            $path = $request->file('product_image')->store('product_requests', 'public');
            $subscriber->product_image = $path;
        }

        $subscriber->save();

        return response()->json(['status' => 'success', 'message' => 'Your request has been submitted successfully!']);
    }
}
