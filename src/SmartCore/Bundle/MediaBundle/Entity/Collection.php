<?php

namespace SmartCore\Bundle\MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\MediaBundle\Entity\Storage;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_collections")
 */
class Collection
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=128)
     *
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(type="array")
     *
     * @var array
     */
    protected $params;

    /**
     * @var Storage
     *
     * @ORM\ManyToOne(targetEntity="Storage", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $default_storage;

    /**
     * Относительный путь можно менять, только если нету файлов в коллекции
     * либо предусмотреть как-то переименовывание пути в провайдере.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $relative_path;

    /**
     * Маска имени файла. Если пустая строка, то использовать оригинальное имя файла,
     * совместимое с вебформатом т.е. без пробелов и русских букв.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $filename_pattern;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $file_relative_path_pattern;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $created_at;

    /**
     * @var File[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="File", mappedBy="collection")
     */
    protected $files;

    /**
     * Constructor.
     *
     * @param string $relativePath
     */
    public function __construct($relativePath = '')
    {
        $this->created_at       = new \DateTime();
        $this->files            = new ArrayCollection();
        $this->relative_path    = $relativePath;

        $this->filename_pattern            = '{hour}_{minutes}_{rand(10)}';
        $this->file_relative_path_pattern  = '{year}/{month}/{day}/';
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param Storage $default_storage
     * @return $this
     */
    public function setDefaultStorage(Storage $default_storage)
    {
        $this->default_storage = $default_storage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultStorage()
    {
        return $this->default_storage;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;
    
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $file_relative_path_pattern
     * @return $this
     */
    public function setFileRelativePathPattern($file_relative_path_pattern)
    {
        $this->file_relative_path_pattern = $file_relative_path_pattern;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileRelativePathPattern()
    {
        return $this->file_relative_path_pattern;
    }

    /**
     * @param string $filename_pattern
     * @return $this
     */
    public function setFilenamePattern($filename_pattern)
    {
        $this->filename_pattern = $filename_pattern;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilenamePattern()
    {
        return $this->filename_pattern;
    }

    /**
     * @param mixed $files
     * @return $this
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string $relative_path
     * @return $this
     */
    public function setRelativePath($relative_path)
    {
        $this->relative_path = $relative_path;

        return $this;
    }

    /**
     * @return string
     */
    public function getRelativePath()
    {
        return $this->relative_path;
    }
}
