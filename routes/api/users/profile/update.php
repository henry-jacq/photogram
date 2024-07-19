<?php

${basename(__FILE__, '.php')} = function () {
    if ($this->isAuthenticated() && $this->isMethod('POST')) {
        $params = ['fullname', 'website', 'jobTitle', 'bio', 'location', 'linkedin', 'instagram'];

        if ($this->paramsExists($params, false) && isset($this->files['user_image'])) {

            $this->data['avatar'] = $this->files['user_image'];
            
            $result = $this->user->updateUserData(
                $this->getUser(), 
                $this->data
            );

            $msg = "Not Updated";

            if ($result) {
                $msg = 'Updated';
            }

            return $this->response([
                'message' => $msg
            ], 200);
        }
    }
    return $this->response([
        'message' => 'Bad Request'
    ], 400);
};
