<?php

namespace App\Modules;

class FilesTable
{
    private $link;
    public function __construct($link)
    {
        $this->link = $link;
    }
    public function addFile(array $fileInfo)
    {
        $query = "INSERT INTO files (file_name, file_path, file_absolute_path, file_extension, metadata) VALUES (?, ?, ?, ?, ?)";
        $types = "sssss";
        $params = array_map(
            fn($key) => $fileInfo[$key],
            ['file_name', 'file_path', 'file_absolute_path', 'file_extension', 'metadata']
        );
        $stmt = $this->link->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->close();
    }
    public function getFiles()
    {
        $query = "SELECT file_name, file_path, file_extension, created_at FROM files";
        $stmt = $this->link->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $files = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $files;
    }
    public function getFileByID($id)
    {
        $searchPattern = '%' . $id . '%';

        $query = "SELECT file_name, file_path, file_absolute_path, file_extension, metadata, created_at 
FROM files 
WHERE file_path LIKE ?;
";
        $stmt = $this->link->prepare($query);
        $stmt->bind_param("s", $searchPattern);
        $stmt->execute();
        $result = $stmt->get_result();
        $file = $result->fetch_assoc();
        $stmt->close();
        return $file;
    }
    public function getFilesExtensions()
    {
        $query = "SELECT file_extension FROM files";
        $stmt = $this->link->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $files = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $files;
    }
}
