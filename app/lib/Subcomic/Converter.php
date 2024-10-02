<?php

namespace Subcomic;

use Mockery\Exception;
use Subcomic\Archive\ArchiveFactory;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class Converter
{
    protected $archivePath;
    protected $tmpDir;
    protected $convertedDir;

    /**
     * @param int $id
     * @param string $archive_path
     * @return int  num pages
     */
    public function convert(int $id, string $archive_path)
    {
        $this->archivePath = $archive_path;
        $this->tmpDir = public_path('tmp') . '/' . $id;
        $this->convertedDir = public_path('converted') . '/' . $id;
        $this->extractArchive();
        $this->flatten();
        $this->removeNotImageFiles();
        return $this->optimizeAndNormalizeImages();
    }

    protected function extractArchive()
    {
        exec(sprintf("rm -rf '%s'", $this->tmpDir));
        mkdir($this->tmpDir);
        $ext = ArchiveFactory::getExtension($this->archivePath);
        switch ($ext) {
            case 'zip':
                $command = sprintf("unzip %s -d '%s'", escapeshellarg($this->archivePath), $this->tmpDir);
                break;
            case 'rar':
                $command = sprintf("unrar e %s '%s'", escapeshellarg($this->archivePath), $this->tmpDir);
                break;
            default:
                throw new Exception("Unknown archive ext");
        }
        exec($command);
    }

    protected function flatten()
    {
        exec(sprintf("bash -c \"cd %s && pax -Xrwls '|/|_|g' */ %s && rm -rf */\"", $this->tmpDir, $this->tmpDir));
    }

    protected function removeNotImageFiles()
    {
        $finder = (new Finder())->files()->in($this->tmpDir);
        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            if (!ImageFileNameDetector::isImage($file->getPathname())) {
                unlink($file->getPathname());
            }
        }
    }

    /**
     * @return int  num pages
     */
    protected function optimizeAndNormalizeImages()
    {
        exec(sprintf("rm -rf '%s'", $this->convertedDir));
        mkdir($this->convertedDir);
        $finder = (new Finder())->files()->in($this->tmpDir);
        $pathNames = [];
        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $pathNames []= $file->getPathname();
        }
        natcasesort($pathNames);
        $i = 0;
        foreach ($pathNames as $pathName) {
            $newPath = sprintf("%s/%04d.jpg", $this->convertedDir, $i);
            $image = new Image(ImageOptimizer::resizeRectGeneral());
            $image->read($pathName);
            $image->write($newPath);
            $i++;
        }
        exec(sprintf("rm -rf '%s'", $this->tmpDir));
        return $i;
    }
}
