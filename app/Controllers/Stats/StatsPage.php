<?php

namespace App\Controllers\Stats;

use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Modules\FilesTable;

class StatsPage
{
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $filesInfo = $this->getFilesExtensions();
        $fileStats = $this->getFilesSize('C:/Code/wedDevBack/files/var/uploadedFiles');

        return $this->twig->render($response, '/stats.template.html', [
            'filecount' => $fileStats['count'],
            'totalsize' => $this->formatSize($fileStats['size']),
            'fileExtensions' => array_keys($filesInfo),
            'fileCount' => array_values($filesInfo),
        ]);
    }


    private function getFilesSize($path)
    {
        $total_size = 0;
        $fileCount = 0;

        if (!is_dir($path)) {
            return ['size' => $total_size, 'count' => $fileCount];
        }

        $files = scandir($path);

        foreach ($files as $t) {
            if ($t === '.' || $t === '..') {
                continue;
            }
            $fullPath = rtrim($path, '/') . '/' . $t;
            if (is_dir($fullPath)) {
                $result = $this->getFilesSize($fullPath);
                $total_size += $result['size'];
                $fileCount += $result['count'];
            } else {
                $size = filesize($fullPath);
                if ($size !== false) {
                    $fileCount++;
                    $total_size += $size;
                }
            }
        }
        return ['size' => $total_size, 'count' => $fileCount];
    }


    private function formatSize($bytes, $force_unit = null, $format = null)
    {
        $format = ($format === null) ? '%01.2f %s' : (string) $format;
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        $mod   = 1024;
        if (($power = array_search((string) $force_unit, $units)) === false) {
            $power = ($bytes > 0) ? floor(log($bytes, $mod)) : 0;
        }
        return sprintf($format, $bytes / pow($mod, $power), $units[$power]);
    }

    private function getFilesExtensions()
    {
        $filesTable = new FilesTable($GLOBALS['link']);
        $fileExtensionsArr = $filesTable->getFilesExtensions();
        $extensionsCount = [];
        foreach ($fileExtensionsArr as $file) {
            if (isset($file['file_extension'])) {
                $extension = $file['file_extension'];
                if (isset($extensionsCount[$extension])) {
                    $extensionsCount[$extension]++;
                } else {
                    $extensionsCount[$extension] = 1;
                }
            }
        }
        return $extensionsCount;
    }
}
