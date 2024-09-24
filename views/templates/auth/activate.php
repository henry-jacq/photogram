<div class="h-100 d-flex align-items-center justify-content-center row user-select-none min-vh-100">
    <div class="py-3 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
        <form class="activate-account-form" method="post" autocomplete="off">
            <div class="form-control p-4 bg-dark shadow-lg bg-opacity-25">
                <h5 class="fs-4 text-center my-4">Activate Your Account</h5>
                <label for="passcode" class="form-label">Enter the 6-digit code we sent to your email</label>
                <input type="text" id="passcode" name="passcode" class="form-control mb-3 bg-transparent text-light small text-center" required="" maxlength="6" pattern="\d{6}" style="letter-spacing: 0.8em;">
                <p class="small text-muted mb-4">Check your spam folder if you don't see the email in your inbox.</p>
                <div data-username="<?= $username ?>">
                    <button type="button" class="btn btn-verify btn-prime w-100 shadow-sm">Verify</button>
                    <p class="text-center text-muted mt-3 mb-0"><a role="button" class="btn-resend-code">Resend code</a></p>
                </div>
            </div>
        </form>
    </div>
</div>