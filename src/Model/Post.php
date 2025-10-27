<?php

namespace silverorange\DevTest\Model;


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
        $pdo = $db;
       $stmt = $pdo->query("
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
}
