<?php

namespace App\Repository;


use App\Entity\File;
use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FileRepository extends ServiceEntityRepository
{


    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, File::class);
    }

    /**
     * @param Orders $order
     * @param $path
     * @param $filename
     * @return File
     */
   public function createBlobFileEntity(Orders $order, $path, $filename)
   {
       $file = new File();
       $file->setOrder($order);
       $file->setPath($path);
       $file->setFilename($filename);
       return $file;
   }
}
