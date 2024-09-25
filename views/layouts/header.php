<nav class="navbar navbar-expand-md shadow" aria-label="Photogram Navbar">
    <div class="container gap-2 px-4">
        <!-- Logo -->
        <a class="navbar-brand fw-semi-bold text-prime fs-3 me-auto d-flex align-items-center" href="/home">
            <img src="/assets/brand/photogram-logo.png" width="35" class="me-2">
            <span class="d-none d-md-inline">Photogram</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#photogramNavbar" aria-controls="photogramNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links and Menu -->
        <div class="collapse navbar-collapse" id="photogramNavbar">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Home Icon -->
                <li class="nav-item pe-4">
                    <a class="nav-link" href="/home">
                        <i class="bi bi-house-door<?php if ($title == 'Home') echo '-fill'; ?> fs-4"></i>
                        <span class="d-md-none ms-2">Home</span>
                    </a>
                </li>

                <!-- Discover Icon -->
                <li class="nav-item pe-4">
                    <a class="nav-link" href="/discover">
                        <i class="bi bi-compass<?php if ($title == 'Discover') echo '-fill'; ?> fs-4"></i>
                        <span class="d-md-none ms-2">Discover</span>
                    </a>
                </li>

                <!-- Create Post Button -->
                <li class="nav-item pe-4">
                    <a class="nav-link" id="postUploadButton" role="button">
                        <i class="bi bi-plus-square fs-4"></i>
                        <span class="d-md-none ms-2">Create Post</span>
                    </a>
                </li>

                <!-- Activity Icon -->
                <li class="nav-item pe-4">
                    <a class="nav-link" role="button" onclick="dialog('Not Implemented!',' This feature is not implemented');">
                        <i class="bi bi-heart fs-4"></i>
                        <span class="d-md-none ms-2">My Activities</span>
                    </a>
                </li>

                <!-- Notifications Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" aria-expanded="false">
                        <i class="bi bi-bell fs-4"></i>
                        <span class="badge bg-danger rounded-circle" style="transform: translate(-70%, -90%);">3</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 notification-dropdown" aria-labelledby="notificationDropdown">
                        <li class="px-2 py-1">
                            <strong class="text-muted">Notifications</strong>
                        </li>
                        <li>
                            <hr class="dropdown-divider my-1">
                        </li>
                        <li class="notification-item">
                            <a class="dropdown-item d-flex align-items-start p-2" href="#">
                                <div class="icon bg-body-secondary rounded-circle me-2">
                                    <i class="bi bi-chat-text fs-5 text-primary"></i>
                                </div>
                                <div class="ms-2 fs-6">
                                    <strong>New comment</strong> on your post
                                    <br><small class="text-muted">5 min ago</small>
                                </div>
                            </a>
                        </li>
                        <li class="notification-item">
                            <a class="dropdown-item d-flex align-items-start p-2" href="#">
                                <div class="icon bg-body-secondary rounded-circle me-2">
                                    <i class="bi bi-heart text-danger fs-5"></i>
                                </div>
                                <div class="ms-2 fs-6">
                                    <strong>User123</strong> liked your photo
                                    <br><small class="text-muted">10 min ago</small>
                                </div>
                            </a>
                        </li>
                        <li class="notification-item">
                            <a class="dropdown-item d-flex align-items-start p-2" href="#">
                                <div class="icon bg-body-secondary rounded-circle me-2">
                                    <i class="bi bi-person-plus fs-5"></i>
                                </div>
                                <div class="ms-2">
                                    <strong>User456</strong> sent you a follow request
                                    <br><small class="text-muted">30 min ago</small>
                                </div>
                            </a>
                        </li>
                        <li class="text-center mt-2"><a href="#" class="text-primary">View all notifications</a></li>
                    </ul>
                </li>

                <!-- User Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <img class="img-fluid border rounded-circle" src="<?= $user->getUserData()->getAvatarURL() ?>" width="35" height="35">
                        <i class="bi bi-chevron-down ms-1"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 g-2" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item mb-1" href="/profile/<?= $user->getUsername() ?>"><i class="bi bi-person-circle me-2"></i>My Profile</a></li>
                        <li><a class="dropdown-item mb-1" href="/profile/edit"><i class="bi bi-pencil me-2"></i>Edit Profile</a></li>
                        <li><a class="dropdown-item mb-1" href="/settings/account"><i class="bi bi-gear me-2"></i>Settings</a></li>
                        <li><a class="dropdown-item bg-body-tertiary text-warning" href="/pro/plans"><i class="bi bi-star me-2"></i>Upgrade to Pro</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-left me-2"></i>Sign Out</a></li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <div class="d-flex justify-content-center gap-2 align-items-center px-2 py-0">
                                <span class="user-select-none">Mode:</span>
                                <?php
                                $theme = $user->getPreferences()->getTheme();
                                ?>
                                <button type="button" class="btn border <?php if ($theme == 'light'): echo ('btn-prime');
                                                                        endif; ?>" data-bs-theme-value="light">
                                    <i class="bi bi-sun fs-5"></i>
                                </button>
                                <button type="button" class="btn border <?php if ($theme == 'dark'): echo ('btn-prime');
                                                                        endif; ?>" data-bs-theme-value="dark">
                                    <i class="bi bi-moon-stars fs-5"></i>
                                </button>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
