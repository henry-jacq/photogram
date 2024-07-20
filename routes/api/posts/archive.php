<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {
        if ($this->paramsExists(['post_id'])) {

            $post_id = $this->data['post_id'];
            $user = $this->getUser();
            $post = $this->post->getPostById($post_id);

            if ($post) {
                if ($this->post->togglePostArchive($post_id, $user)) {
                    usleep(mt_rand(400000, 1300000));
                    return $this->response([
                        'message' => true
                    ], 200);
                }
                usleep(mt_rand(400000, 1300000));
                return $this->response([
                    'message' => 'Not Archived'
                ], 402);
            }
        }
        
        usleep(mt_rand(400000, 1300000));
        
        return $this->response([
            'message' => 'Bad Request'
        ], 400);
    }
};
