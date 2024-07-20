<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->paramsExists(['pid', 'comment']) && $this->isMethod('POST')) {
        $pid = $this->data['pid'];
        $text = $this->data['comment'];
        $commentedUser = $this->getUser();
        $avatar = $commentedUser->getUserData()->getAvatarURL();
        $commentId = $this->post->addComment($pid, $text, $commentedUser);
        
        if ($commentId !== false) {
            return $this->response([
                'message' => true,
                'username' => $commentedUser->getUsername(),
                'fullname' => $commentedUser->getUserData()->getFullname(),
                'avatar' => $avatar,
                'comment_id' => $commentId
            ], 200);
        }

        return $this->response([
            'message' => false
        ], 401);

    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
