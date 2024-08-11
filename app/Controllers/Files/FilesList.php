<?php

namespace App\Controllers\Files;

use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Modules\FilesTable;

class FilesList
{
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $filesTable = new FilesTable($GLOBALS['link']);
        $result = $filesTable->getFiles();
        $fileNames = [];
        $filePaths = [];
        $fileExtensions = [];

        foreach ($result as $file) {
            $fileNames[] = $file['file_name'];
            $filePaths[] = $file['file_path'];
            $fileExtensions[] = $file['file_extension'];
        }

        return $this->twig->render($response, '/fileslist.template.html', [
            'fileNames' => $fileNames,
            'filePaths' => $filePaths,
            'fileExtensions' => $fileExtensions
        ]);
    }
}
