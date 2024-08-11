<?php

namespace App\Controllers\Files;

use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use getID3;
use App\Modules\FilesTable;

class FileUpload
{
    private $twig;
    private $allowedMimeTypes = [
        // Изображения
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/bmp',
        'image/tiff',
        'image/svg+xml',
        'image/x-icon',
        // Видео
        'video/mp4',
        'video/mpeg',
        'video/quicktime',
        'video/webm',
        'video/x-msvideo',
        'video/x-flv',
        'video/3gpp',
        'video/x-matroska',
        // Аудио
        'audio/mpeg',
        'audio/wav',
        'audio/ogg',
        'audio/aac',
        'audio/mp3',
        'audio/flac',
        'audio/x-ms-wma',
        'audio/x-wav',
        'audio/webm',
        // Документы
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/rtf',
        'application/json',
        'application/xml',
        'application/zip',
        'application/x-rar-compressed',
        'text/plain',
        'text/csv',
        'text/xml',
        'text/html',
        'text/markdown'
    ];


    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {

        $parsedBody = $request->getParsedBody();
        $uploadedFiles = $request->getUploadedFiles();
        $file = $uploadedFiles['file'] ?? null;
        if ($file && $file->getError() === UPLOAD_ERR_OK) {
            if ($file->getSize() > 8000000) {
                $parsedBody['message'] = "Too big size";
            } else {
                $mimeType = $file->getClientMediaType();
                $fileName = pathinfo($file->getClientFilename(), PATHINFO_FILENAME);
                if (strlen($fileName) > 100) {
                    $fileName = substr($fileName, 0, 100);
                }
                $this->moveUploadedFile($file, $mimeType, $fileName);
                $response = $response
                    ->withHeader('Location', '/file/' . $this->basename)
                    ->withStatus(302);
                return $response;
            }
        } else {
            $parsedBody['message'] = 'File not loaded.';
        }

        return $this->twig->render($response, '/upload.template.html', $parsedBody);
    }
    private function moveUploadedFile($uploadedFile, string $mimeType, string $fileName)
    {
        // Массив с информацией о файле для базы данных
        $infForDB = [];
        $infForDB['file_name'] = $fileName; //
        // Большая проверка на кол-во файлов в директории (не больше 500 на папку)
        $baseDirectory = BASE_DIR . 'var/uploadedFiles';
        $maxFilesPerDir = 500;
        $directories = array_filter(glob($baseDirectory . '/*'), 'is_dir');
        if (empty($directories)) {
            $directories[] = $baseDirectory . DIRECTORY_SEPARATOR . 'subdir_1';
            mkdir(end($directories));
        }
        $targetDirectory = null;
        foreach ($directories as $directory) {
            $files = array_diff(scandir($directory), array('..', '.'));
            if (count($files) < $maxFilesPerDir) {
                $targetDirectory = $directory;
                break;
            }
        }
        if (!$targetDirectory) {
            $newDirIndex = count($directories) + 1;
            $targetDirectory = $baseDirectory . DIRECTORY_SEPARATOR . 'subdir_' . $newDirIndex;
            mkdir($targetDirectory);
        }
        // Создание нового расширения и сохранение старого
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $infForDB['file_extension'] = $extension; //
        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            $extension = 'txt';
        }
        // Создание пути и сохранение
        $basename = bin2hex(random_bytes(8));
        $this->basename = $basename;
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        $finalDirectory = $targetDirectory . DIRECTORY_SEPARATOR . $filename;
        $infForDB['file_absolute_path'] = $finalDirectory;
        $infForDB['file_path'] = "/../var/uploadedFiles/" . basename(dirname($finalDirectory)) . '/'  . basename($finalDirectory);; //
        $uploadedFile->moveTo($finalDirectory);
        if ($extension != 'txt' && $extension != 'zip') {
            $getID3 = new getId3;
            $ThisFileInfo = $getID3->analyze($finalDirectory);

            // Преобразование метаданных в JSON
            $infForDB['metadata'] = json_encode($ThisFileInfo);
        } else {
            $infForDB['metadata'] = json_encode([]);
        }
        // Сохранение в базу данных
        $FilesTable = new FilesTable($GLOBALS['link']);
        $FilesTable->addFile($infForDB);
        return $infForDB;
    }
    private $basename;
}
