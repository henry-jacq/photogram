<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Controller;
use App\Services\PostService;
use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProfileController extends Controller
{
    public function __construct(
        private readonly View $view,
        private readonly PostService $post,
        private readonly UserService $user
    ) {
        parent::__construct($view);
    }

    public function profile(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');
        $name = strtolower($request->getAttribute('name'));
        $profileUser = $this->user->getByUsername($name);
        $posts = $this->post->fetchUserPosts($profileUser);
        $totalLikes = $this->post->getTotalUserLikes($profileUser);

        // Only if the user exists else render error page
        if ($profileUser) {
            $args = [
                'name' => $name,
                'user' => $userData,
                'profileUser' => $profileUser,
                'posts' => $posts,
                'totalLikes' => $totalLikes,
                'title' => ucfirst($name) . "'s Profile"
            ];
            return $this->render($response, 'user/profile', $args);
        }
        
        return $this->renderErrorPage($response, ['code' => 404, 'title' => 'Not Found']);
    }

    public function edit(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');

        $args = [
            'title' => "Edit Profile",
            'user' => $userData
        ];
        return $this->render($response, 'user/edit', $args);
    }
}
