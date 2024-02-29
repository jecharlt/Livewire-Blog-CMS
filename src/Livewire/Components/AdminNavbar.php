<?php

namespace Jecharlt\LivewireBlogCMS\Livewire\Components;

use Jecharlt\LivewireBlogCMS\Livewire\Pages\AdminDashboard;
use Jecharlt\LivewireBlogCMS\Models\BlogDetails as BlogDetailsDB;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminNavbar extends Component
{
    public $blog_logo;
    public function render()
    {
        return view('livewire-blog-cms::livewire.components.admin-navbar');
    }

    public function mount() {
        $this->update_blog_logo();
    }

    public function send_switch_page($destination) {
        $this->dispatch('switch_page', destination: $destination)->to(AdminDashboard::class);
    }

    public function logout() {
        $this->redirect('/blog-admin-logout');
    }

    #[On('update_blog_logo')]
    public function update_blog_logo() {
        $this->blog_logo = BlogDetailsDB::first()->logo_light;
    }
}
