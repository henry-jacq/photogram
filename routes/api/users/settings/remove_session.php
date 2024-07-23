<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {
        if ($this->paramsExists(['id'])) {

            $sessionId = (int) $this->data['id'];
            $userSession = $this->session->get('userSession');

            if ($userSession->getId() === $sessionId) {
                $this->auth->logout();
                return $this->response([
                    'message' => "Session Removed!",
                    'redirect' => '/login'
                ], 200);
            }

            $result = $this->auth->terminateSessionById($sessionId);
            return $this->response([
                'message' => $result
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
