<div class="container p-4"
    x-data="{
        username_search: '',
        show: @entangle('show'),
        users: @entangle('frontend_users'),
        username: @entangle('username'),
        password: @entangle('password'),
        confirm_password: @entangle('confirm_password'),
        user_id: @entangle('user_id'),
        current_password: @entangle('current_password'),
        editUser(user_id = null) {
            this.username = '';
            this.user_id = null;
            this.password = '';
            this.confirm_password = '';
            this.current_password = '';
            if (user_id != null) {
                let temp = this.users.filter(user => {
                    return user.id === user_id;
                })[0];
                this.user_id = temp.id;
                this.username = temp.username;
            }
            this.show = true;
        },
        dateTimeFormat(date_time) {
            const date = new Date(date_time);
            const options = {
                year: 'numeric', month: '2-digit', day: '2-digit',
                hour: '2-digit', minute: '2-digit', second: '2-digit',
                hour12: false
            };
            return new Intl.DateTimeFormat('en-US', options).format(date).replace(/(\d{2})\/(\d{2})\/(\d{4}),/, '$3-$1-$2')
        },
        get filteredUsers() {
            return this.users.filter(user => {
                return user.username.toLowerCase().includes(this.username_search.toLowerCase());
            })
        }
    }" x-cloak wire:ignore>
    <div class="row">
        <div class="col-6">
            <h5>Blog Users</h5>
        </div>
        <div class="col-6 text-end d-flex align-content-end justify-content-end" x-show.important="!show"
             x-transition>
            <button class="save-article-button d-flex align-items-center justify-content-center"
            @click.prevent="editUser()">
                @include('livewire-blog-cms::svg.create_new_svg', ['class' => 'save-publish-article-svg'])
                New User
            </button>
        </div>
    </div>

    <div x-show="show" class="article-result-container mx-auto mt-4" x-transition>
        <div class="w-100 create-category-container p-3">
            <div class="row d-flex align-items-center">
                <div class="col-6 d-flex align-items-center">
                    <h6 class="m-0">New User</h6>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                    <button class="delete-article-button d-flex align-items-center justify-content-center"
                            @click.prevent="$wire.deleteUser(user_id);"
                            x-show.important="user_id !== null">
                        @include('livewire-blog-cms::svg.delete_user_svg', ['class' => 'delete-user-svg'])
                        <span>Delete User</span>
                    </button>
                    <button class="unpublish-article-button d-flex align-items-center justify-content-center ms-3"
                            @click.prevent="show = false;">
                        @include('livewire-blog-cms::svg.unpublish_document_svg', ['class' => 'unpublish-article-svg'])
                        Close
                    </button>
                </div>
            </div>
            <form>
                <div class="row mt-4">
                    <div class="col-12 form-group">
                        <label class="admin-dashboard-input-title" for="username">Username *</label>
                        <input type="text" class="admin-dashboard-input" id="username" x-model="username"/>
                        <span class="admin-dashboard-input-sub-text">New user username</span>
                    </div>
                </div>
                <template x-if="user_id !== null">
                    <div class="row mt-2">
                        <div class="col-12 form-group">
                            <label class="admin-dashboard-input-title" for="current_password">Current Password *</label>
                            <input type="password" class="admin-dashboard-input" id="current_password"
                                   x-model="current_password">
                            <span class="admin-dashboard-input-sub-text">Current user password</span>
                        </div>
                    </div>
                </template>
                <div class="row mt-2">
                    <div class="col-12 form-group">
                        <label class="admin-dashboard-input-title" for="password"
                               x-text="user_id ? 'New Password' : 'Password *'"></label>
                        <input type="password" class="admin-dashboard-input" id="password" x-model="password"/>
                        <span class="admin-dashboard-input-sub-text">New user password</span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 form-group">
                        <label class="admin-dashboard-input-title" for="confirm-password"
                               x-text="user_id ? 'Confirm New Password' : 'Confirm Password *'"></label>
                        <input type="password" class="admin-dashboard-input" id="confirm-password"
                               x-model="confirm_password"/>
                        <span class="admin-dashboard-input-sub-text">Confirm new user password</span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 d-flex align-items-center justify-content-end w-100">
                        <button class="save-article-button d-flex align-items-center justify-content-center
                        ms-3" @click.prevent="$wire.saveChanges(user_id)">
                            @include('livewire-blog-cms::svg.floppy_disk_svg', ['class' => 'save-publish-article-svg'])
                            <span x-text="(user_id !== null ? 'Edit User' : 'Create User')"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="article-result-container mx-auto">
        <div class="row my-4">
            <div class="col-12">
                <label class="admin-dashboard-input-title">
                    Search
                </label>
                <div class="search-wrapper">
                    @include('livewire-blog-cms::svg.search_svg', ['class' => 'view-search-article-svg'])
                    <input type="text" class="admin-dashboard-search" x-model="username_search"/>
                </div>
                <div class="d-flex align-content-center justify-content-between w-100">
                    <span class="admin-dashboard-input-sub-text">
                        Search by Username
                    </span>
                    <span class="admin-dashboard-input-sub-text"
                          x-text="`${filteredUsers.length} Result${filteredUsers.length > 1 ? 's' : ''}`">
                        {x} Results
                    </span>
                </div>
            </div>
        </div>

        <template x-if="filteredUsers.length > 0">
            <template x-for="user in filteredUsers" :key="user.id">
                <template x-if="user.username.toLowerCase().includes(username_search.toLowerCase())">
                    <div class="article-result d-flex my-3">
                        <div class="col-10 py-3 ps-3 latest-post-text-container">
                            <div class="inline article-result-text">
                                <div x-text="`Username: ${user.username}`"></div>
                                <div class="sub-article-result-text">
                                    <span x-text="`Created ${dateTimeFormat(user.created_at)} | Last Edited:
                                    ${dateTimeFormat(user.updated_at)}`"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="d-flex w-100 h-100">
                                <div class="col-12 w-100 h-100">
                                    <button class="admin-edit-post-button d-flex align-items-center
                                    justify-content-center type-size-down h-100" @click.prevent="editUser(user.id)">
                                        @include('livewire-blog-cms::svg.edit_post_svg', ['class' => 'view-edit-article-svg'])
                                        Edit User
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </template>

        <template x-if="filteredUsers.length === 0">
            <div class="text-center">No users found!</div>
        </template>
    </div>
</div>
