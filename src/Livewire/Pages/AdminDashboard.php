<?php

namespace Jecharlt\LivewireBlogCMS\Livewire\Pages;

use Jecharlt\LivewireBlogCMS\Livewire\Components\Header;
use Jecharlt\LivewireBlogCMS\Livewire\Components\NewArticle;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminDashboard extends Component
{
    public $current_page;

    #[Layout('livewire-blog-cms::layouts.blog-admin')]
    public function render()
    {
        return view('livewire-blog-cms::livewire.pages.admin-dashboard');
    }

    public function mount() {
        if (!Auth::guard('blog')->check()) {
            return $this->redirect('/blog-admin-login');
        }
        $this->current_page = "New Article";
        $this->dispatch('update_page_description', page_description: $this->current_page)->to(Header::class);
    }

    #[On('switch_page')]
    public function switch_page($destination) {
        if ($this->current_page == $destination && $this->current_page == "New Article") {
            $this->dispatch('reMount')->to(NewArticle::class);
        }
        $this->current_page = $destination;
        $this->dispatch('update_page_description', page_description: $this->current_page)->to(Header::class);
    }

    #[On('trigger_toast')]
    public function trigger_toast($message, $title, $type) {
        $this->toast($message, $title, $type);
    }
}
