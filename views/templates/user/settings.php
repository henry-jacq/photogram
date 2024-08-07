<div class="container">
    <div class="row">
        <div class="col-lg-3">
            <div class="card mb-2 border-0 m-0">
                <div class="card-header border-0 bg-transparent mt-4 mx-2">
                    <div class="d-none d-lg-inline-block w-100">
                        <h4 class="fs-5 fw-normal ms-3"><i class="bi bi-gear me-2"></i>Settings</h4>
                        <hr>
                    </div>
                    <ul class="nav nav-pills d-lg-block d-none">
                        <li class="nav-item">
                            <a class="nav-link <?php if($tab=='account'): echo('active'); endif;?>" href="/settings/account">
                                <i class="fas fa-user me-2"></i>My Account
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if($tab=='manage'): echo('active'); endif;?>" href="/settings/manage">
                                <i class="fas fa-envelope me-2"></i>Manage Email
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if($tab== 'change-password'): echo('active'); endif;?>" href="/settings/change-password">
                                <i class="fas fa-lock me-2"></i>Change Password
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if($tab== 'notifications'): echo('active'); endif;?>" href="/settings/notifications">
                                <i class="fas fa-bell me-2"></i>Notifications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if($tab== 'sessions'): echo('active'); endif;?>" href="/settings/sessions">
                                <i class="fas fa-desktop me-2"></i>Active Sessions
                            </a>
                        </li>
                    </ul>

                    <ul class="nav nav-tabs d-lg-none" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
                        <li class="nav-item">
                            <a class="nav-link <?php if($tab== 'account'): echo('active'); endif;?>" href="/settings/account" role="tab">
                                <i class="fas fa-user"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if($tab== 'manage'): echo('active'); endif;?>" href="/settings/manage" role="tab">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if($tab== 'change-password'): echo('active'); endif;?>" href="/settings/change-password" role="tab">
                                <i class="fas fa-lock"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if($tab== 'notifications'): echo('active'); endif;?>" href="/settings/notifications" role="tab">
                                <i class="fas fa-bell"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if($tab== 'sessions'): echo('active'); endif;?>" href="/settings/sessions" role="tab">
                                <i class="fas fa-desktop"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <?php $this->renderComponent("settings/{$tab}", [
                'user' => $user,
                'sessions' => $sessions,
                'sessionToken' => $sessionToken
            ]); ?>
        </div>
    </div>
</div>