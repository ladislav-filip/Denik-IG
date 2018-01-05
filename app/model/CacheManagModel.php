<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/5/2018
 * Time: 18:15
 */

namespace App\Model;

use Nette\Utils\Finder;

class CacheManagModel
{
    private $tempDir;

    public function __construct($tempDir)
    {
        $this->tempDir = $tempDir;
    }

    public function getTempDir() {
        return $this->tempDir;
    }

    public function clearAll() {
        $dir = $this->getTempDir() . '/cache';
        foreach (Finder::findDirectories()->in($dir) as $key => $file) {
            self::deleteDir($key);
        }
    }

    private static function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}