<div class="container px-md-5">
    <div class="post-feed-section py-4">
        <?php
        $usedSpace = $user->getStorage()->getUsedSpace();
        $totalSpace = $user->getStorage()->getTotalSpace();
        $remainingSpace = $totalSpace - $usedSpace;
        $usedPercentage = ($usedSpace / $totalSpace) * 100;
        $remainingPercentage = ($remainingSpace / $totalSpace) * 100;
        $progressBarClass = $usedPercentage >= 80 ? 'bg-danger' : 'bg-primary';
        ?>
        <div class="rounded p-3 mb-2 bg-body-tertiary">
            <h3 class="lead fw-normal mb-3"><i class="bi bi-cloud me-2"></i>Storage (<?= number_format($usedPercentage) ?>% full)</h3>
            <div class="progress mt-3 storage-progress">
                <div class="progress-bar <?php echo $progressBarClass; ?>" role="progressbar" style="width: <?php echo $usedPercentage; ?>%;" aria-valuenow="<?php echo $usedPercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                <?php if ($usedPercentage < 100) : ?>
                    <div class="progress-bar bg-body-secondary" role="progressbar" style="width: <?php echo $remainingPercentage; ?>%;" aria-valuenow="<?php echo $remainingPercentage; ?>" aria-valuemin="0" aria-valuemax="100">
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-body-secondary">
                <small><?= formatSizeUnits($usedSpace); ?> of <?= formatSizeUnits($totalSpace); ?> used</small>
            </div>
            <a href="/settings/account" class="btn btn-sm btn-warning mt-3">Buy Storage</a>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <h3 class="fw-light mt-3">My Feed</h3>
            <div class="d-none d-md-inline-block">
                <div class="btn-group btn-group-sm" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="view_mode" value="grid" id="btnRadioGrid" autocomplete="off">
                    <label class="btn btn-outline-prime rounded-start-4" for="btnRadioGrid"><i class="bi bi-grid-3x3 me-2"></i>Grid</label>
                    <input type="radio" class="btn-check" name="view_mode" value="list" id="btnRadioList" autocomplete="off">
                    <label class="btn btn-outline-prime rounded-end-4" for="btnRadioList"><i class="bi bi-grid-1x2 me-2"></i>List</label>
                </div>
            </div>
        </div>
        <hr class="m-0 py-2">
        <?php if (!$posts && count($posts) < 1) : ?>
            <div class="text-center py-5">
                <i class="bi bi-plus-circle display-4 mb-4"></i>
                <p class="text-muted text-center align-items-center mb-0 ">Start sharing posts to make it a better place!</p>
            </div>
        <?php else : ?>
            <div class="row g-3" id="masonry-area" data-masonry='{ "percentPosition": true }'>
                <?php foreach ($posts as $p) : ?>
                    <div class="grid-item col-xxl-3 col-lg-4 col-md-6" id="post-<?= $p->getId() ?>">
                        <?php $this->renderComponent('card', [
                            'p' => $p,
                            'user' => $user
                        ]); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>