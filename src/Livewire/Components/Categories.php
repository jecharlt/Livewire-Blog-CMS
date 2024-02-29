<?php

namespace Jecharlt\LivewireBlogCMS\Livewire\Components;

use Jecharlt\LivewireBlogCMS\Livewire\Pages\AdminDashboard;
use Jecharlt\LivewireBlogCMS\Models\BlogCategory;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

class Categories extends Component
{
    public $categories;
    public $categories_frontend;
    public $category_name;
    public $category_path;
    public $featured_image;
    public $cleared_image;
    public $show;
    public $category_id;
    public function render()
    {
        return view('livewire-blog-cms::livewire.components.categories');
    }

    public function mount() {
        $this->categories = BlogCategory::all();
        $this->categories_frontend = $this->categories->toArray();
    }

    public function categoryRedirect($path) {
        $this->redirect("/{$path}");
    }

    public function saveChanges($category_id) {
        if (!$category_id) {
            $category = new BlogCategory;
        }
        else {
            $category = BlogCategory::where('id', $category_id)->first();
        }

        $validator = Validator::make(
            [
                'name' => $this->category_name,
                'path' => $this->category_path,
                'featured_image' => $this->featured_image,
            ],
            [
                'name' => ['required', 'unique:blog_categories,name,' . $category_id],
                'path' => ['required', 'unique:blog_categories,path,' . $category_id],
            ]
        );

        if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
                $this->send_toast($error, '', 'error');
                return;
            }
        }

        try {
            $save_changes = $category->name ? 'saved changes!' : 'created category!';
            $category->name = $this->category_name;
            $category->path = $this->category_path;

            if (($this->cleared_image && trim($this->featured_image) == "") || (trim($this->featured_image) !== "")) {
                $category->featured_image = $this->featured_image;
            }

            $category->save();
            $this->category_id = $category->id;
            $this->send_toast("Successfully {$save_changes}", "", "success");
            $this->mount();
        }
        catch (Exception $e) {
            $this->send_toast("Error: {$e->getMessage()}", "", "error");
        }
    }

    public function send_toast($message, $title, $type) {
        $this->dispatch('trigger_toast', message: $message, title: $title, type: $type)->to(AdminDashboard::class);
    }

    public function deleteCategory($category_id) {
        $validator = Validator::make(
            ['category_id' => $category_id],
            ['category_id' => ['required', 'exists:blog_categories,id']],
            ['exists' => 'The selected category does not exist.']
        );

        if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
                $this->send_toast($error, '', 'error');
                return;
            }
        }

        try {
            $category = BlogCategory::where('id', $category_id)->first();
            $category_name = $category->name;
            $category->delete();
            $this->send_toast("Successfully deleted: $category_name", "", "success");
            $this->show = false;
            $this->mount();
        }
        catch (Exception $e) {
            $this->send_toast("An error occurred!", "", "error");
        }
    }
}
