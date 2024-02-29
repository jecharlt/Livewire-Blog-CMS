<div class="container p-4"
     x-data="{
         show: @entangle('show'),
         category_search: '',
         tempImageFile: '',
         tempImageFilePath: '',
         featured_image: @entangle('featured_image'),
         cleared_image: @entangle('cleared_image'),
         category_name: @entangle('category_name'),
         category_path: @entangle('category_path'),
         category_id: @entangle('category_id'),
         categories: @entangle('categories_frontend'),
         get filteredCategories() {
             return this.categories.filter(category => {
                 return category.name.toLowerCase().includes(this.category_search.toLowerCase());
             })
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
         displayNewImage(files) {
            if (files.length === 0) {
                return;
            }
            this.tempImageFile = files[0];

            const reader = new FileReader();
            reader.onload = (e) => {
                this.tempImageFilePath = e.target.result;
                this.$refs.view_image.style.backgroundImage = `url(${this.tempImageFilePath})`
            }

            reader.readAsDataURL(this.tempImageFile)
            this.cleared_image = false;
        },
        uploadImage() {
            let token = 'csrf-token'
            const formData = new FormData();
            formData.append('upload', this.tempImageFile);
            formData.append('type', 'banner');
            return new Promise((resolve, reject) => {
                if (!this.tempImageFile) {
                    resolve();
                    return;
                }
                fetch('/blog-admin-image-upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector(`meta[name=${token}]`).getAttribute('content'),
                    },
                    credentials: 'same-origin',
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.urls) {
                            this.featured_image = data.urls['default']
                            resolve();
                        }
                        else {
                            alert(`An error occurred: ${data.error.message}`);
                            console.log(data.error);
                            reject(data.error);
                        }
                    })
                    .catch(error => {
                        alert(`An error occurred: ${error.message}`)
                        console.log(error);
                        reject(error);
                    })
            })
        },
        initialLoadImage(url) {
            if (url.trim() !== '') {
                this.$refs.view_image.style.backgroundImage = `url(${url})`
                this.cleared_image = false;
            }
        },
        editCategory(category_id = null) {
            this.clearImage();
            this.category_name = '';
            this.category_id = null;
            this.category_path = '';
            if (category_id === null) {
                this.initialLoadImage('', $refs.view_image)
            }
            else {
                let temp = this.categories.filter(category => {
                     return category.id === category_id;
                 })[0];
                this.category_name = temp.name;
                this.category_path = temp.path;
                this.initialLoadImage(temp.featured_image || '', $refs.view_image);
                temp.featured_image ? this.cleared_image = false : this.cleared_image = true;
                this.featured_image = temp.featured_image;
                this.category_id = temp.id;
            }
            this.show = true;
        },
        clearImage() {
            this.tempImageFile = '';
            this.tempImageFilePath = '';
            this.featured_image = '';
            this.cleared_image = true;
            this.$refs.view_image.style.backgroundImage = '';
            this.$refs.input_file.value = '';
        }
     }" x-cloak wire:ignore x-init="cleared_image = $refs.view_image.style.backgroundImage === 'none'">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <div class="row">
        <div class="col-6">
            <h5>Categories</h5>
        </div>
        <div class="col-6 text-end d-flex align-content-end justify-content-end" x-show.important="!show"
             x-transition>
            <button class="save-article-button d-flex align-items-center justify-content-center"
                    @click.prevent="editCategory()">
                @include('livewire-blog-cms::svg.create_new_svg', ['class' => 'save-publish-article-svg'])
                New Category
            </button>
        </div>
    </div>

    <div x-show="show" class="article-result-container mx-auto mt-4" x-transition>
        <div class="w-100 create-category-container p-3">
            <div class="row d-flex align-items-center">
                <div class="col-6 d-flex align-items-center">
                    <h6 class="m-0">New Category</h6>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                    <button class="delete-article-button d-flex align-items-center justify-content-center"
                            @click.prevent="$wire.deleteCategory(category_id); clearImage();"
                            x-show.important="category_id !== null">
                        @include('livewire-blog-cms::svg.delete_category_svg', ['class' => 'delete-category-svg'])
                        <span>Delete Category</span>
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
                        <label class="admin-dashboard-input-title" for="category-title">Category Name *</label>
                        <input type="text" class="admin-dashboard-input" id="category-title" x-model="category_name"/>
                        <span class="admin-dashboard-input-sub-text">The name of the category that will appear
                            next to articles in the category</span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 form-group">
                        <label class="admin-dashboard-input-title" for="category-path">Category Path *</label>
                        <input type="text" class="admin-dashboard-input" id="category-path" x-model="category_path"/>
                        <span class="admin-dashboard-input-sub-text">The URL path for the category home page (ie.
                            /{path})
                        </span>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 d-flex flex-column align-items-start justify-content-center w-100">
                        <label class="admin-dashboard-input-title">Featured Image</label>
                        <label for="input-file" class="drop-area w-100" x-ref="drop_area"
                               @dragover.prevent
                               @drop.prevent="displayNewImage($event.dataTransfer.files)"
                        >
                            <input type="file" accept="image/*" id="input-file" hidden x-ref="input_file"
                                   @change="displayNewImage($refs.input_file.files)">
                            <div class="view-image d-flex align-items-center justify-content-center flex-column"
                                 x-ref="view_image">
                                <div x-show="cleared_image">
                                    @include('livewire-blog-cms::svg.upload_svg', ['class' => ''])
                                    <p>Drag and drop or click here <br> to upload image</p>
                                </div>
                            </div>
                            <input type="hidden" id="featuredImageContent" x-model="featured_image"/>
                        </label>
                        <span class="admin-dashboard-input-sub-text mt-3">Uploaded image must be less
                        than 2MB, and, by default, the image uploaded will be saved in
                    original, 800px, 1024px, and 1920px width variants</span>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 d-flex align-items-center justify-content-end w-100">
                        <button class="clear-image-button d-flex align-items-center justify-content-center"
                                @click.prevent="clearImage()">
                            @include('livewire-blog-cms::svg.eraser_svg', ['class' => 'clear-image-svg'])
                            <span>Clear Image</span>
                        </button>
                        <button class="save-article-button d-flex align-items-center justify-content-center ms-3"
                                @click.prevent="
                                uploadImage().then(() => {$wire.saveChanges(category_id)})">
                            @include('livewire-blog-cms::svg.floppy_disk_svg', ['class' => 'save-publish-article-svg'])
                            <span x-text="(category_id !== null ? 'Edit Category' : 'Create Category')"></span>
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
                    <input type="text" class="admin-dashboard-search" x-model="category_search"/>
                </div>
                <div class="d-flex align-content-center justify-content-between w-100">
                    <span class="admin-dashboard-input-sub-text">
                        Search by Category Name
                    </span>
                    <span class="admin-dashboard-input-sub-text"
                    x-text="`${filteredCategories.length} Result${filteredCategories.length > 1 ? 's' : ''}`">
                        {x} Results
                    </span>
                </div>
            </div>
        </div>

        <template x-if="filteredCategories.length > 0">
            <template x-for="category in filteredCategories" :key="category.id">
                <template x-if="category.name.toLowerCase().includes(category_search.toLowerCase())">
                    <div class="article-result d-flex my-3">
                        <div class="col-2">
                            <img :src="category.featured_image || 'https://placehold.co/300x100'" class="img-fluid latest-img">
                        </div>
                        <div class="col-8 py-3 latest-post-text-container">
                            <div class="ps-3">
                                <div class="inline article-result-text">
                                    <div x-text="`Name: ${category.name}`"></div>
                                    <div x-text="`Path: /${category.path}`"></div>
                                </div>
                                <div class="sub-article-result-text">
                                <span x-text="`Created: ${dateTimeFormat(category.created_at)} | Last Edited:
                                ${dateTimeFormat(category.updated_at)}`"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="d-flex flex-column w-100 h-100">
                                <div class="col-6 w-100 h-50">
                                    <button class="admin-view-post-button d-flex align-items-center
                                    justify-content-center category-size-down" @click.prevent="$wire.categoryRedirect
                                    (category.path)">
                                        @include('livewire-blog-cms::svg.view_post_svg', ['class' => 'view-edit-article-svg'])
                                        View Category
                                    </button>
                                </div>
                                <div class="col-6 w-100 h-50">
                                    <button class="admin-edit-post-button d-flex align-items-center
                                    justify-content-center category-size-down"
                                            @click.prevent="editCategory(category.id)">
                                        @include('livewire-blog-cms::svg.edit_post_svg', ['class' => 'view-edit-article-svg'])
                                        Edit Category
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </template>

        <template x-if="filteredCategories.length === 0">
            <div class="text-center">No categories found!</div>
        </template>
    </div>
</div>
