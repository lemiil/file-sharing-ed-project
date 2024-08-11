<?php

namespace App\Controllers\Files;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Modules\FilesTable;

class FileDownload
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $filesTable = new FilesTable($GLOBALS['link']);
        $fileInfo = $filesTable->getFileByID($args['id']);
        $file_path = $fileInfo['file_absolute_path'];

        $new_file_name = $fileInfo['file_name'] . '.' . $fileInfo['file_extension'];


        if (file_exists($file_path)) {
            $response = $response->withHeader('Content-Description', 'File Transfer')
                ->withHeader('Content-Type', 'application/octet-stream')
                ->withHeader('Content-Disposition', 'attachment; filename="' . basename($new_file_name) . '"')
                ->withHeader('Expires', '0')
                ->withHeader('Cache-Control', 'must-revalidate')
                ->withHeader('Pragma', 'public')
                ->withHeader('Content-Length', (string) filesize($file_path));
            $response->getBody()->write(file_get_contents($file_path));

            return $response;
        } else {
            $response->getBody()->write('File not found.');
            return $response->withStatus(404);
        }
    }
}
