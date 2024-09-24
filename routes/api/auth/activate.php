<?php

use App\Entity\User;

${basename(__FILE__, '.php')} = function () {
    if ($this->auth->isAuthenticated()) {
        usleep(mt_rand(400000, 1300000));
        return $this->response([
            'message' => 'Already Authenticated',
            'redirect' => $this->getRedirect('/home')
        ], 202);
    }

    if ($this->paramsExists(['username', 'passcode'])) {

        $username = $this->data['username'];
        $passcode = $this->data['passcode'];
        $user = $this->auth->isUserActive($username);

        if ($user instanceof User) {
            $status = $this->auth->activateAccount($user, $passcode);
            if ($status) {
                $message = 'Account activated!';
                $this->session->flash('success', [
                    'message' => $message
                ]);
                
                usleep(mt_rand(400000, 1300000));
                return $this->response([
                    'message' => $message,
                    'redirect' => $this->getRedirect('/login')
                ], 202);
            }
            usleep(mt_rand(400000, 1300000));
            return $this->response([
                'message' => 'Invalid Passcode'
            ], 401);
        }

        usleep(mt_rand(400000, 1300000));
        return $this->response([
            'message' => 'Unauthorized'
        ], 401);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
