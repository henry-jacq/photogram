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

    if ($this->paramsExists(['username', 'password'])) {
        $data = [
            'username' => $this->data['username'],
            'password' => $this->data['password'],
            'ipAddress' => $this->getServerParam('REMOTE_ADDR'),
            'userAgent' => $this->getServerParam('HTTP_USER_AGENT')
        ];

        $user = $this->auth->isUserActive($data['username']);

        if ($user instanceof User) {
            $this->auth->updatePassCode($user);
            $this->auth->sendActivationEmail($user);
            return $this->response([
                'message' => 'Activation Required',
                'redirect' => $this->getRedirect('/activate?username=' . $user->getUsername())
            ], 202);
        }

        $result = $this->auth->login($data);

        if ($result) {
            usleep(mt_rand(400000, 1300000));
            return $this->response([
                'message' => 'Authenticated',
                'redirect' => $this->getRedirect('/home')
            ], 202);
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
