<?php
/**
 * Created by PhpStorm.
 * User: aprerot
 * Date: 30/08/2018
 * Time: 16:57
 */

namespace App\Entity;

use App\Annotation\ExternalUserFilterAnnotation;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class BlobFile.
 *
 * @author
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 *
 * @ExternalUserFilterAnnotation(targetFieldName="client_id")
 */
class File extends AbstractTraceableEntity
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id = null;

    /**
     * @ORM\Column(name="path", type="string")
     */
    private $path;

    /**
     * @ORM\Column(name="filename", type="string")
     */
    private $filename;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Orders", inversedBy="files")
     */
    private $order;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return File
     */
    public function setId(int $id): File
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     * @return File
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     * @return File
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Orders
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set Order and associated Client
     * @param Orders $order
     * @return File
     */
    public function setOrder(Orders $order)
    {
        $this->order = $order;
        $this->setClient($order->getClient());
        $order->addFile($this);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
}