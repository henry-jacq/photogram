<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {

        // To fetch liked users
        if ($this->paramsExists(['likes'])) {

            $pid = $this->data['likes'];
            
            $data = $this->post->fetchLikedUsers($pid);

            $output = [];

            foreach ($data as $userId => $user) {
                $output[] = [
                    'username' => $user->getUsername(),
                    'fullname' => $user->getUserData()->getFullname(),
                    'avatar' => $user->getUserData()->getAvatarURL(),
                ];
            }

            $msg = count($output) > 0 ? true : false;

            return $this->response([
                'message' => $msg,
                'users' => $output
            ], 200);
        }

        // To fetch commented users
        if ($this->paramsExists(['comments'])) {
            
            $pid = $this->data['comments'];
            $user = $this->getUser();

            $comments = $this->post->fetchComments($pid);

            $msg = empty($comments) ? false : true;

            $commentData = [];
            foreach ($comments as $comment) {
                $userData = $comment->getCommentUser();
                $commentId = $comment->getId();
                $commentText = $comment->getContent();
                $timestamp = getHumanDiffTime($comment->getCommentDate()->format('Y-m-d H:i:s'));
                $username = $userData->getUsername();
                $fullname = $userData->getUserData()->getFullname();
                $avatar = $userData->getUserData()->getAvatarURL();

                $commentArray = [
                    'comment' => $commentText,
                    'timestamp' => $timestamp,
                    'username' => $username,
                    'fullname' => $fullname,
                    'avatar' => $avatar
                ];

                if ($user->getUsername() === $username) {
                    $commentArray['comment_id'] = $commentId;
                }

                $commentData[] = $commentArray;
            }

            if (count($commentData) === 0) {
                $userComments = array('users' => false);
            } else {
                $userComments = array('users' => $commentData);
            }
            
            $data = [
                'message' => $msg,
                'owner' => [
                    'username' => $user->getUsername(),
                    'avatar' => $user->getUserData()->getAvatarURL()
                ],
                'comments' => $userComments
            ];
            
            return $this->response($data, 200);
        }
    }
    
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
