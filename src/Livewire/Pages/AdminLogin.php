<?php

namespace Jecharlt\LivewireBlogCMS\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Jecharlt\LivewireBlogCMS\Livewire\Components\Header;
use Exception;
use Jecharlt\LivewireBlogCMS\Models\BlogDetails as BlogDetailsDB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class AdminLogin extends Component
{
    public $username = '';
    public $password = '';
    public $blog_logo;

    #[Layout('livewire-blog-cms::layouts.blog-admin')]
    public function render()
    {
        return view('livewire-blog-cms::livewire.pages.admin-login');
    }

    public function mount() {
        if (Auth::guard('blog')->check()) {
            return $this->redirect('/blog-admin-dashboard');
        }
        $this->dispatch('update_page_description', page_description: 'Blog Admin Login')->to(Header::class);
        $this->blog_logo = BlogDetailsDB::first()->logo_dark;
    }

    public function validationFailed($message) {
        $this->toast($message, '', 'error');
    }

    public function login() {
        $login_rules = [
            'username' => 'required',
            'password' => 'required|min:8'
        ];

        $this->validate($login_rules);

        try {
            if (Auth::guard('blog')->attempt(['username' => trim($this->username), 'password' => trim($this->password)])) {
                session()->regenerate();
                return $this->redirect('/blog-admin-dashboard');
            }
            else {
                $this->toast('Incorrect Username or Password', '', 'error');
            }
        }
        catch (Exception $e) {
            $this->toast('An Error Occurred', '', 'error');
        }
    }
}
