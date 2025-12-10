@vite('resources/css/app.css')
@vite('resources/js/app.js')
@vite('resources/js/editpage.js')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<style>
    /* Reset Bootstrap row conflicts */
    .admin-edit-container {
        display: flex;
        min-height: 100vh;
        background: #f8f9fa;
    }

    .main-content {
        flex: 1;
        margin-left: 250px;
        padding: 20px;
        width: calc(100% - 250px);
        transition: all 0.3s ease;
    }

    /* Override Bootstrap row styles */
    .admin-edit-content {
        display: block;
        margin: 0;
        padding: 0;
    }

    .admin-edit-content > * {
        max-width: none;
        padding-right: 0;
        padding-left: 0;
        margin-top: 0;
    }

    .addPost {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #3498db;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .add_head {
        color: #34495e;
        font-weight: 600;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Form Container */
    .form-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .form-label i {
        margin-right: 8px;
        color: #3498db;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, #3498db, #2980b9);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
    }

    /* Image Upload Section */
    .upload-section {
        border: 2px dashed #bdc3c7;
        border-radius: 8px;
        padding: 1.5rem;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .upload-section:hover {
        border-color: #3498db;
        background: #ecf0f1;
    }

    .upload-icon {
        font-size: 2rem;
        color: #7f8c8d;
        margin-bottom: 1rem;
        text-align: center;
    }

    .current-images {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e9ecef;
    }

    .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1rem;
    }

    .image-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .image-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .image-item img {
        width: 150px;
        height: 150px;
        object-fit: cover;
        display: block;
    }

    .image-remove {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(231, 76, 60, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .image-remove:hover {
        background: #c0392b;
    }

    /* Character Count */
    .character-count {
        font-size: 0.8rem;
        color: #7f8c8d;
        text-align: right;
        margin-top: 0.25rem;
    }

    /* Form Sections */
    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #ecf0f1;
    }

    /* Custom flex layouts */
    .form-row {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        gap: 1rem;
    }

    .form-label-col {
        flex: 0 0 120px;
        padding-top: 0.5rem;
    }

    .form-input-col {
        flex: 1;
        max-width: 600px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 15px;
            width: 100%;
        }

        .form-row {
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label-col {
            flex: none;
            width: 100%;
        }

        .form-input-col {
            flex: none;
            width: 100%;
            max-width: none;
        }

        .image-preview {
            justify-content: center;
        }

        .image-item img {
            width: 120px;
            height: 120px;
        }
    }

    @media (max-width: 480px) {
        .main-content {
            padding: 10px;
        }

        .form-container {
            padding: 1rem;
        }

        .image-item img {
            width: 100px;
            height: 100px;
        }
    }

    /* Loading State */
    .btn-loading {
        position: relative;
        color: transparent;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }
</style>

@include('admin.common.header')

<div class="admin-edit-container">
    @include('admin.common.sidebar')
    
    <div class="main-content">
        <!-- Page Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 class="h2 mb-1 addPost">Edit Post</h1>
                <p class="text-muted mb-0">Update your blog post details and images</p>
            </div>
            <a href="/admin" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>

        <!-- Edit Form -->
        <div class="form-container">
            <h4 class="add_head mb-4">
                <i class="fas fa-edit me-2"></i>Update Post Details
            </h4>
            
            <form data-id="{{ $user->id }}" enctype="multipart/form-data" id="editForm">
                @csrf
                @method('PUT')
                
                <!-- Title Section -->
                <div class="form-section">
                    <div class="form-row">
                        <div class="form-label-col">
                            <label class="form-label">
                                <i class="fas fa-heading"></i>Title
                            </label>
                        </div>
                        <div class="form-input-col">
                            <input class="form-control" name="title" type="text" maxlength="50" 
                                   placeholder="Enter a compelling title for your post..." 
                                   value="{{ $user->title }}" id="postTitle" required>
                            <div class="character-count">
                                <span id="titleCount">{{ strlen($user->title) }}</span>/50 characters
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="form-section">
                    <div class="form-row">
                        <div class="form-label-col">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i>Description
                            </label>
                        </div>
                        <div class="form-input-col">
                            <textarea class="form-control" name="desc" id="description" 
                                      rows="6" placeholder="Write your post content here..." 
                                      required>{{ $user->description }}</textarea>
                            <div class="character-count">
                                <span id="descCount">{{ strlen($user->description) }}</span> characters
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload Section -->
                <div class="form-section">
                    <div class="form-row">
                        <div class="form-label-col">
                            <label class="form-label">
                                <i class="fas fa-image"></i>Images
                            </label>
                        </div>
                        <div class="form-input-col">
                            <div class="upload-section">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <p class="text-center mb-2">Drag & drop new images here or click to browse</p>
                                <p class="text-center small text-muted mb-3">Supports JPG, PNG, GIF - Max 5MB each</p>
                                <div class="text-center">
                                    <input class="form-control form-control-sm d-none" name="file" 
                                           id="upload" accept="image/*" type="file" >
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('upload').click()">
                                        <i class="fas fa-folder-open me-2"></i>Choose Files
                                    </button>
                                </div>
                            </div>

                            <!-- Current Images -->
                            <div class="current-images">
                                <h6 class="mb-3">
                                    <i class="fas fa-images me-2"></i>Current Images
                                    <small class="text-muted">(Click × to remove)</small>
                                </h6>
                                <div class="image-preview" id="currentImages">
                                    @php
                                        $imagepath = json_decode($user->file_path);
                                    @endphp
                                    @if(!empty($imagepath))
                                        @foreach($imagepath as $index => $image)
                                            <div class="image-item">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Current Image {{ $index + 1 }}">
                                                <button type="button" class="image-remove" onclick="removeImage(this, '{{ $image }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="image-item">
                                            <img src="{{ asset('storage/uploads/no_image.jpg') }}" alt="No Image">
                                        </div>
                                    @endif
                                </div>
                                <input type="hidden" name="removed_images" id="removedImages" value="">
                            </div>

                            <!-- New Images Preview -->
                            <div class="current-images" id="newImagesPreview" style="display: none;">
                                <h6 class="mb-3">
                                    <i class="fas fa-plus-circle me-2"></i>New Images to Upload
                                </h6>
                                <div class="image-preview" id="newImagesContainer"></div>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="form-section">
                    <div class="d-flex mb-3">
                        <div style="width: 150px; flex-shrink: 0;">
                            <label class="form-label">
                                <i class="fas fa-cog me-2"></i>Options
                            </label>
                        </div>
                        <div style="flex: 1; max-width: 600px;">
                            <div class="admin-form-row">
                                <div class="admin-form-col">
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-control" name="category">
                                            <option value="">Select Category</option>
                                            <option value="technology" <?php echo $user->category == 'technology' ? 'selected' : "" ?>>Technology</option>
                                            <option value="lifestyle" <?php echo $user->category == 'lifestyle' ? 'selected' : "" ?>>Lifestyle</option>
                                            <option value="travel" <?php echo $user->category == 'travel' ? 'selected' : "" ?>>Travel</option>
                                            <option value="food" <?php echo $user->category == 'food' ? 'selected' : "" ?>>Food</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="admin-form-col">
                                    <div class="mb-3">
                                        <label class="form-label">Tags</label>
                                        <input type="text" class="form-control"
                                            placeholder="Add tags separated by commas" name="tags">
                                    </div>
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="featuredPost" name="featured">
                                <label class="form-check-label" for="featuredPost">
                                    Mark as featured post
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Submit Section -->
                <div class="form-row">
                    <div class="form-label-col"></div>
                    <div class="form-input-col">
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <button type="submit" id="submit_data" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Post
                            </button>
                            <a href="/admin" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('postTitle');
        const descTextarea = document.getElementById('description');
        const titleCount = document.getElementById('titleCount');
        const descCount = document.getElementById('descCount');

        titleInput.addEventListener('input', function() {
            titleCount.textContent = this.value.length;
        });

        descTextarea.addEventListener('input', function() {
            descCount.textContent = this.value.length;
        });

        // File upload preview
        const fileInput = document.getElementById('upload');
        const newImagesPreview = document.getElementById('newImagesPreview');
        const newImagesContainer = document.getElementById('newImagesContainer');

        fileInput.addEventListener('change', function(e) {
            const files = e.target.files;
            newImagesContainer.innerHTML = '';

            if (files.length > 0) {
                newImagesPreview.style.display = 'block';

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const imageItem = document.createElement('div');
                        imageItem.className = 'image-item';
                        imageItem.innerHTML = `
                            <img src="${e.target.result}" alt="New Image ${i + 1}">
                            <button type="button" class="image-remove" onclick="removeNewImage(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        newImagesContainer.appendChild(imageItem);
                    };

                    reader.readAsDataURL(file);
                }
            } else {
                newImagesPreview.style.display = 'none';
            }
        });
        
        const uploadSection = document.querySelector('.upload-section');        
        uploadSection.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.borderColor = '#3498db';
            this.style.background = '#ecf0f1';
        });

        uploadSection.addEventListener('dragleave', function() {
            this.style.borderColor = '#bdc3c7';
            this.style.background = '#f8f9fa';
        });

        uploadSection.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.borderColor = '#bdc3c7';
            this.style.background = '#f8f9fa';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                
                // Trigger change event to show preview
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });
    });    
</script>

@include('admin.common.footer')