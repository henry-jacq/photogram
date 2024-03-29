<?php

namespace App\Model;

use DateTime;
use Exception;
use ZipArchive;
use Carbon\Carbon;
use App\Core\Model;
use MongoDB\BSON\ObjectId;


class Post extends Model
{
    protected $collectionName = 'posts';
    protected $storage_path = STORAGE_PATH . '/posts/';

    public function __construct(
        $mongoDB,
        private readonly User $user,
        private readonly ZipArchive $zip
    )
    {
        parent::__construct($mongoDB, $this->collectionName);
        if (!file_exists($this->storage_path)) {
            mkdir($this->storage_path);
        }
    }

    /**
     * Get user posts by user ID
     */
    public function getUserPosts(string $user_id)
    {
        $cursor = $this->findById($user_id, 'user_id', false, true);

        $posts = iterator_to_array($cursor);

        usort($posts, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        $userData = $this->getUsersByIds([$user_id]);

        $formattedPosts = [];

        foreach ($posts as $post) {
            $formattedPost = (array)$post;
            $formattedPost['likes'] = count($post->likes);
            $formattedPost['liked_users'] = $post->likes;
            $formattedPost['created_at'] = $this->getHumanTime($post->created_at);
            $formattedPost['avatar'] = $this->user->getUserAvatar($userData[$post->user_id]);
            unset($userData[$post->user_id]['avatar
            ']);
            $formattedPost['userData'] = $userData[$post->user_id] ?? null;
            $formattedPosts[] = $formattedPost;
        }

        return $formattedPosts;
    }

    public function getLatestPosts(int $limit = 10)
    {
        $posts = $this->findAll()->toArray();
        
        usort($posts, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        $posts = array_slice($posts, 0, $limit);

        $userIds = array_column($posts, 'user_id');
        $userData = $this->getUsersByIds($userIds);

        $formattedPosts = [];

        foreach ($posts as $post) {
            $formattedPost = (array)$post;
            $formattedPost['likes'] = count($post->likes);
            $formattedPost['liked_users'] = $post->likes;
            $formattedPost['created_at'] = $this->getHumanTime($post->created_at);
            $formattedPost['avatar'] = $this->user->getUserAvatar($userData[$post->user_id]);
            unset($userData[$post->user_id]['avatar
            ']);
            $formattedPost['userData'] = $userData[$post->user_id] ?? null;
            $formattedPosts[] = $formattedPost;
        }

        return $formattedPosts;
    }

    public function fetchPosts($limit, $skip)
    {
        $posts = $this->collection->find()->toArray();

        usort($posts, function ($a, $b) {
            return strtotime($b->created_at) - strtotime($a->created_at);
        });

        $posts = array_slice($posts, $skip, $limit);

        $userIds = array_column($posts, 'user_id');
        $userData = $this->getUsersByIds($userIds);

        $formattedPosts = [];

        foreach ($posts as $post) {
            $formattedPost = (array)$post;
            $formattedPost['likes'] = count($post->likes);
            $formattedPost['liked_users'] = $post->likes;
            $formattedPost['created_at'] = $this->getHumanTime($post->created_at);
            $formattedPost['avatar'] = $this->user->getUserAvatar($userData[$post->user_id]);
            unset($userData[$post->user_id]['avatar
            ']);
            $formattedPost['userData'] = $userData[$post->user_id] ?? null;
            $formattedPosts[] = $formattedPost;
        }

        return $formattedPosts;
    }

    /**
     * Get Human readable time format
     */
    public function getHumanTime(string $timestamp)
    {
        $time = Carbon::parse($timestamp);
        return $time->diffForHumans();
    }

    /**
     * Get post by its ID
     */
    public function getPostById(string $pid)
    {
        $result = $this->findById($pid);
        return $result;
    }

    /**
     * Return list of users data
     */
    public function getUsersByIds(array $userIds)
    {
        $objectIds = array_map(function ($userId) {
            return $this->createMongoId($userId);
        }, $userIds);

        $c = $this->db->selectCollection('users');
        $users = $c->find(['_id' => ['$in' => $objectIds]]);
        $userData = [];

        foreach ($users as $user) {
            $userData[(string)$user->_id] = $user;
        }

        return $userData;
    }

    public function createPost(array $data)
    {
        foreach ($data['images'] as $image) {
            $path = $this->storeImage($image);
            $url[] = $path;
        }

        $schema = [
            'user_id' => $data['user_id'],
            'images' => $url,
            'caption' => $data['text'],
            'likes' => [],
            'comments' => [],
            'created_at' => now(),
            'updated_at' => now(),
        ];
        return $this->create($schema);
    }

    public function getPostZip(string $postId)
    {
        $images = $this->getPostImages($postId);

        if (!$images) {
            return false;
        }

        $name = $this->getZipFileName();
        $tempPath = STORAGE_PATH . DIRECTORY_SEPARATOR . 'temp';
        $zipPath = $tempPath . DIRECTORY_SEPARATOR . $name;
        if (!file_exists($tempPath)) {
            mkdir($tempPath);
        }
        if ($this->zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;   // Unable to open the zip file
        }

        foreach ($images as $image) {
            $path = $this->storage_path . $image;
            $this->zip->addFile($path, $image);
        }

        $this->zip->close();
        return $zipPath;
    }

    private function getZipFileName()
    {
        $formattedDateTime = (new DateTime())->format('His');
        $randomString = bin2hex(random_bytes(4));
        $name = "Photogram_Image_{$formattedDateTime}{$randomString}.zip";
        return $name;
    }

    /**
     * Delete post with images
     */
    public function deletePost(string $id)
    {
        try {
            $data = $this->findById($id);

            if (is_null($data)) {
                return false;
            }

            $this->deleteImage(iterator_to_array($data));

            $this->delete($id);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updatePostText(string $id, string $text)
    {
        if (!empty($text) && strlen($text) >= 240) {
            return false;
        }
        
        $data = ['$set' => ['caption' => $text]];

        $this->update($id, $data);

        return true;
    }

    public function storeImage(string $image_tmp)
    {
        if (is_file($image_tmp) && exif_imagetype($image_tmp) !== false) {
            $name = md5(time() . mt_rand(0, 99999));
            $ext = image_type_to_extension(exif_imagetype($image_tmp));
            $image = $name . $ext;
            $image_path = $this->storage_path . $image;

            if (move_uploaded_file($image_tmp, $image_path)) {
                return $image;
            }

            throw new Exception("Can't move the uploaded file");
        } else {
            throw new Exception("Not a valid image path!");
        }
    }

    public function getImage(string $image)
    {
        $filePath = $this->storage_path . $image;
        if (file_exists($filePath) && is_file($filePath)) {
            return file_get_contents($filePath);
        }

        return false;
    }

    /**
     * Return the list of image names related to post
     */
    protected function getPostImages(string $pid)
    {
        $post = $this->findById($pid);
        if ($post !== null) {
            return iterator_to_array($post['images']);
        } else {
            return false;
        }
    }

    /**
     * Delete Images from Storage
     */
    public function deleteImage(array $data)
    {
        try {
            foreach ($data['images'] as $image) {
                $image_path = $this->storage_path . $image;
                if (file_exists($image_path)) {
                    if (unlink($image_path)) {
                        continue;
                    } else {
                        throw new Exception('Cannot remove image: ' . $image_path);
                    }
                }
            }
            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Toggle post likes
     */
    public function toggleLikes(string $pid, string $uid)
    {
        $post = $this->findById($pid);
        if (in_array($uid, (array)$post['likes'])) {
            $query = [
                '$pull' => ['likes' => $uid]
            ];
        } else {
            $query = [
                '$push' => ['likes' => $uid]
            ];
        }
        $result = $this->update($pid, $query);
        
        if ($result->getModifiedCount() > 0) {
            return true;
        }

        return false;
        
    }

    /**
     * Get liked users data
     */
    public function getLikedUsers(string $pid)
    {
        $cursor = $this->findById($pid, multiple: true);

        $posts = iterator_to_array($cursor);
        $userIds = array_column($posts, 'likes');

        $userIds = (array)$userIds[0];
        $userData = $this->getUsersByIds($userIds);

        return $userData;
    }

    /**
     * Get total likes of a user
     */
    public function getUserLikesCount(string $user_id)
    {
        $pipeline = [
            ['$match' => ['user_id' => $user_id]],
            ['$project' => ['likes' => 1]],
            ['$unwind' => '$likes'],
            ['$group' => [
                '_id' => '$user_id',
                'totalLikes' => ['$sum' => 1]
            ]]
        ];
        
        $cursor = $this->collection->aggregate($pipeline);
        $result = [];

        foreach ($cursor as $doc) {
            $result[] = $doc;
        }

        if (empty($result)) {
            return count($result);
        }

        return $result[0]['totalLikes'];
    }

    /**
     * Add comments to post
     */
    public function addComment(string $pid, string $uid, string $text)
    {
        $query = ['$push' => [
            'comments' => [
                '_id' => $this->createMongoId(null),
                'uid' => $uid,
                'text' => $text,
                'timestamp' => now()
            ]
        ]];
        
        $result = $this->update($pid, $query);

        if ($result->getModifiedCount() > 0) {
            return (string)$query['$push']['comments']['_id'];
        }

        return false;
    }

    /**
     * Fetch comments for given post ID
     */
    public function fetchComments(string $pid)
    {
        $query = [
            '_id' => $this->createMongoId($pid)
        ];

        $post = $this->findOne($query);

        return (array) $post->comments;
    }

    /**
     * Delete a comment
     */
    public function deleteComment(string $pid, string $cid)
    {
        $data = [
            '$pull' => [
                'comments' => [
                    '_id' => $this->createMongoId($cid)
                ]
            ]
        ];

        $result = $this->update($pid, $data);

        if ($result->getModifiedCount() > 0) {
            return true;
        }

        return false;
        
    }
}
