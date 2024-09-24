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

    if ($this->paramsExists(['username', 'resend'])) {

        $username = $this->data['username'];
        $status = $this->data['resend'];

        $user = $this->auth->isUserActive($username);

        if ($status && $user instanceof User) {
            $this->auth->updatePassCode($user);
            $this->auth->sendActivationEmail($user);

            usleep(mt_rand(400000, 1300000));
            return $this->response([
                'message' => 'Code Sent'
            ], 201);
        }

        $this->session->flash('error', [
            'message' => 'User is already active!'
        ]);
        
        usleep(mt_rand(400000, 1300000));
        return $this->response([
            'message' => 'Unauthorized',
            'redirect' => $this->getRedirect('/login')
        ], 401);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
