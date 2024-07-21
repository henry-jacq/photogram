<div class="tab-content p-3" id="v-pills-tabContent">
    <div class="tab-pane fade active show" id="account" role="tabpanel" aria-labelledby="account-tab">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">My Account</h5>
                <hr>
                <h6><i class="fa fa-user me-2" aria-hidden="true"></i>Change Username</h6>
                <p class="card-text"></p>Changing your username can have unintended side effects.<p></p>
                <form>
                    <div class="form-group input-group">
                        <span class="input-group-text">https://photogram.local/</span>
                        <input type="text" class="form-control" id="username" aria-describedby="usernameHelp" placeholder="Your Username" value="henry">
                    </div>
                    <p id="usernameHelp" class="form-text text-muted">After changing your username, your old username becomes available for anyone else to claim.</p>
                    <button class="btn btn-prime" role="button">Save changes</button>
                    <hr>

                    <div class="form-group">
                        <h6 class="d-block"><i class="fa fa-shield-halved me-2" aria-hidden="true"></i>Two Factor Authentication (2FA)</h6>
                        Increase your account's security by enabling two-factor authentication.
                        <div class="row">
                            <div class="col-lg-7">
                                <p class="mt-3">Two-factor authentication adds an additional layer of security to your account by requiring more than just a password to log in.
                                </p>
                            </div>
                            <div class="col-lg-4 my-auto ms-auto">
                                <p class="mb-2">Status: <b class="badge bg-danger rounded-pill">Disabled</b></p>
                                <button class="btn btn-outline-primary" type="button"><i class="fas fa-shield-halved me-2" aria-hidden="true"></i>Enable 2FA now!</button>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <h6><i class="fa fa-trash me-2" aria-hidden="true"></i>Delete account</h6>
                <div class="form-group">
                    <p>Deleting an account has the following effects:</p>
                    <ul>
                        <li>Certain user content will be moved to a system-wide "Ghost User" in order to
                            maintain content for posterity.</li>
                        <li>Your 4 posts will be removed and cannot be restored.</li>
                        <li>Once you delete your account, there is no going back. Please be certain.</li>
                    </ul>
                    <p>Before deleting your account, take a backup of your data <a href="#" class="text-decoration-none">here.</a></p>
                </div>
                <button class="btn btn-danger btn-sm" type="button" onclick="dialog('Delete account?',' Are you sure want to delete your account ?');" id="delete-account-button"><i class="fa fa-trash me-2" aria-hidden="true"></i>Delete Account</button>

            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="emails" role="tabpanel" aria-labelledby="emails-tab">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Manage Email</h5>
                <hr>
                <div class="mb-3">
                    <p>You can control emails linked to your account</p>
                </div>
                <h6>Display email</h6>
                <div class="form-group mb-3">
                    <select class="form-select" aria-label="Default select example">
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                    </select>
                </div>
                <hr>
                <h6>Add email address</h6>
                <ul class="mt-2">
                    <li>henrytest@gmail.com<div class="badge bg-primary rounded-pill">Primary</div>
                    </li>
                </ul>
                <form>
                    <div class="form-group mb-3">
                        <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter your email">
                    </div>
                    <button class="btn btn-prime">Add email address</button>
                </form>
                <hr>
                <h5 class="fw-normal">Linked emails (1)</h5>
                <div class="p-0 bg-body-tertiary border rounded-3">
                    <ul class="mt-3">
                        <li>Your primary email is used for sending notifications </li>
                        <li>Your primary email will be displayed on your public profile.</li>
                        <li>Your primary email is used for avatar detection. You can change it in your <a href="/edit-profile">profile settings</a>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
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
    <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Notifications</h5>
                <hr>
                <form>
                    <div class="form-group mb-3">
                        <label class="d-block mb-0">Email notifications</label>
                        <div class="small text-muted mb-3">Receive alert notifications via email</div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="mailIfUserPosted">
                            <label class="form-check-label" for="mailIfUserPosted">
                                Email when a user is posted
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="mailProductUpdates">
                            <label class="form-check-label" for="mailProductUpdates">
                                Send me the product updates
                            </label>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="d-block mb-2">Notification Email</label>
                        <select class="form-select" aria-label="Default select example">
                            <option selected="">henrytest@gmail.com</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="d-block mb-2">Notifications</label>
                        <ul class="list-group list-group-sm">
                            <li class="list-group-item has-icon">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkComments">
                                    <label class="form-check-label" for="checkComments">
                                        Comments
                                    </label>
                                </div>
                                <label class="form-text" for="checkComments">
                                    Send me an email if someone commented on my post.
                                </label>
                            </li>
                            <li class="list-group-item has-icon">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkFollows">
                                    <label class="form-check-label" for="checkFollows">
                                        Follows
                                    </label>
                                </div>
                                <label class="form-text" for="checkFollows">
                                    Send me an email if someone starts following me.
                                </label>
                            </li>
                            <li class="list-group-item has-icon">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkDeletePost">
                                    <label class="form-check-label" for="checkDeletePost">
                                        Post deletion
                                    </label>
                                </div>
                                <label class="form-text" for="checkDeletePost">
                                    Send me an email, if post was deleted.
                                </label>
                            </li>
                        </ul>
                        <button class="btn btn-prime mt-4" role="button">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="sessions" role="tabpanel" aria-labelledby="sessions-tab">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Active Sessions</h5>
                <hr>
                <form>
                    <div class="form-group">
                        <label class="d-block">Sessions</label>
                        <p class="font-size-sm text-secondary">This is a list of devices that have logged into
                            your account. Revoke any sessions that you do not recognize.</p>
                        <ul class="list-group list-group-sm">
                            <li class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="mb-5 me-3 p-1" data-toggle="tooltip" title="Desktop"><i class="bi bi-display"></i></div>
                                        <div class="float-left my-3">
                                            <div>
                                                <h6 class="mb-1">223.228.184.131</h6>
                                            </div>
                                            <div>
                                                Last accessed on 22 Feb 17:11
                                            </div>
                                            <div>
                                                <strong>Brave</strong>
                                                on
                                                <strong>Windows</strong>
                                            </div>
                                            <div>
                                                <strong>Signed in</strong>
                                                on 22 Feb 17:11
                                            </div>
                                        </div>
                                    </div>
                                    <div class="float-right">
                                        <a class="btn btn-danger btn-sm ms-3"><span class="sr-only">Revoke</span>Revoke</a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="mb-5 me-3 p-1" data-toggle="tooltip" title="Phone"><i class="bi bi-phone"></i></div>
                                        <div class="float-left my-3">
                                            <div>
                                                <h6 class="mb-1">137.212.56.223</h6>
                                            </div>
                                            <div>
                                                Last accessed on 23 Feb 13:32
                                            </div>
                                            <div>
                                                <strong>Chrome</strong>
                                                on
                                                <strong>Android</strong>
                                            </div>
                                            <div>
                                                <strong>Signed in</strong>
                                                on 23 Feb 13:32
                                            </div>
                                        </div>
                                    </div>
                                    <div class="float-right">
                                        <a class="btn btn-danger btn-sm ms-3"><span class="sr-only">Revoke</span>Revoke</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>