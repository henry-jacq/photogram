<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {
        if ($this->paramsExists(['id', 'text'])) {
            
            $pid = $this->data['id'];
            $post = $this->post->getPostById($pid);
            $caption = htmlspecialchars($this->data['text']);

            if ($post->getUser() !== $this->getUser()) {
                return $this->response([
                    'message' => 'Not Modified'
                ], 304);
            }
            
            $result = $this->post->updatePostCaption($post, $caption);
            
            return $this->response([
                'message' => $result
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
