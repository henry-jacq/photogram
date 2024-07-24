<div class="tab-content p-3">
    <div class="tab-pane fade active show" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
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
</div>