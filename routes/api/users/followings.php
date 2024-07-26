<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('GET')) {
        if ($this->paramsExists(['id'])) {

            $user = $this->getUser();
            $userId = (int)$this->data['id'];

            if ($this->getUserId() != $userId) {
                $user = $this->user->getUserById($userId);
            }

            if (empty($user)) {
                return $this->response([
                    'message' => 'Not Found'
                ], 404);
            }

            $result = $this->user->getFollowings($user);

            return $this->response([
                'message' => empty($result) ? false : true,
                'followings' => $result
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
