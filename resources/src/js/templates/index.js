// Init masonry
var grid = document.getElementById('masonry-area');
if (grid) {
    var $grid = $('#masonry-area').masonry({
        itemSelector: '.grid-item',
        columnWidth: '.grid-item',
        percentPosition: true
    });

    $grid.imagesLoaded().progress(function () {
        $grid.masonry('layout');
    });

    // $grid.infiniteScroll({
    //     path: function () {
    //         return '/api/posts/fetch?page=' + this.pageIndex;
    //     },
    //     append: '.grid-item',
    //     outlayer: $grid.data('masonry'),
    //     responseType: function (response) {
    //         if (response.includes('<html') || response.includes('<HTML')) {
    //             return 'text';
    //         } else {
    //             var jsonResponse = JSON.parse(response);
    //             if (jsonResponse.message === 'Not Found') {
    //                 $grid.infiniteScroll('option', {
    //                     loadOnScroll: false,
    //                     status: '.infinite-scroll-status'
    //                 });
    //                 return 'text';
    //             }
    //         }
    //         return 'json';
    //     },
    //     status: '.infinite-scroll-status'
    // });

    $grid.on('load.infiniteScroll', function (event, response) {
        var bodyContent = $(response).find('body').html();
        var $items = $(bodyContent);
        $grid.append($items).masonry('appended', $items);
        $grid.imagesLoaded().progress(function () {
            $grid.masonry('layout');
        });
        $grid.masonry('layout');
    });
}

$('.carousel-control-prev, .carousel-control-next').on('click', function () {
    $grid.masonry('layout');
});

// Disable right-click on Images
$('img').on("contextmenu", function () {
    return false;
});

// Disable Image Dragging
$("img").on("dragstart", function (event) {
    event.preventDefault();
});

// Scroll to top
if ($('#scroll-top-btn').length != 0) {
    var scrollTopBtn = $('#scroll-top-btn');
    $(window).on('scroll', function () {
        var scrollPos = $(this).scrollTop();

        if (scrollPos > 0) {
            scrollTopBtn.removeClass('d-none').fadeIn('slow');
        } else {
            scrollTopBtn.fadeOut(function () {
                $(this).addClass('d-none');
            });
        }
    });
    scrollTopBtn.on('click', function (e) {
        e.preventDefault();
        $('html').animate({ scrollTop: 0 }, 'fast');
    });
}

// Toggle view mode
$("[name=view_mode]").on('click', function() {
    const val = $(this).val();
    
    $.get("/api/users/preferences",
    {
        view: val
    }, function(data, status) {
        if (status === 'success') {
            if (data.message === true) {
                location.reload()
            }
        }
    });
});

// Create Post Modal
$('#postUploadButton').on('click', function () {
    var title = `<i class="bi bi-plus-circle-dotted me-2"></i>Create Post`;
    var body = `
        <div class="container">
            <h5 class="fw-semibold mb-3">Add Caption</h5>
          
            <!-- Checkbox for AI-based captions -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="aiCaptionCheckbox">
                <label class="form-check-label" for="aiCaptionCheckbox">
                    Generate AI-based caption
                </label>
            </div>
            <div id="aiCaptionWarning" class="alert alert-warning d-none mb-3" role="alert">
                <span class="fw-bold">Caution:</span> This beta feature may not provide accurate captions and picks one randomly for multiple images.
            </div>
            <div id="userCaption">
                <textarea class="form-control shadow-none post-caption mb-2" name="post_text" rows="2" placeholder="Enter your caption..."></textarea>
                <p id="total-caption-chars" class="text-end mb-1">0/240</p>
            </div>
            <h5 class="fw-semibold mb-3">Add Photos</h5>
            <form class="dropzone rounded mb-3 dz-clickable" id="dzCreatePost">
                <div class="dz-message py-1">
                    <i class="bi bi-images display-6"></i>
                    <p class="small">Drag and drop photos here or click to upload</p>
                </div>
            </form>
        </div>`;

    var d = new Dialog(title, body);
    d.setButtons([
        {
            'name': "Clear",
            "class": "btn-outline-secondary btn-reset-form"
        },
        {
            'name': "Create post",
            "class": "btn-prime btn-create-post"
        }
    ]);

    var modal = d.clone;
    var md_footer = modal.find('.modal-footer');
    d.show("primary", true);
    var createPostBtn = md_footer.find('.btn-create-post');
    var resetFormBtn = md_footer.find('.btn-reset-form');
    $(createPostBtn).attr('disabled', true);
    $(resetFormBtn).attr('disabled', true);

    // Dropzone - To upload the files
    if (document.querySelector('#dzCreatePost')) {
        Dropzone.autoDiscover = false;

        // Initializing Dropzone
        var myDropzone = new Dropzone("#dzCreatePost", {
            url: "/api/posts/create",
            paramName: "file",
            // Max 20 files can be uploaded at a time for php
            maxFiles: 2,
            // Max 50MB per file for php
            maxFilesize: 50,
            parallelUploads: 2,
            uploadMultiple: true,
            acceptedFiles: ".png,.jpeg,.jpg,.gif",
            autoProcessQueue: false
        });

        // Enable buttons when files are added
        myDropzone.on("addedfile", function () {
            $(createPostBtn).prop('disabled', false);
            $(resetFormBtn).prop('disabled', false);
        });

        // Disable buttons when files are removed
        myDropzone.on("removedfile", function () {
            $(createPostBtn).prop('disabled', true);
            $(resetFormBtn).prop('disabled', true);
            if (myDropzone.files.length > 0) {
                $(createPostBtn).prop('disabled', false);
                $(resetFormBtn).prop('disabled', false)
            }
        });

        // Add post text to dropzone formdata
        myDropzone.on("sending", function (file, xhr, formData) {
            var ai_caption = $('#aiCaptionCheckbox').is(':checked');
            if (ai_caption) {
                formData.append("ai_caption", true);
                formData.append("user_caption", false);
            } else {
                formData.append("ai_caption", false);
                formData.append("user_caption", $('.post-caption').val());
            }
        });

        // Remove rejected files from dropzone
        myDropzone.on("error", function (file) {
            if (file.status === "error") {
                var rejectedFiles = myDropzone.getRejectedFiles();
                for (var i = 0; i < rejectedFiles.length; i++) {
                    myDropzone.removeFile(rejectedFiles[i]);
                }
            }
        });

        // Remove all files before clsoing the modal
        $(modal).on('hide.bs.modal', function () {
            if (myDropzone.files.length > 0) {
                myDropzone.removeAllFiles();
            }
        });

        $('#aiCaptionCheckbox').on('change', function () {
            var textarea = $('#userCaption');
            var warning = $('#aiCaptionWarning');
            if (this.checked) {
                textarea.addClass('d-none');
                warning.removeClass('d-none');
            } else {
                textarea.removeClass('d-none');
                warning.addClass('d-none');
            }
        });

        // Create a new post
        $(createPostBtn).on('click', function (e) {
            e.preventDefault();
            if (myDropzone.files.length > 0) {
                const spinner = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Creating post`;
                $(this).attr('disabled', true);
                $(resetFormBtn).attr('disabled', true);
                $(this).html(spinner);
                myDropzone.processQueue();
            } else {
                $(this).attr('disabled', true);
            }
        });

        // Reset the form data
        $(resetFormBtn).on('click', function () {
            $('.post-caption').val('');

            const length = $('.post-caption').val().length;
            $('#total-caption-chars').text(`${length}/240`);

            if (myDropzone.files.length > 0) {
                myDropzone.removeAllFiles();
            }
        });

        // After queue complete, verify errors then reload the page
        myDropzone.on("queuecomplete", function () {
            var filesWithErrors = myDropzone.getFilesWithStatus(Dropzone.ERROR);
            if (filesWithErrors.length > 0) {
                for (var i = 0; i < filesWithErrors.length; i++) {
                    myDropzone.removeFile(filesWithErrors[i]);
                }
                $(createPostBtn).html('Create post');
                if (!$('#error-message').length) {
                    $('<p id="error-message" class="text-danger mb-1">Cannot create post!</p>').insertBefore('#dzCreatePost');
                }
            } else {
                $(modal).modal('hide');
                setTimeout(function() {
                    location.replace('/home');
                }, Math.random() * 1000);
            }
        });

        // Character limit on post text   
        $('.post-caption').on('input', function () {
            const length = $(this).val().length;

            if (length > 240) {
                const truncatedValue = $(this).val().slice(0, 240);
                $(this).val(truncatedValue);
            }
            $('#total-caption-chars').text(`${$(this).val().length}/${240}`);
        });
    }
});