<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {
        if ($this->paramsExists(['id'])) {
            
            $pid = $this->data['id'];
            $post = $this->post->getPostById($pid);

            if (!$post) {
                return $this->response([
                    'message' => 'Post not found'
                ], 404);
            }

            if ((int)$post->getUser()->getId() !== (int)$this->getUserId()) {
                return $this->response([
                    'message' => 'Unauthorized'
                ], 401);
            }
            
            $result = $this->post->deletePost($post);
            
            return $this->response([
                'message' => $result
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
