<div class="container p-4"
    x-data="{
        tempImageFiles: ['', ''],
        tempImageFilePaths: ['', ''],
        cleared_images: @entangle('cleared_images'),
        featured_images: @entangle('featured_images'),
        blog_title: @entangle('blog_title'),
        blog_description: @entangle('blog_description'),
        displayNewImage(files, number) {
            if (files.length === 0) {
                return;
            }

            this.tempImageFiles[number] = files[0];
            let viewImageRef = `view_image${number}`;

            const reader = new FileReader();
            reader.onload = (e) => {
                this.tempImageFilePaths[number] = e.target.result;
                this.$refs[viewImageRef].style.backgroundImage = `url(${this.tempImageFilePaths[number]})`;
            }

            reader.readAsDataURL(this.tempImageFiles[number])
            this.cleared_images[number] = false;
        },
        async uploadImage() {
            let token = 'csrf-token';
            for (let i = 0; i < this.tempImageFiles.length; i++) {
                if (!this.tempImageFiles[i]) {
                    continue;
                }
                const formData = new FormData();
                formData.append('upload', this.tempImageFiles[i]);
                formData.append('type', 'logo');
                try {
                    let response = await fetch('/blog-admin-image-upload', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(`meta[name=${token}]`).getAttribute('content'),
                        },
                        credentials: 'same-origin',
                        body: formData,
                    });

                    let data = await response.json();

                    if (data.urls) {
                        this.featured_images[i] = data.urls['default'];
                    }
                    else {
                        alert(`An error occured: ${data.error.message}`);
                        console.log(data.error);
                    }
                } catch (error) {
                    alert(`An error occured: ${error.message}`);
                    console.log(error);
                }
            }
        },
        clearImage(number) {
          this.tempImageFiles[number] = '';
          this.tempImageFilePaths[number] = '';
          this.cleared_images[number] = true;
          this.featured_images[number] = '';
          let viewImageRef = `view_image${number}`;
          let inputFileRef = `input_file${number}`;
          if (this.$refs[viewImageRef]) {
            this.$refs[viewImageRef].style.backgroundImage = '';
          }
          if (this.$refs[inputFileRef]) {
            this.$refs[inputFileRef].value = '';
          }
        },
        initialLoadImage(url, number) {
            if (url.trim() !== '') {
                let viewImageRef = `view_image${number}`;
                this.$refs[viewImageRef].style.backgroundImage = `url(${url})`
                this.cleared_images[number] = false;
            }
        },
    }" x-init="
    initialLoadImage(featured_images[0] ?? '', 0);
    initialLoadImage(featured_images[1] ?? '', 1);" x-cloak wire:ignore >
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <div class="row">
        <div class="col-6">
            <h5>Blog Details</h5>
        </div>
        <div class="col-6 text-end d-flex align-content-end justify-content-end">
            <button class="save-article-button d-flex align-items-center justify-content-center"
            @click.prevent="uploadImage().then(() => $wire.saveChanges())">
                @include('livewire-blog-cms::svg.floppy_disk_svg', ['class' => 'save-publish-article-svg'])
                Save Changes
            </button>
        </div>
    </div>

    <form>
        <div class="row mt-4">
            <div class="col-12 form-group">
                <label class="admin-dashboard-input-title" for="blog-title">Blog Title</label>
                <input type="text" class="admin-dashboard-input" id="blog-title" x-model="blog_title"/>
                <span class="admin-dashboard-input-sub-text">The title of the blog and value of the title that
                    will appear in the &lt;head&gt; of every page for SEO</span>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 form-group">
                <label class="admin-dashboard-input-title" for="blog-description">
                    Blog Description
                </label>
                <input type="text" class="admin-dashboard-input" id="blog-description" x-model="blog_description">
                <span class="admin-dashboard-input-sub-text">The description of the blog and value of the
                    description which will be used in the &lt;head&gt; for SEO</span>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6 pe-3 d-flex flex-column align-items-center justify-content-center w-50">
                <label class="admin-dashboard-input-title">Blog Logo (Light)</label>
                <label for="input-file0" class="drop-area-small w-100" x-ref="drop_area0"
                       @dragover.prevent
                       @drop.prevent="displayNewImage($event.dataTransfer.files, 0)"
                >
                    <input type="file" accept="image/*" id="input-file0" hidden x-ref="input_file0"
                           @change="displayNewImage($refs.input_file0.files, 0)">
                    <div class="view-image-small d-flex align-items-center justify-content-center flex-column"
                         x-ref="view_image0">
                        <div x-show="cleared_images && cleared_images[0]">
                            @include('livewire-blog-cms::svg.upload_svg', ['class' => ''])
                            <p class="m-0">Drag and drop or click here <br> to upload image</p>
                        </div>
                    </div>
                    <input type="hidden" x-model="featured_images[0]"/>
                </label>
                <div class="d-flex align-items-start justify-content-between mt-3 w-100">
                    <span class="admin-dashboard-input-sub-text justify-content-start">
                        Uploaded image must be less
                        than 2MB, and, by default, the image uploaded will be saved in
                    original, 250px, 350px, and 400px width variants
                    </span>
                    <button class="blog-details-clear-image-button d-flex align-items-center justify-content-center
                    ms-2" @click.prevent="clearImage(0)">
                        @include('livewire-blog-cms::svg.eraser_svg', ['class' => 'clear-image-svg'])
                        <span>Clear Image</span>
                    </button>
                </div>
            </div>
            <div class="col-6 pe-3 d-flex flex-column align-items-center justify-content-center w-50">
                <label class="admin-dashboard-input-title">Blog Logo (Dark)</label>
                <label for="input-file1" class="drop-area-small w-100" x-ref="drop_area1"
                       @dragover.prevent
                       @drop.prevent="displayNewImage($event.dataTransfer.files, 1)"
                >
                    <input type="file" accept="image/*" id="input-file1" hidden x-ref="input_file1"
                           @change="displayNewImage($refs.input_file1.files, 1)">
                    <div class="view-image-small d-flex align-items-center justify-content-center flex-column"
                         x-ref="view_image1">
                        <div x-show="cleared_images && cleared_images[1]">
                            @include('livewire-blog-cms::svg.upload_svg', ['class' => 'clear-image-svg'])
                            <p class="m-0">Drag and drop or click here <br> to upload image</p>
                        </div>
                    </div>
                    <input type="hidden" x-model="featured_images[1]"/>
                </label>
                <div class="d-flex align-items-start justify-content-between mt-3 w-100">
                    <span class="admin-dashboard-input-sub-text justify-content-start">
                        Uploaded image must be less
                        than 2MB, and, by default, the image uploaded will be saved in
                    original, 250px, 350px, and 400px width variants
                    </span>
                    <button class="blog-details-clear-image-button d-flex align-items-center justify-content-center
                    ms-2" @click.prevent="clearImage(1)">
                        @include('livewire-blog-cms::svg.eraser_svg', ['class' => 'clear-image-svg'])
                        <span>Clear Image</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
