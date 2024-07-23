<?php

namespace App\Controller;

use App\Core\View;
use App\Core\Controller;
use App\Services\AuthService;
use App\Services\PostService;
use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends Controller
{
    public function __construct(
        private readonly View $view,
        private readonly AuthService $auth,
        private readonly PostService $post,
        private readonly UserService $user,
    ) {
        parent::__construct($view);
    }

    public function home(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');
        $posts = $this->post->fetchAllPosts();

        $args = [
            'title' => 'Home',
            'user' => $userData,
            'posts' => $posts
        ];
        return $this->render($response, 'user/home', $args);
    }

    public function discover(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');

        $args = [
            'title' => 'Discover',
            'user' => $userData
        ];
        return $this->render($response, 'user/discover', $args);
    }

    public function subscribe(Request $request, Response $response): Response
    {
        $userData = $request->getAttribute('userData');

        $args = [
            'title' => 'Photogram Pro',
            'user' => $userData
        ];

        return $this->render($response, 'user/subscribe', $args);
    }

    public function settings(Request $request, Response $response): Response
    {
        $tab = $request->getAttribute('tab');
        $userData = $request->getAttribute('userData');

        $args = [
            'title' => "Settings - " . ucfirst($tab),
            'user' => $userData,
            'tab' => $tab,
        ];
        
        return $this->render($response, "user/settings", $args);
    }

    public function files(Request $request, Response $response): Response
    {
        $category = $request->getAttribute('category');
        $imageName = $request->getAttribute('image');

        $path = STORAGE_PATH . '/' . $category . '/' . $imageName;
        
        if (in_array($category, ['posts', 'avatars'])) {
            if ($category == 'posts') {
                $image = $this->post->getImage($imageName);
            }
            if ($category == 'avatars') {
                $image = $this->user->getAvatar($imageName);
            }
            
            if (!$image) {
                return $response->withStatus(404);
            }
        } else {
            return $response->withStatus(404);
        }

        $response->getBody()->write($image);

        return $response
        ->withHeader('Content-Type', mime_content_type($path))
        ->withHeader('Content-Length', filesize($path))
        ->withHeader('Cache-Control', 'max-age=' . (60 * 60 * 24 * 365))
        ->withHeader('Expires', gmdate(DATE_RFC1123, time() + 60 * 60 * 24 * 365))
        ->withHeader('Last-Modified', gmdate(DATE_RFC1123, filemtime($path)))
        ->withHeader('Pragma', '');
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->auth->logout();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}
