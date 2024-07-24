<div class="tab-content p-3">
    <div class="tab-pane fade active show" id="account" role="tabpanel" aria-labelledby="account-tab">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">My Account</h5>
                <hr>
                <h6><i class="fa fa-user me-2" aria-hidden="true"></i>Change Username</h6>
                <p class="card-text"></p>Changing your username can have unintended side effects.<p></p>
                <form>
                    <div class="form-group input-group">
                        <span class="input-group-text">https://photogram.local/profile/</span>
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
</div>