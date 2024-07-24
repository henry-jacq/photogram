<div class="tab-content p-3">
    <div class="tab-pane fade active show" id="sessions" role="tabpanel" aria-labelledby="sessions-tab">
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
                            <?php foreach ($sessions as $session) : ?>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="mb-5 me-3 p-1" data-toggle="tooltip" title="Desktop"><i class="bi <?php if (getDeviceType($session->getUserAgent()) == 'Phone'): echo('bi-phone'); else: echo('bi-display'); endif;?>"></i></div>
                                            <div class="float-left my-3">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <?php echo($session->getIpAddress());
                                                        if ($session->getSessionToken() == $sessionToken): echo('<span class="badge text-bg-success ms-2">Active</span>'); endif; ?>
                                                    </h6>
                                                </div>
                                                <div>
                                                    Last accessed on <?= $session->getLastActivity()->format('d M H:i')?>
                                                </div>
                                                <div>
                                                    <strong><?= getBrowser($session->getUserAgent())?></strong>
                                                    on
                                                    <strong><?= getOS($session->getUserAgent())?></strong>
                                                </div>
                                                <div>
                                                    <strong>Signed in</strong>
                                                    on <?= $session->getLoginTime()->format('d M H:i')?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="float-right" data-session-id="<?= $session->getId() ?>">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove-session ms-3">Revoke</a>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>