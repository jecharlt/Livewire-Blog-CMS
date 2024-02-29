<?php

namespace Jecharlt\LivewireBlogCMS\Livewire\Components;

use Jecharlt\LivewireBlogCMS\Livewire\Pages\AdminDashboard;
use Jecharlt\LivewireBlogCMS\Models\BlogArticle;
use Jecharlt\LivewireBlogCMS\Models\BlogArticleType;
use Jecharlt\LivewireBlogCMS\Models\BlogCategory;
use Exception;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Component;

class NewArticle extends Component
{
    public $categories;
    public $article_types;
    public $article;

    public $article_title;
    public $description;
    public $slug;
    public $category;
    public $article_type;
    public $featured_image;
    public $content;
    public $is_published;
    public $originally_published_at;
    public $republished_at;
    public $cleared_image;
    public function render()
    {
        return view('livewire-blog-cms::livewire.components.new-article');
    }

    public function mount() {
        $this->categories = BlogCategory::all();
        $this->article_types = BlogArticleType::all();

        if (session('article_id') !== null) {
            $this->article = BlogArticle::where('id', session('article_id'))->first();
            session()->forget('article_id');
        }
        else {
            $this->article = new BlogArticle;
        }

        $this->article_title = $this->article->title ?? "";
        $this->description = $this->article->description ?? "";
        $this->slug = $this->article->slug ?? "";
        $this->category = $this->article->category_id ?? "";
        $this->article_type = $this->article->article_type_id ?? "";
        $this->featured_image = $this->article->featured_image ?? "";
        $this->content = $this->article->content ?? "";
        $this->is_published = $this->article->is_published ?? false;
        $this->originally_published_at = $this->article->originally_published_at ?? null;
        $this->republished_at = $this->article->republished_at ?? null;
        $this->dispatch('reinit');
    }

    public function saveChanges() {
        $validator = Validator::make(
            [
                'article_title' => $this->article_title,
                'description' => $this->description,
                'slug' => $this->slug,
                'category' => $this->category,
                'article_type' => $this->article_type,
                'featured_image' => $this->featured_image,
                'content' => $this->content,
            ],
            [
                'article_title' => ['required', 'unique:blog_articles,title,' . $this->article->id],
                'description' => ['required'],
                'slug' => ['required', 'unique:blog_articles,slug,' . $this->article->id],
                'category' => ['required'],
                'article_type' => ['required'],
                'content' => ['required'],
            ]
        );

        if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
                $this->send_toast($error, '', 'error');
                return;
            }
        }

        try {
            $save_changes = $this->article->title ? "saved changes!" : "created article!";
            $this->article->title = $this->article_title;
            $this->article->description = $this->description;
            $this->article->slug = $this->slug;
            $this->article->category_id = $this->category;
            $this->article->article_type_id = $this->article_type;
            if (($this->cleared_image && trim($this->featured_image) == "") || (trim($this->featured_image) !== "")) {
                $this->article->featured_image = $this->featured_image;
            }
            $this->article->content = $this->content;
            $this->article->is_published = $this->is_published;
            $this->article->originally_published_at = $this->originally_published_at;
            $this->article->republished_at = $this->republished_at;
            $this->article->save();

            $this->send_toast("Successfully {$save_changes}", "", "success");
        }
        catch (Exception $e) {
            $this->send_toast("Error: {$e->getMessage()}", "", "error");
        }
    }

    public function publish() {
        $this->article->is_published = true;
        if($this->article->originally_published_at == null) {
            $this->article->originally_published_at = now();
        }
        else {
            $this->article->republished_at = now();
        }
        $this->article->save();

        $this->send_toast("Successfully published article!", "", "success");
    }

    public function unpublish() {
        $this->article->is_published = false;
        $this->article->save();

        $this->send_toast("Successfully unpublished article!", "", "success");
    }

    public function deleteArticle() {
        try {
            $article_title = $this->article_title;
            $this->article->delete();
            $this->send_toast("Successfully deleted: $article_title", "", "success");
            $this->mount();
        }
        catch (Exception $e) {
            $this->send_toast("An error occurred!", "", "error");
        }
    }

    public function send_toast($message, $title, $type) {
        $this->dispatch('trigger_toast', message: $message, title: $title, type: $type)->to(AdminDashboard::class);
    }

    #[On('reMount')]
    public function reMount() {
        $this->mount();
    }
}
