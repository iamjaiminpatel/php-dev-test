<?php

namespace silverorange\DevTest\Model;

use PDO;


class Post
{
    public string $id;
    public string $title;
    public string $body;
    public string $created_at;
    public string $modified_at;
    public string $author;

    public static function getAllPosts($db): array
    {
       $stmt = $db->query("
            SELECT 
                LOWER(
                    INSERT(
                        INSERT(
                            INSERT(
                                INSERT(HEX(p.id), 9, 0, '-'),
                            14, 0, '-'),
                        19, 0, '-'),
                    24, 0, '-')
                )  as post_id,
            p.title, p.body, p.created_at, a.full_name AS author_name
            FROM Posts p
            JOIN Authors a ON a.id = p.author
            ORDER BY p.created_at DESC
        ");

        $posts = [];
        
        while ($row = $stmt->fetch()) {
            $posts[] = $row;
        }
        return $posts;
    }

    public static function getPostDetails($db, $postId)
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
                    ) AS post_id,
                    p.title,
                    p.body,
                    p.created_at,
                    a.full_name AS author_name
                FROM Posts p
                JOIN Authors a ON a.id = p.author
                WHERE p.id = UNHEX(REPLACE('$postId', '-', ''))
        ";

        $stmt = $db->query($sql);
        $post_details = $stmt->fetch(PDO::FETCH_ASSOC);
       
        $post = new self();
        $post->id = $post_details['id'] ?? '';
        $post->title = $post_details['title'] ?? '';
        $post->body = $post_details['body'] ?? '';
        $post->created_at = date('F j, Y', strtotime($post_details['created_at'])) ?? '';
        $post->modified_at = $post_details['modified_at'] ?? '';
        $post->author = $post_details['author_name'] ?? '';

        return $post;

    }
}