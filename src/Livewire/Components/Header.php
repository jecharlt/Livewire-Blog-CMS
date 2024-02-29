<?php

namespace Jecharlt\LivewireBlogCMS\Livewire\Components;

use Livewire\Attributes\On;
use Livewire\Component;
use Jecharlt\LivewireBlogCMS\Models\BlogDetails as BlogDetailsDB;

class Header extends Component
{
    public $blog_title;
    public $page_description;
    public function render()
    {
        return view('livewire-blog-cms::livewire.components.header');
    }

    public function mount() {
        $this->update_title();
    }

    #[On('update_title')]
    public function update_title() {
        $this->blog_title = BlogDetailsDB::first()->title;
    }

    #[On('update_page_description')]
    public function update_page_description($page_description) {
        $this->page_description = $page_description;
    }
}
