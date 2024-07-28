<div class="px-md-5 my-3">
    <div class="row border rounded-3">
        <div class="col-lg-3 bg-body-tertiary rounded-3 py-5">
            <div class="d-flex flex-column align-items-center text-center mt-3">
                <?php $userData = $user->getUserData(); ?>
                <img class="rounded-circle border border-2 border-prime" width="150" src="<?= $userData->getAvatarURL() ?>">
                <span class="fs-5 fw-semibold mt-2"><?= $userData->getFullName() ?></span>
                <span class="small mt-2"><?= $user->getPrimaryEmail() ?></span>
            </div>
        </div>
        <div class="col-lg-9 profile-body">
            <div class="px-3 mt-4">
                <h4 class="fw-normal"><i class="fa-fw bi bi-pencil me-2"></i>Edit Profile</h4>
                <hr>
            </div>
            <form class="user-form-data p-3" method="POST" autocomplete="off">
                <div class="form-group mb-3">
                    <label for="user-avatar" class="form-label fw-semibold">Upload new avatar</label>
                    <p class="small mb-2">You can change your avatar here or remove the current avatar to revert to the default avatar.</p>
                    <input class="form-control" type="file" id="user-avatar" name="user_image">
                    <div class="text-secondary small mb-2">The maximum file size allowed is 800KB.</div>
                    <?php if (!empty($userData->getAvatarURL()) && basename($userData->getAvatarURL()) != 'default.png') : ?>
                        <div class="d-flex justify-content-end mb-3">
                            <button id="btnRemoveAvatar" class="btn btn-sm btn-outline-danger" type="button"><i class="bi bi-trash me-1"></i>Remove avatar</button>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group mb-3">
                    <label for="fullName" class="form-label fw-semibold">Full Name</label>
                    <input type="text" id="fullName" class="form-control" name="fullname" placeholder="Full name" aria-label="Full name" value="<?php echo $userData->getFullName(); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="website" class="form-label fw-semibold">Website</label>
                    <input id="website" class="form-control" type="text" name="website" placeholder="https://mywebsite.com" value="<?php echo $userData->getWebsite(); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="job" class="form-label fw-semibold">Job title</label>
                    <select id="jobTitle" class="form-select" name="jobTitle" aria-label="Select Job Title">
                        <option <?php if (is_null($userData->getJobTitle()) || $userData->getJobTitle() == 'None') echo 'selected'; ?>>None</option>
                        <option <?php if ($userData->getJobTitle() == 'App Developer') echo 'selected'; ?>>App Developer</option>
                        <option <?php if ($userData->getJobTitle() == 'Content Creator') echo 'selected'; ?>>Content Creator</option>
                        <option <?php if ($userData->getJobTitle() == 'Photographer') echo 'selected'; ?>>Photographer</option>
                        <option <?php if ($userData->getJobTitle() == 'Software Engineer') echo 'selected'; ?>>Software Engineer</option>
                        <option <?php if ($userData->getJobTitle() == 'Student') echo 'selected'; ?>>Student</option>
                        <option <?php if ($userData->getJobTitle() == 'UI/UX Designer') echo 'selected'; ?>>UI/UX Designer</option>
                    </select>
                    <p class="form-text mb-0">The job you selected will be shown in your posts.</p>
                </div>
                <div class="form-group mb-3">
                    <label for="bio" class="form-label fw-semibold">Bio</label>
                    <textarea id="bio" class="form-control" rows="5" name="bio" placeholder="Write about you..." maxlength="100"><?php echo $userData->getBio(); ?></textarea>
                    <p class="form-text mb-0">Tell us about yourself in fewer than 100 characters.</p>
                </div>
                <div class="form-group mb-3">
                    <label for="location" class="form-label fw-semibold">Location</label>
                    <input id="location" class="form-control" type="text" name="location" spellcheck="false" placeholder="City, Country" value="<?php echo $userData->getLocation(); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="linkedin" class="form-label fw-semibold">Linkedin</label>
                    <input id="linkedin" class="form-control" type="text" name="linkedin" spellcheck="false" placeholder="https://linkedin.com/in/username" value="<?php echo $userData->getLinkedin(); ?>">
                </div>
                <div class="form-group mb-4">
                    <label for="instagram" class="form-label fw-medium">Instagram</label>
                    <input id="instagram" class="form-control" type="text" name="instagram" spellcheck="false" placeholder="https://instagram.com/username" value="<?php echo $userData->getInstagram(); ?>">
                </div>
                <div class="d-flex justify-content-start gap-2">
                    <button class="btn btn-prime btn-save-data" type="button">Update profile</button>
                    <a href="/" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>