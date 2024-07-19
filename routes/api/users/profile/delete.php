<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {
        // Get the user data
        $ud = $this->getUser()->getUserData();
        
        // Delete the avatar from the storage folder
        $this->user->deleteAvatar($ud);
        
        // Set the default avatar
        $ud->setProfileAvatar('default.png');
        $this->manager->persist($ud);
        $this->manager->flush();

        return $this->response([
            'message' => true
        ], 200);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
