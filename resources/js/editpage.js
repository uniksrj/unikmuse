import $ from 'jquery';

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
$(document).ready(function () {
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
})