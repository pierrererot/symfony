<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 30/08/2018
 * Time: 16:51
 */

namespace App\Controller;

use App\Entity\File;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FileDownloaderController extends AbstractController
{

    const PARAMETER_FILENAME = 'filename';

    /**
     * @Route("/download/file/", name="dl_file")
     * @param RegistryInterface $doctrine
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(RegistryInterface $doctrine, Request $request)
    {
        $fileRootDir = getenv("ROOT_PATH_FILES_DIRECTORY");
        $filename = $request->query->get(self::PARAMETER_FILENAME,null);
        $file = $doctrine->getRepository(File::class)->findOneBy(["filename" => $filename]);

        return $this->file($fileRootDir.$file->getPath().$file->getFilename());
    }

    public function upload(RegistryInterface $doctrine)
    {
        //TODO A voir ?
    }
}