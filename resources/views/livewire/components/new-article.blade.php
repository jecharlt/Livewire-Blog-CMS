<div class="container p-4"
     x-data="{
        tempImageFile: '',
        tempImageFilePath: '',
        featured_image: @entangle('featured_image'),
        cleared_image: @entangle('cleared_image'),
        ckeditor: @entangle('content'),
        editorInstance: null,
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
                            alert(`${data.error.message}`);
                            console.log(data.error);
                            reject(data.error);
                        }
                    })
                    .catch(error => {
                        alert(`${error.message}`)
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
            else {
                this.clearImage();
                this.cleared_image = true;
            }
        },
        clearImage() {
            this.tempImageFile = '';
            this.tempImageFilePath = '';
            this.cleared_image = true;
            this.featured_image = '';
            this.$refs.view_image.style.backgroundImage = '';
            this.$refs.input_file.value = '';
        },
        initEditor() {
            if (document.querySelector('.ck-editor__editable')) {
                document.querySelector('.ck-editor__editable').ckeditorInstance.destroy();
            }
            this.classicEditor();
        },
        classicEditor() {
            let csrf = 'csrf-token';
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    removePlugins: ['MediaEmbedToolbar'],
                    simpleUpload: {
                        uploadUrl: '/blog-admin-image-upload',
                        withCredentials: true,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(`meta[name=${csrf}]`).getAttribute('content'),
                        },
                    }
                })
                .then(editor => {
                    this.editorInstance = editor;
                    editor.setData(this.ckeditor);
                    editor.model.document.on('change:data', () => {
                        this.ckeditor = editor.getData();
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        }
    }" x-cloak x-init="initialLoadImage('{{$this->featured_image ?? ''}}'); initEditor();"
    @reinit.window="initEditor();">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="row">
        <div class="col-6">
            @if($this->article->title)
                <h5>Edit Article</h5>
            @else
                <h5>New Article</h5>
            @endif
        </div>
        <div class="col-6 text-end d-flex align-content-end justify-content-end">
            <button class="save-article-button d-flex align-items-center justify-content-center"
                    @click.prevent="uploadImage().then(() => $wire.saveChanges())">
                @include('livewire-blog-cms::svg.floppy_disk_svg', ['class' => 'save-publish-article-svg'])
                @if(!$this->article->title)
                    Create New Article
                @else
                    Save Changes
                @endif
            </button>
            @if(!$this->article->is_published && $this->article->title)
                <button class="publish-article-button d-flex align-items-center justify-content-center ms-3"
                        @click.prevent="$wire.publish()">
                    @include('livewire-blog-cms::svg.publish_document_svg', ['class' => 'save-publish-article-svg'])
                    Publish Article
                </button>
            @endif
            @if($this->article->is_published)
                <button class="unpublish-article-button d-flex align-items-center justify-content-center ms-3"
                        @click.prevent="$wire.unpublish()">
                    @include('livewire-blog-cms::svg.unpublish_document_svg', ['class' => 'unpublish-article-svg'])
                    Unpublish Article
                </button>
            @endif
            @if($this->article->title)
                <button class="delete-article-button d-flex align-items-center justify-content-center ms-3"
                        @click.prevent="$wire.deleteArticle(); clearImage();">
                    @include('livewire-blog-cms::svg.delete_article_svg', ['class' => 'delete-article-svg'])
                    Delete Article
                </button>
            @endif
        </div>
    </div>

    <form>
        <div class="row mt-4">
            <div class="col-12 form-group">
                <label class="admin-dashboard-input-title" for="article-title">Article Title *</label>
                <input type="text" class="admin-dashboard-input" id="article-title" wire:model="article_title"/>
                <span class="admin-dashboard-input-sub-text">The title and value of the title that will appear in
                    the &lt;head&gt; of the article for SEO</span>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 form-group">
                <label class="admin-dashboard-input-title" for="meta-description">
                    Meta Description *
                </label>
                <input type="text" class="admin-dashboard-input" id="meta-description" wire:model="description">
                <span class="admin-dashboard-input-sub-text">The description that will appear in the &lt;head&gt; of
                    the article for SEO
                </span>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-4">
                <label class="admin-dashboard-input-title" for="url-slug">URL Slug *</label>
                <input type="text" class="admin-dashboard-input" id="url-slug" wire:model="slug">
                <span class="admin-dashboard-input-sub-text">The URL slug (ie. /articles/{slug})</span>
            </div>
            <div class="col-4">
                <label class="admin-dashboard-input-title" for="category">Category *</label>
                <select class="admin-dashboard-input" id="category" wire:model="category">
                    <option disabled selected value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <span class="admin-dashboard-input-sub-text">The category for the article</span>
            </div>
            <div class="col-4">
                <label class="admin-dashboard-input-title" for="article-type">
                    Article Type *
                </label>
                <select class="admin-dashboard-input" id="article-type" wire:model="article_type">
                    <option disabled selected value="">Select Article Type</option>
                    @foreach($article_types as $article_type)
                        <option value="{{ $article_type->id }}">{{ $article_type->name }}</option>
                    @endforeach
                </select>
                <span class="admin-dashboard-input-sub-text">The type of the article</span>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 d-flex flex-column align-items-start justify-content-center w-100">
                <label class="admin-dashboard-input-title">Featured Image</label>
                <label for="input-file" class="drop-area w-100" x-ref="drop_area"
                       @dragover.prevent
                       @drop.prevent="displayNewImage($event.dataTransfer.files)"
                       wire:ignore>
                    <input type="file" accept="image/*" id="input-file" hidden x-ref="input_file"
                           @change="displayNewImage($refs.input_file.files)" wire:ignore>
                    <div class="view-image d-flex align-items-center justify-content-center flex-column"
                         x-ref="view_image" wire:ignore>
                        <div x-show="cleared_image" wire:ignore>
                            @include('livewire-blog-cms::svg.upload_svg', ['class' => ''])
                            <p>Drag and drop or click here <br> to upload image</p>
                        </div>
                    </div>
                    <input type="hidden" id="featuredImageContent" x-model="featured_image" wire:ignore/>
                </label>
                <div class="d-flex align-items-start justify-content-between mt-3 w-100">
                    <span class="admin-dashboard-input-sub-text justify-content-start">Uploaded image must be less
                        than 2MB, and, by default, the image uploaded
                        will be
                        saved in
                    original, 800px, 1024px, and 1920px width variants</span>
                    <button class="clear-image-button d-flex align-items-center justify-content-end"
                            @click.prevent="clearImage()">
                        @include('livewire-blog-cms::svg.eraser_svg', ['class' => 'clear-image-svg'])
                        <span>Clear Image</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <label class="admin-dashboard-input-title" for="editor">Post Body *</label>
                <div wire:ignore>
                    <textarea id="editor" x-model="ckeditor" x-ref="editor"></textarea>
                </div>
                <input type="hidden" id="contentInput"/>
                <span class="admin-dashboard-input-sub-text mt-3">If you want to add HTML content to be rendered,
                    click the source button at the top left, and then paste your HTML code</span>
            </div>
        </div>
    </form>
</div>
