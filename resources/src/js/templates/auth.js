// Get the current URL path
var currentPath = window.location.pathname;

// Login user
if (currentPath == "/login") {
    // show/hide password field in login form
    $("#icon-click").on("click", function (data) {
        let icon = $("#icon");

        if (icon.hasClass("bi-eye-slash")) {
            $("#pass").attr("type", "text");
            icon.removeClass("bi-eye-slash");
            icon.addClass("bi-eye");
        } else if (icon.hasClass("bi-eye")) {
            $("#pass").attr("type", "password");
            icon.removeClass("bi-eye");
            icon.addClass("bi-eye-slash");
        }
    });

    // Restrict form submission when hitting enter key on these fields
    $('#user, #pass, #rememberMe').on("keydown", function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            return false;
        }
    });

    $('.user-login-form').on('submit', function (event) {
        event.preventDefault();
        $('.btn-login').attr('disabled', true);
        $('.btn-login').html('Verifying credentials...');
        const formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "/api/auth/login",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.message == 'Activation Required') {
                    $('.btn-login').html('Performing activation...');
                } else if (response.message == 'Authenticated') {
                    $('.btn-login').html('Logging in...');
                }
                setTimeout(function () {
                    location.replace(response.redirect);
                }, 2000);
            },
            error: function (jqXHR, textStatus) {
                if (textStatus == 'error') {
                    if ($('.alert.alert-danger.alert-dismissible.fade.show').length === 0) {
                        var errorMessage = $('<div>').addClass('alert alert-danger alert-dismissible fade show').html('<i class="bi bi-exclamation-circle me-2"></i><b class="fw-semibold">Invalid credentials!</b> Please try again.<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>');
                        $(errorMessage).insertBefore('form');
                    }
                    $('.btn-login').attr('disabled', false);
                    $('.btn-login').html('Login now!');
                }
            }
        });
    });
}

if (currentPath.startsWith('/activate')) {
    $('.btn-verify').on('click', function (event) {
        event.preventDefault();
        $('.btn-verify').attr('disabled', true);
        $('.btn-verify').html('Verifying code...');
        const formData = new FormData();
        formData.append('username', $(this).parent().data('username'));
        formData.append('passcode', $('#passcode').val());

        $.ajax({
            type: "POST",
            url: "/api/auth/activate",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('.btn-verify').html('Activating account...');
                setTimeout(function () {
                    location.replace(response.redirect);
                }, 2000);
            },
            error: function (jqXHR) {
                if (jqXHR.status === 401) {
                    if ($('.alert.alert-danger.alert-dismissible.fade.show').length === 0) {
                        var errorMessage = $('<div>').addClass('alert alert-danger alert-dismissible fade show').html('<i class="bi bi-exclamation-circle me-2"></i><b class="fw-semibold">Invalid passcode!</b> Please check and try again.<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>');
                        $(errorMessage).insertBefore('form');
                    }
                    $('.btn-verify').attr('disabled', false);
                    $('.btn-verify').html('Verify');
                }
            }
        });
    });

    $('.btn-resend-code').on('click', function (event) {
        event.preventDefault();
        var username = $(this).parent().parent().data('username');
        $('.btn-resend-code').attr('disabled', true);
        $('.btn-resend-code').html('Resending code...');
        
        $.ajax({
            type: "POST",
            url: "/api/auth/resend-code",
            data: JSON.stringify({
                'username': username,
                'resend': true
            }),
            contentType: 'application/json',
            success: function (response) {
                if (response.message == 'Code Sent') {
                    if ($('.alert.alert-success.alert-dismissible.fade.show').length === 0) {
                        var successMessage = $('<div>').addClass('alert alert-success alert-dismissible fade show').html('<i class="bi bi-check-circle me-2"></i><b class="fw-semibold">Code Sent!</b> Check your mailbox.<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>');
                        $(successMessage).insertBefore('form');
                    }
                    $('.btn-resend-code').attr('disabled', false);
                    $('.btn-resend-code').html('Resend code');
                }
            },
            error: function (jqXHR, textStatus) {
                if (jqXHR.responseJSON.message == 'Unauthorized') {
                    location.replace(jqXHR.responseJSON.redirect)
                } else if (textStatus == 'error') {
                    if ($('.alert.alert-danger.alert-dismissible.fade.show').length === 0) {
                        var errorMessage = $('<div>').addClass('alert alert-danger alert-dismissible fade show').html('<i class="bi bi-exclamation-circle me-2"></i><b class="fw-semibold">Invalid credentials!</b> Please try again.<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>');
                        $(errorMessage).insertBefore('form');
                    }
                    $('.btn-resend-code').attr('disabled', false);
                    $('.btn-resend-code').html('Resend code');
                }
            }
        });
    });
}

// Register user
if (currentPath == '/register') {
    // Restrict form submission when hitting enter key on these fields
    var usernameInput = $('#username');
    var fullnameInput = $('#fullname');
    var emailInput = $('#email');
    var passwordInput = $('#password');
    var registerBtn = $('.btn-register');

    $('#fullname, #username, #password, #email').on("keydown", function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            return false;
        }
    });

    $(function () {
        usernameInput.on('input', validateField);
        fullnameInput.on('input', validateField);
        emailInput.on('input', validateField);
        passwordInput.on('input', validateField);

        function validateField() {
            var field = $(this);
            var value = field.val().trim();
            var isValid = false;
            var feedbackElement = field.next('.invalid-feedback');

            if (field.attr('id') === 'username') {
                isValid = /^[a-zA-Z][a-zA-Z_]*$/.test(value);
                feedbackElement.text('It should not contain any special characters except letters and underscore.');
            } else if (field.attr('id') === 'fullname') {
                isValid = /^[A-Za-z]+(\s[A-Za-z]+)*$/.test(value);
                feedbackElement.text('It should not contain numbers or special characters.');
            } else if (field.attr('id') === 'email') {
                isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                feedbackElement.text('Please enter a valid email address.');
            } else if (field.attr('id') === 'password') {
                isValid = value.length >= 8;
                feedbackElement.text('Password should contain at least 8 characters.');
            }

            field.toggleClass('is-invalid', !isValid);
            feedbackElement.toggle(!isValid);

            var allFieldsValid = !usernameInput.hasClass('is-invalid') &&
                !fullnameInput.hasClass('is-invalid') &&
                !emailInput.hasClass('is-invalid') &&
                !passwordInput.hasClass('is-invalid');

            registerBtn.prop('disabled', !allFieldsValid);
        }
    });


    $('.user-register-form').on('submit', function (event) {
        event.preventDefault();
        registerBtn.html('Verifying credentials...');
        registerBtn.attr('disabled', true);
        const formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "/api/auth/register",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.result == true) {
                    var successHTML = `
                    <section class="container">
                        <div class="d-flex align-items-center justify-content-center min-vh-100">
                            <div class="py-3 col-sm-10 col-md-10 col-lg-8 col-xl-6">
                                <div class="bg-black bg-opacity-25 rounded-3 p-5 text-center border">
                                    <img src="/assets/brand/photogram-logo.png"
                                        alt="logo" class="img-fluid mx-auto d-block mb-4" width="63" height="63">
                                    <h3 class="display-6">Welcome to Photogram!</h3>
                                    <p class="lead mb-4">Your account has been created.</p>
                                    <a class="text-decoration-none" href="/login">
                                        <button class="btn btn-prime hvr-icon-forward">Continue to login <i class="fa fa-arrow-right hvr-icon" aria-hidden="true"></i></button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>`;
                    $('section.container').html(successHTML);
                } else {
                    console.error('Cannot insert success message');
                }
            },
            error: function (jqXHR, textStatus) {
                if (textStatus == 'error') {
                    if ($('.alert.alert-danger.alert-dismissible.fade.show').length === 0) {
                        var errorMessage = $('<div>').addClass('alert alert-danger alert-dismissible fade show').html('<i class="bi bi-exclamation-circle me-2"></i><b class="fw-semibold">Failed to register!</b> Invalid credentials.<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>');
                        $(errorMessage).insertBefore('form');
                    }
                    registerBtn.attr('disabled', false);
                    registerBtn.html('Register now!');
                }
            }
        });
    })
}

// Forgot-Password
if (currentPath == "/forgot-password") {
    $('.forgot-password-form').on('submit', function (event) {
        event.preventDefault();
        $('.btn-send-link').attr('disabled', true);
        $('.btn-send-link').html('Sending reset link to your email...');
        const formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "/api/auth/reset-password",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('.btn-send-link').html('Send link');
                if (response.status === 'Success') {
                    location.replace('/login');
                }
            },
            error: function (error) {
                $('.btn-send-link').attr('disabled', false);
                $('.btn-send-link').html('Send link');
                if ($('.alert.alert-danger.alert-dismissible.fade.show').length === 0) {
                    var errorMessage = $('<div>').addClass('alert alert-danger alert-dismissible fade show').html(`<i class="bi bi-exclamation-circle me-2"></i><b class="fw-semibold">Mail not sent!</b> Your email, ${formData.get('reset_email')}, does not exist in our database.<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>`);
                    $(errorMessage).insertBefore('form');
                }
            }
        });
    });
}

// Check password in both fields are same
var regex = /^\/forgot-password\/[a-zA-Z0-9]+$/;
if (regex.test(window.location.pathname)) {
    $(function () {
        var passwordInput = $('#newPassword');
        var confirmPasswordInput = $('#confirmNewPassword');
        var changePasswordBtn = $('.btn-change-password');
        var invalidFeedback = $('.invalid-feedback');

        passwordInput.on('input', validatePasswords);
        confirmPasswordInput.on('input', validatePasswords);

        function validatePasswords() {
            var password = passwordInput.val();
            var confirmPassword = confirmPasswordInput.val();
            var passwordsMatch = password === confirmPassword && password.length > 0 && confirmPassword.length > 0;

            passwordInput.toggleClass('is-invalid', !passwordsMatch);
            confirmPasswordInput.toggleClass('is-invalid', !passwordsMatch);
            changePasswordBtn.prop('disabled', !passwordsMatch);
            invalidFeedback.toggle(!passwordsMatch);
        }
    });

    // Change-Password
    $('.change-password-form').on('submit', function (event) {
        event.preventDefault();
        let changePasswordBtn = $('.btn-change-password');
        changePasswordBtn.attr('disabled', true);
        changePasswordBtn.html('Changing your password...');
        const formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "/api/auth/reset-password",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                changePasswordBtn.attr('disabled', false);
                changePasswordBtn.html('Change password');
                if (response.status == 'Success') {
                    location.replace('/login');
                } else {
                    if ($('.alert.alert-danger.alert-dismissible.fade.show').length === 0) {
                        var message = $('<div>').addClass('alert alert-danger alert-dismissible fade show').html(`<i class="bi bi-exclamation-circle me-2"></i>Unable to change the password!<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>`);
                        $(message).insertBefore('form');
                    }
                }
            },
            error: function (error) {
                changePasswordBtn.attr('disabled', false);
                changePasswordBtn.html('Change password');
                if ($('.alert.alert-danger.alert-dismissible.fade.show').length === 0) {
                    var errorMessage = $('<div>').addClass('alert alert-danger alert-dismissible fade show').html(`<i class="bi bi-exclamation-circle me-2"></i>Error occured! cannot change your password!<button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>`);
                    $(errorMessage).insertBefore('form');
                }
            }
        });
    });
}