<?php

namespace silverorange\DevTest\Console;

use Exception;
use DateTime;

class PostImporterConsole
{

    protected \PDO $db;
    
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function execute(): void
    {
        try {
;
            $files = glob(__DIR__ . '/../../data/*.json');
            if (empty($files)) {
                throw new Exception("No JSON post files found.");
            }

            foreach ($files as $file) {
                $this->importFile($file);
            }
            echo "Import completed!\n";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    private function importFile(string $filePath): void
    {
        $data = json_decode((string) file_get_contents($filePath), true);

        /**
         * @var array<string, mixed> $data
         */
        if (!isset($data['id'], $data['title'], $data['body'], $data['author'])) {
            throw new Exception("Invalid JSON structure in file: $filePath");
        }

        $stmt = $this->db->prepare("SELECT 1 FROM Posts WHERE id = :id");
        $stmt->execute(['id' => $data['id']]);

        /**
         * @var array{
         *     id: string,
         *     title: string,
         *     body: string,
         *     author: string,
         *     created_at: string,
         *     modified_at: string
         * } $data
         */
        if ($stmt->fetch()) {
            echo "Skipping existing post: {$data['title']}\n";
            return;
        }

        $stmt = $this->db->prepare("
            INSERT INTO Posts (id, title, body, author, created_at, modified_at)
            VALUES (UNHEX(REPLACE(:id, '-', '')), :title, :body, UNHEX(REPLACE(:author, '-', '')), :created_at, :modified_at)
        ");

        $stmt->execute([
            'id' => $data['id'],
            'title' => $data['title'],
            'body' => $data['body'],
            'author' => $data['author'],
            'created_at' => (new DateTime($data['created_at']))->format('Y-m-d H:i:s'),
            'modified_at' => (new DateTime($data['modified_at']))->format('Y-m-d H:i:s')
        ]);

        echo "Imported: {$data['title']}\n";
    }
}
