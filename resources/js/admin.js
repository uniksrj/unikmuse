import $ from 'jquery';
import 'select2';
function showAlert(type, message) {
    $('.alert-dismissible').remove();
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

    var alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="fas ${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

    $('body').append(alertHtml);
    setTimeout(function () {
        $('.alert-dismissible').hide();
    }, 4000);
}

function removeImage(button, imagePath) {
    if (confirm('Are you sure you want to remove this image?')) {
        const imageItem = button.closest('.image-item');
        imageItem.style.transform = 'scale(0.8)';
        imageItem.style.opacity = '0';

        setTimeout(() => {
            imageItem.remove();
            const removedImagesInput = document.getElementById('removedImages');
            const removedImages = removedImagesInput.value ? removedImagesInput.value.split(',') : [];
            removedImages.push(imagePath);
            removedImagesInput.value = removedImages.join(',');
        }, 300);
    }
}

function removeNewImage(button) {
    const imageItem = button.closest('.image-item');
    imageItem.style.transform = 'scale(0.8)';
    imageItem.style.opacity = '0';

    setTimeout(() => {
        imageItem.remove();

        // Check if any new images left
        const newImagesContainer = document.getElementById('newImagesContainer');
        if (newImagesContainer.children.length === 0) {
            document.getElementById('newImagesPreview').style.display = 'none';
            document.getElementById('upload').value = '';
        }
    }, 300);
}

function updateStats(stats) {
    if (stats.total_posts) {
        $('.stats-card:first-child .stats-number').text(stats.total_posts);
    }
    if (stats.drafts) {
        $('.stats-card:nth-child(2) .stats-number').text(stats.drafts);
    }
}

function updateCounters() {
    var titleCount = $('#postTitle').val().length;
    $('#titleCount').text(titleCount);

    var slugCount = $('#postSlug').val().length;
    $('#slugCount').text(slugCount);

    var descCount = $('#description').val().length;
    $('#descCount').text(descCount);

    var metaTitleCount = $('input[name="meta_title"]').val().length;
    $('#metaTitleCount').text(metaTitleCount);

    var metaDescCount = $('textarea[name="meta_description"]').val().length;
    $('#metaDescCount').text(metaDescCount);
}

function initializeSelect2() {
    if ($('#tagsSelect').length) {
        console.log('Initializing tags Select2...');

        $('#tagsSelect').select2({
            placeholder: "Select or add tags",
            allowClear: true,
            tags: true,
            tokenSeparators: [',', ' '],
            dropdownParent: $('body')
        });
    }

    if ($('#categorySelect').length) {
        console.log('Initializing category Select2...');

        $('#categorySelect').select2({
            placeholder: "Select a category",
            allowClear: true,
            dropdownParent: $('body')
        });
    }
}

$(document).ready(function () {
    initializeSelect2();
    console.log("Admin JS is ready!");
    // setTimeout(() => {
    //     $.ajax({
    //         url: deleteUrl,
    //         method: "POST",
    //         data: {
    //             _token: csrfToken
    //         },
    //         success: function (response) {
    //             console.log(response);

    //             if (response.success) {
    //                 $('#alert_message').fadeOut('slow');
    //             }
    //         },
    //         error: function (xhr, status, error) {
    //             alert('An error occurred: ' + error);
    //         }
    //     });
    // }, 2000);

    // Form submission with AJAX
    $('#addPost').on('submit', function (e) {

        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Publishing...');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: '/saveData',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showAlert('success', 'Post published successfully!');

                if (response.reset_form) {
                    form[0].reset();
                    $('#imagePreview').hide();
                    $('#titleCount').text('0');
                    $('#descCount').text('0');
                }
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            },
            error: function (xhr) {
                var errorMessage = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    errorMessage = '';
                    $.each(errors, function (key, value) {
                        errorMessage += value[0] + '\n';
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showAlert('error', errorMessage);
            },
            complete: function () {

                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });



    $('#upload').on('change', function (e) {
        var file = e.target.files[0];
        if (file) {
            // Check file size (5MB limit)
            if (file.size > 5 * 1024 * 1024) {
                showAlert('error', 'File size must be less than 5MB');
                $(this).val('');
                return;
            }

            var validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                showAlert('error', 'Only JPG, PNG, and GIF files are allowed');
                $(this).val('');
                return;
            }
        }
    });

    $('#postTitle').on('input', function () {
        var count = $(this).val().length;
        $('#titleCount').text(count);

        if (count > 200) {
            $(this).val($(this).val().substring(0, 200));
            $('#titleCount').text(200);
            showAlert('error', 'Title cannot exceed 50 characters');
        }
    });

    $('#description').on('input', function () {
        $('#descCount').text($(this).val().length);
    });

    $('button:contains("Save Draft")').on('click', function (e) {
        e.preventDefault();

        var form = $('form[action="/saveData"]');
        var formData = new FormData(form[0]);
        formData.append('draft', true);

        var draftBtn = $(this);
        var originalText = draftBtn.html();
        draftBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
        draftBtn.prop('disabled', true);

        $.ajax({
            url: '/saveDraft',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showAlert('success', 'Draft saved successfully!');
                if (response.drafts) {
                    $('.stats-card:nth-child(2) .stats-number').text(response.drafts);
                }
            },
            error: function (xhr) {
                showAlert('error', 'Failed to save draft');
            },
            complete: function () {
                draftBtn.html(originalText);
                draftBtn.prop('disabled', false);
            }
        });
    });

    $('button:contains("Preview")').on('click', function (e) {
        e.preventDefault();

        var formData = new FormData($('form[action="/saveData"]')[0]);
        formData.append('preview', true);

        $.ajax({
            url: '/preview',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                var previewWindow = window.open('', '_blank');
                previewWindow.document.write(response);
                previewWindow.document.close();
            },
            error: function () {
                showAlert('error', 'Unable to generate preview');
            }
        });
    });

    $('#tagsSelect').select2({
        placeholder: "Select tags...",
        allowClear: true,
        tags: true,
        tokenSeparators: [',', ' ']
    });

    $('#categorySelect').select2({
        placeholder: "Select category..."
    });

    $('#postTitle, #postSlug, #description, input[name="meta_title"], textarea[name="meta_description"]').on('input', updateCounters);

    updateCounters();

    $('#generateSlugBtn').click(function () {
        var title = $('#postTitle').val().trim();
        if (title) {
            var slug = title
                .toLowerCase()
                .replace(/[^\w\s]/gi, '')
                .replace(/\s+/g, '-')
                .substring(0, 100);
            $('#postSlug').val(slug);
            updateCounters();
        } else {
            showAlert('error', 'Please enter a title first');
        }
    });

    $('#postTitle').on('blur', function () {
        if ($(this).val().trim() && !$('#postSlug').val()) {
            $('#generateSlugBtn').click();
        }
    });

    $('#cancelBtn').click(function () {
        if (confirm('Are you sure you want to cancel? All unsaved changes will be lost.')) {
            window.location.href = '/admin/posts';
        }
    });

    $('#postTitle').on('input', function () {
        var title = $(this).val().trim();
        var metaTitle = $('input[name="meta_title"]');

        if (title && !metaTitle.val()) {
            metaTitle.val(title.substring(0, 60));
            updateCounters();
        }
    });

    $('#description').on('input', function () {
        var desc = $(this).val().trim();
        var metaDesc = $('textarea[name="meta_description"]');

        if (desc && !metaDesc.val()) {
            metaDesc.val(desc.substring(0, 160));
            updateCounters();
        }
    });

    $('#upload').on('change', function (e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imagePreview').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
        }
    });

    // edit page button
    console.log("edit js file work");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.delete_data').on('click', function (e) {
        e.preventDefault();
        let uni = $(this).data('id');
        if (confirm('Are you sure you want to delete this record?')) {
            $.ajax({
                url: `/delete_data/${uni}`,
                type: 'DELETE',
                success: function (response) {
                    $('#message').html('<div class="alert alert-success">' + response.success + '</div>');
                    $(`button[data-id="${recordId}"]`).parent().remove();
                },
                error: function (err) {
                    console.error(err);
                    $('#message').html('<div class="alert alert-danger">Something Went Wrong</div>');
                }
            });
        }

    });

    $(document).on('submit', '#editForm', function (e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);

        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: '/update_data/' + form.data('id'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showAlert('success', 'Post Update successfully!');

                if (response.reset_form) {
                    form[0].reset();
                    $('#newImagesContainer').hide();
                    $('#titleCount').text('0');
                    $('#descCount').text('0');
                }
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            },
            error: function (xhr) {
                var errorMessage = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    errorMessage = '';
                    $.each(errors, function (key, value) {
                        errorMessage += value[0] + '\n';
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showAlert('error', errorMessage);
            },
            complete: function () {

                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
});