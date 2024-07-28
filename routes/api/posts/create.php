<?php

${basename(__FILE__, '.php')} = function () {  
    if (isset($this->files['file'][0]) && !empty($this->files['file'][0])) {
        $files = $this->files['file'];
        
        $paths = [];
        if (count($files) > 1) {
            foreach ($files as $file) {
                $paths[] = $file->getFilePath();
            }
        } else {
            $paths[] = $files[0]->getFilePath();
        }

        $data = [
            'images' => $paths,
            'user' => $this->getUser(),
            'ai_caption' => $this->data['ai_caption'],
            'user_caption' => $this->data['user_caption'],
        ];

        $result = $this->post->createPost($data);

        if (is_string($result)) {
            return $this->response([
                'message' => $result
            ], 413);
        }
        
        if ($result) {
            if ($this->data['ai_caption'] !== 'true') {
                usleep(mt_rand(1500000, 2800000));
            }
            return $this->response([
                'message' => true
            ], 200);
        }
        usleep(mt_rand(400000, 1300000));
        return $this->response([
            'message' => 'Not Created'
        ], 402);
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
