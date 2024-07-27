<?php

${basename(__FILE__, '.php')} = function () {  
    if ($this->paramsExists(['username', 'password'])) {
        $data = [
            'username' => $this->data['username'],
            'password' => $this->data['password'],
            'ipAddress' => $this->getServerParam('REMOTE_ADDR'),
            'userAgent' => $this->getServerParam('HTTP_USER_AGENT')
        ];
        
        $login = $this->auth->login($data);

        if ($login) {
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
