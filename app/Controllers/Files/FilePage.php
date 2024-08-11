<?php

namespace App\Controllers\Files;

use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Modules\FilesTable;

class FilePage
{
    private $twig;
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $filesTable = new FilesTable($GLOBALS['link']);
        $info = $filesTable->getFileByID($args['id']);

        $prettyMetadata = $info['metadata'];
        $prettyMetadata = json_decode($prettyMetadata, true);
        unset($prettyMetadata['filepath']);
        unset($prettyMetadata['filename']);
        unset($prettyMetadata['filenamepath']);
        unset($prettyMetadata['GETID3_VERSION']);
        $info['metadata'] = json_encode($prettyMetadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // Определяем тип файла
        $fileType = strtolower($info['file_extension']);
        $isImage = in_array($fileType, ['jpg', 'jpeg', 'png', 'gif']);
        $isVideo = in_array($fileType, ['mp4', 'webm', 'ogg']);
        $isAudio = in_array($fileType, ['mp3', 'mpeg', 'wav', 'flac', 'ogg']);


        return $this->twig->render($response, '/filepage.template.html', [
            'file' => $info,
            'fileId' => $args['id'],
            'isImage' => $isImage,
            'isVideo' => $isVideo,
            'isAudio' => $isAudio,
            'metadata' => $prettyMetadata
        ]);
    }
}
