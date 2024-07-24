<div class="tab-content p-3">
    <div class="tab-pane fade show active" id="emails" role="tabpanel" aria-labelledby="emails-tab">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Manage Email</h5>
                <hr>
                <div class="mb-3">
                    <p>You can control emails linked to your account.</p>
                </div>
                <h6 class="mt-4">Display Email</h6>
                <hr class="mt-0 mb-2">
                <div class="form-group mb-3">
                    <select class="form-select" aria-label="Email visibility select">
                        <option value="public">Public</option>
                        <option value="private" selected>Private</option>
                    </select>
                    <small class="form-text text-muted">Choose email visibility</small>
                </div>
                <h6 class="mt-4">Set Your Primary Email</h6>
                <hr class="mt-0 mb-2">
                <div class="form-group mb-3">
                    <select class="form-select" aria-label="Primary email select">
                        <option value="<?= $user->getEmail() ?>"><?= $user->getEmail() ?></option>
                    </select>
                    <span class="form-text">For Notification Purposes</span><br>
                    <button class="btn btn-sm btn-prime mt-2">Save Changes</button>
                </div>
                <h6 class="mt-4">Email linked to your account:</h6>
                <hr class="mt-0 mb-2">
                <ul class="list-group mt-2">
                    <li class="list-group-item">
                        <i class="bi bi-envelope me-2"></i>
                        <?= $user->getEmail() ?>
                        <span class="badge bg-prime rounded-pill ms-2">Primary</span>
                    </li>
                </ul>
                <h6 class="mt-4">Add Email Address</h6>
                <hr class="mt-0 mb-2">
                <form>
                    <div id="sendEmailCode" class="form-group mb-3">
                        <input type="email" class="form-control" id="email" placeholder="Enter your email">
                        <span class="form-text">Link your email to photogram</span><br>
                        <div class="d-flex align-items-center justify-content-between">
                            <button class="btn btn-sm btn-prime mt-2">Send Verification Code</button>
                            <a id="resendCodeLink" role="button" class="text-decoration-underline small d-none">Resend code</a>
                        </div>
                    </div>
                    <div id="verifyEmailCode" class="form-group mb-3 d-none">
                        <h6>Enter Verification Code:</h6>
                        <input type="text" class="form-control" id="verifyCode" placeholder="Enter the code">
                        <button class="btn btn-sm btn-prime mt-2">Verify & Link Email</button>
                    </div>
                </form>
                <hr>
                <h5 class="fw-normal">Linked Emails (1)</h5>
                <div class="p-3 bg-body-tertiary border rounded-3">
                    <ul class="mb-0">
                        <li>Your primary email is used for sending email notifications.</li>
                        <li>Your primary email can be displayed on your public profile.</li>
                        <li>Your primary email is used to notify and alert the account status.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>