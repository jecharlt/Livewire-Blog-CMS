<?php

namespace Jecharlt\LivewireBlogCMS\Livewire\Components;

use Jecharlt\LivewireBlogCMS\Livewire\Pages\AdminDashboard;
use Jecharlt\LivewireBlogCMS\Models\BlogArticle;
use Jecharlt\LivewireBlogCMS\Models\BlogArticleType;
use Jecharlt\LivewireBlogCMS\Models\BlogCategory;
use Livewire\Component;

class AllArticles extends Component
{
    public $articles;
    public $categories;
    public $article_types;
    public $articles_frontend;
    public function render()
    {
        return view('livewire-blog-cms::livewire.components.all-articles');
    }

    public function mount() {
        $this->articles = BlogArticle::with(['category', 'articleType'])->latest('updated_at')->get();
        $this->articles_frontend = $this->articles->toArray();
        $this->categories = BlogCategory::all();
        $this->article_types = BlogArticleType::all();
    }

    public function editArticle($article_id) {
        session()->push('article_id', $article_id);
        $this->dispatch('switch_page', destination: "New Article")->to(AdminDashboard::class);
    }

    public function articleRedirect($slug) {
        $this->redirect("articles/{$slug}");
    }
}
