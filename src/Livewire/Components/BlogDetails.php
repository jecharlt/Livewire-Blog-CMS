<?php

namespace Jecharlt\LivewireBlogCMS\Livewire\Components;

use Jecharlt\LivewireBlogCMS\Livewire\Pages\AdminDashboard;
use Livewire\Component;
use Jecharlt\LivewireBlogCMS\Models\BlogDetails as BlogDetailsDB;

class BlogDetails extends Component
{
    public $blog_details;
    public $blog_title;
    public $blog_description;
    public $cleared_images;
    public $featured_images;
    public function render()
    {
        return view('livewire-blog-cms::livewire.components.blog-details');
    }

    public function mount() {
        $this->blog_details = BlogDetailsDB::first();
        $this->blog_title = $this->blog_details->title ?? "";
        $this->blog_description = $this->blog_details->description ?? "";
        $this->featured_images[0] = $this->blog_details->logo_light ?? "";
        $this->featured_images[1] = $this->blog_details->logo_dark ?? "";
        $this->cleared_images = [
            empty(trim($this->featured_images[0])),
            empty(trim($this->featured_images[1]))
        ];
    }

    public function saveChanges() {
        $this->blog_details->title = $this->blog_title;
        $this->blog_details->description = $this->blog_description;
        if (($this->cleared_images[0] && trim($this->featured_images[0]) == "")
            || trim($this->featured_images[0]) !== "") {
            $this->blog_details->logo_light = $this->featured_images[0];
        }
        if (($this->cleared_images[1] && trim($this->featured_images[1]) == "")
            || trim($this->featured_images[1]) !== "") {
            $this->blog_details->logo_dark = $this->featured_images[1];
        }
        $this->blog_details->save();
        $this->dispatch('update_title')->to(Header::class);
        $this->dispatch('update_blog_logo')->to(AdminNavbar::class);
        $this->send_toast("Successfully saved changes!", "", "success");
    }

    public function send_toast($message, $title, $type) {
        $this->dispatch('trigger_toast', message: $message, title: $title, type: $type)->to(AdminDashboard::class);
    }
}
