<div class="discover-section pt-4 mb-5 px-2">
    <div class="d-flex align-items-center flex-column justify-content-center">
        <div class="col-12 col-md-7 col-lg-6 mt-3 mb-5">
            <form class="text-body position-relative ms-auto my-md-0 my-sm-3" role="search">
                <div class="input-group mb-3">
                    <input class="form-control search-input shadow" type="search" name="search" placeholder="Search" aria-label="Search" aria-describedby="search-blogs">
                    <div class="invalid-feedback">User not found!</div>
                    <span class="input-group-text btn-search px-3" id="icon-click" role="button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Search">
                        <i class="bi-search" id="icon"></i>
                    </span>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-10 mt-3 mb-5">
            <div class="d-flex justify-content-between align-items-center">
                <p class="display-6 mt-3 fs-4">Peoples to Connect</p>
                <div class="d-none d-md-inline-block">
                    <a class="link-body-emphasis" role="button"><i class="fa-solid fa-rotate me-2"></i>Refresh List</a>
                </div>
            </div>
            <hr class="m-0 py-2">
            <div class="mb-5">
                <div class="row">
                    <?php for($i=0; $i<3; $i++): ?>
                    <div class="col-md-12 col-lg-4">
                        <div class="card mb-3 shadow">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center p-3 pb-0">
                                    <div class="avatar avatar-story me-2">
                                        <a href="/profile/henry" class="d-block link-dark text-decoration-none" aria-expanded="false">
                                            <img class="user-profile-img border rounded-circle" src="/files/avatars/8205af2d859ea6abf9f0f24306b25236.jpeg" width="55" height="55" loading="lazy"></a>
                                    </div>
                                    <div class="skeleton-header">
                                        <div class="mb-1">
                                            <h6 class="nav-item fw-normal mb-0"> <a href="/profile/henry" class="text-decoration-none link-body-emphasis" style="color: var(--bs-dark-text)">John Wick</a>
                                            </h6>
                                        </div>
                                        <p class="mb-0 fs-6 fw-light">@john_wick</p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between p-3">
                                <button class="btn btn-sm btn-primary flex-fill me-1"><i class="fa-solid fa-plus me-2"></i>Follow</button>
                                <button class="btn btn-sm btn-outline-secondary flex-fill ms-1"><i class="bi bi-chat-left-text-fill me-2"></i>Message</button>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</div>