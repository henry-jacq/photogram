<?php


${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {

        if ($this->paramsExists(['follower_id'])) {
            $user = $this->getUser();
            $follower_id = (int) $this->data['follower_id'];

            $result = $this->user->toggleFollow($user, $follower_id);

            return $this->response([
                'message' => $result
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
