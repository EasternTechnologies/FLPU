<?php
namespace App\Http\Controllers\Report;

use App\ArticleReports;
use App\Category;
use App\Http\Controllers\Controller;
use App\Subcategory;
use Illuminate\Support\Facades\Storage;
use App\Report;

class DeleteController extends Controller
{
    protected $report = 0;

    public function __construct () {
        $this->middleware('auth');
    }

    public function delete_report ( $slug, Report $report ) {

        $articles = $report->articles()->get();

        foreach ( $articles as $article ) {
            $pics = $article->images()->get();
            foreach ( $pics as $pic ) {
                Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
            }
            $article->images()->delete();

            $article->companies()->detach();
            $article->countries()->detach();
            $article->vvttypes()->detach();
            $article->personalities()->detach();

            $article->delete();
            $article->removeFromIndex();

        }
        $report->delete();

        return redirect()->back()->with('status', 'Отчет удален');
    }

    public function delete_article ( $slug, ArticleReports $article ) {
        $pics = $article->images()->get();
        foreach ( $pics as $pic ) {
            Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
        }
        $article->images()->delete();
        $article->personalities()->detach();
        $article->companies()->detach();
        $article->delete();
        $article->removeFromIndex();

        return redirect()->back()->with('status', 'Материал удален');
    }

    public function delete_subcategory ( $slug, Subcategory $subcategory ) {
        foreach ( $subcategory->article_reports as $article ) {
            $pics = $article->images()->get();
            foreach ( $pics as $pic ) {
                Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
            }
            $article->images()->delete();
            $article->companies()->detach();
            $article->countries()->detach();
            $article->vvttypes()->detach();
            $article->personalities()->detach();
            $article->delete();
            $article->removeFromIndex();
        }
        $subcategory->delete();

        return redirect()->back()->with('status', 'Подраздел удален');
    }

    public function delete_category ($slug,Category $category) {

        $articles = $category->article_reports()->get();
        foreach ( $articles as $article ) {
            $pics = $article->images()->get();
            foreach ( $pics as $pic ) {
                Storage::disk('bsvt')->delete([$pic->image, $pic->thumbnail]);
            }
            $article->images()->delete();
            $article->images()->delete();

            $article->companies()->detach();
            $article->countries()->detach();
            $article->vvttypes()->detach();
            $article->personalities()->detach();

            $article->delete();
            $article->removeFromIndex();
        }
        $category->delete();

        return redirect()->back()->with('status', 'Раздел удален');
    }

}