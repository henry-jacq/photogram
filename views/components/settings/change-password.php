<div class="tab-content p-3">
    <div class="tab-pane fade active show" id="password" role="tabpanel" aria-labelledby="password-tab">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Change Password</h5>
                <hr>
                <div class="mb-3">
                    <p class="mb-2"><b>Password requirements</b></p>
                    <p class="small text-muted mb-2">To create a new password, you have to meet all of the
                        following requirements:</p>
                    <ul class="small text-muted pl-4 mb-0">
                        <li>Minimum 8 characters</li>
                        <li>At least one special character</li>
                        <li>At least one number</li>
                        <li>Can't be the same as a previous password</li>
                    </ul>
                </div>
                <form class="" method="post" action="#">
                    <div class="mb-3">
                        <label for="current-password" class="form-label">Current password</label>
                        <input type="password" class="form-control" id="current-password" aria-describedby="passwordHelp" name="current-password" required="" autocomplete="">
                        <div id="passwordHelp" class="form-text">
                            You must provide your current password in order to change it.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new-password" class="form-label">New password</label>
                        <input type="password" class="form-control" id="new-password" name="new-password" required="" autocomplete="">
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirm password</label>
                        <input type="password" class="form-control" id="confirm-password" name="password-confirm" required="" autocomplete="">
                    </div>
                    <div class="mt-4">
                        <button type="submit" id="save-password" class="btn btn-prime" disabled="">Change
                            password</button>
                        <a class="p-1 btn btn-link text-decoration-none float-end" rel=" nofollow" data-method="put" href="#">
                            <span>I forgot my password</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
