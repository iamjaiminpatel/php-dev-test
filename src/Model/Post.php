<?php

namespace silverorange\DevTest\Model;

use PDO;
use PDOStatement;

class Post
{
    public string $id = '';
    public string $title = '';
    public string $body = '';
    public string $created_at = '';
    public string $modified_at = '';
    public string $author = '';

    /**
     * Get all posts.
     *
     * @param PDO $db
     * @return array<Post>
     */
    public static function getAllPosts(PDO $db): array
    {
        $sql = "
            SELECT 
                LOWER(
                    INSERT(
                        INSERT(
                            INSERT(
                                INSERT(HEX(p.id), 9, 0, '-'),
                            14, 0, '-'),
                        19, 0, '-'),
                    24, 0, '-')
                ) AS id,
                p.title,
                p.body,
                p.created_at,
                p.modified_at,
                a.full_name AS author
            FROM Posts p
            JOIN Authors a ON a.id = p.author
            ORDER BY p.created_at DESC
        ";

        $stmt = $db->query($sql);

        if (!$stmt instanceof PDOStatement) {
            // In case query fails
            return [];
        }

        /** @var array<Post> $posts */
        $posts = [];

        while (true) {
            /** @var array{
             *     id: string,
             *     title: string,
             *     body: string,
             *     created_at: string,
             *     modified_at?: string|null,
             *     author: string
             * }|false $row
             */
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row === false) {
                break;
            }

            $post = new self();
            $post->id = (string) $row['id'];
            $post->title = (string) $row['title'];
            $post->body = (string) $row['body'];

            $timestamp = strtotime((string) $row['created_at']);
            $post->created_at = $timestamp !== false ? date('F j, Y', $timestamp) : '';

            $post->modified_at = isset($row['modified_at']) ? (string) $row['modified_at'] : '';
            $post->author = (string) $row['author'];

            $posts[] = $post;
        }

        return $posts;
    }

    /**
     * Get details of a single post by ID.
     *
     * @param PDO $db
     * @param string $postId
     * @return Post|null
     */
    public static function getPostDetails(PDO $db, string $postId): ?Post
    {
        $sql = "
            SELECT 
                LOWER(
                    INSERT(
                        INSERT(
                            INSERT(
                                INSERT(HEX(p.id), 9, 0, '-'),
                            14, 0, '-'),
                        19, 0, '-'),
                    24, 0, '-')
                ) AS id,
                p.title,
                p.body,
                p.created_at,
                p.modified_at,
                a.full_name AS author
            FROM Posts p
            JOIN Authors a ON a.id = p.author
            WHERE p.id = UNHEX(REPLACE(:postId, '-', ''))
        ";

        $stmt = $db->prepare($sql);
        if (!$stmt instanceof PDOStatement) {
            return null;
        }

        $stmt->execute(['postId' => $postId]);

        /** @var array{
         *     id: string,
         *     title: string,
         *     body: string,
         *     created_at: string,
         *     modified_at?: string|null,
         *     author: string
         * }|false $row
         */
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $post = new self();
        $post->id = (string) $row['id'];
        $post->title = (string) $row['title'];
        $post->body = (string) $row['body'];

        $timestamp = strtotime((string) $row['created_at']);
        $post->created_at = $timestamp !== false ? date('F j, Y', $timestamp) : '';

        $post->modified_at = isset($row['modified_at']) ? (string) $row['modified_at'] : '';
        $post->author = (string) $row['author'];

        return $post;
    }
}