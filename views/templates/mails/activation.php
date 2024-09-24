<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">Activate Your Account</h2>
                    <p class="card-text text-center mb-4">Hello <?= $userName ?>, Please use the 6-digit passcode below to activate your account.</p>
                    <div class="text-center mb-4">
                        <span style="font-size: 2rem; letter-spacing: 0.5rem; font-weight: bold; color: #333;"><?= $passCode ?></span>
                    </div>
                    <p class="text-center">Enter this passcode on the account verification page to complete your activation process. This code is valid for the next 10 minutes.</p>
                    <hr>
                    <p class="text-center">If you did not request this, please ignore this email.</p>
                </div>
            </div>
            <p class="text-center mt-3 text-muted small">&copy; <?= date("Y") ?> Photogram. All Rights Reserved.</p>
        </div>
    </div>
</div>