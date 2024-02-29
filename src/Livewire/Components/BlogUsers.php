<?php

namespace Jecharlt\LivewireBlogCMS\Livewire\Components;

use Jecharlt\LivewireBlogCMS\Livewire\Pages\AdminDashboard;
use Jecharlt\LivewireBlogCMS\Models\BlogUser;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class BlogUsers extends Component
{
    public $show;
    public $username;
    public $password;
    public $users;
    public $frontend_users;
    public $confirm_password;
    public $current_password;
    public $user_id;
    public function render()
    {
        return view('livewire-blog-cms::livewire.components.blog-users');
    }

    public function mount() {
        $this->users = BlogUser::all();
        $this->frontend_users = $this->users->toArray();
    }

    public function send_toast($message, $title, $type) {
        $this->dispatch('trigger_toast', message: $message, title: $title, type: $type)->to(AdminDashboard::class);
    }

    public function saveChanges($user_id) {
        if(!$user_id) {
            $user = new BlogUser;
            $validator = Validator::make(
                [
                    'username' => $this->username,
                    'password' => $this->password,
                    'confirm_password' => $this->confirm_password
                ],
                [
                    'username' => ['required', 'unique:blog_users,username,' . $user_id],
                    'password' => ['required', 'min:8'],
                    'confirm_password' => ['required', 'same:password', 'min:8']
                ]
            );
        }

        else {
            $user = BlogUser::where('id', $user_id)->first();
            $validator = Validator::make(
              [
                  'username' => $this->username,
                  'current_password' => $this->current_password,
                  'password' => $this->password,
                  'confirm_password' => $this->confirm_password,
              ],
              [
                  'username' => ['required', 'unique:blog_users,username,' . $user_id],
                  'current_password' => ['required', 'min:8'],
                  'confirm_password' => ['required_with:password', 'same:password', 'min:8']
              ]
            );
        }

        if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
                $this->send_toast($error, '', 'error');
                return;
            }
        }

        if ($user_id && !Hash::check($this->current_password, $user->password)) {
            $this->send_toast("Incorrect current password.", "", "");
        }

        try {
            $save_changes = $user->username ? "saved changes!" : "created new user!";
            $user->username = $this->username;
            if($user_id && $this->confirm_password) {
                $user->password = bcrypt($this->password);
            }
            else if (!$user_id) {
                $user->password = bcrypt($this->password);
            }
            $user->save();
            $this->user_id = $user->id;
            $this->send_toast("Successfully {$save_changes}", "", "success");
            $this->confirm_password = '';
            $this->password = '';
            $this->current_password = '';
            $this->mount();
        }
        catch (Exception $e) {
            $this->send_toast("Error {$e->getMessage()}", "", "error");
        }
    }

    public function deleteUser($user_id) {
        $validator = Validator::make(
          ['user_id' => $user_id],
          ['user_id' => ['required', 'exists:blog_users,id']],
          ['exists' => 'The selected user does not exist']
        );

        if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
                $this->send_toast($error, '', 'error');
                return;
            }
        }

        if (BlogUser::count() <= 1) {
            $this->send_toast("Unable to delete last remaining user.", "", "error");
            return;
        }

        try {
            $user = BlogUser::where("id", $user_id)->first();
            $username = $user->username;
            $user->delete();
            $this->send_toast("Successfully deleted: $username", "", "success");
            $this->show = false;
            $this->confirm_password = '';
            $this->password = '';
            $this->current_password = '';
            $this->mount();
        }
        catch (Exception $e) {
            $this->send_toast("An error occurred!", "", "error");
        }
    }
}
