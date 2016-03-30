<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Image
 *
 * @ORM\Table(name="images")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var boolean
     *
     * @ORM\Column(name="fetched", type="boolean", nullable=true)
     */
    private $fetched;

    /**
     * @var Task
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Task")
     */
    private $task;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Image
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getFetched()
    {
        return $this->fetched;
    }

    /**
     * @param boolean $fetched
     *
     * @return Image
     */
    public function setFetched($fetched)
    {
        $this->fetched = $fetched;
        return $this;
    }

    /**
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param Task $task
     *
     * @return Image
     */
    public function setTask($task)
    {
        $this->task = $task;
        return $this;
    }

    public function getGoogleDriveFileId()
    {
        $link = trim($this->getUrl());
        $link = str_replace('https://drive.google.com/file/d/', '', $link);
        $link = str_replace('/view?usp=sharing', '', $link);

        return $link;
    }

    public function getDownloadLink()
    {
        $link = trim($this->getUrl());
        $link = str_replace('https://drive.google.com/file/d/', 'https://drive.google.com/uc?export=download&id=', $link);
        $link = str_replace('/view?usp=sharing', '', $link);

        return $link;
    }

    public function getPreviewLink()
    {
        $link = trim($this->getUrl());
        $link = str_replace('https://drive.google.com/file/d/', 'https://drive.google.com/uc?id=', $link);
        $link = str_replace('/view?usp=sharing', '', $link);

        return $link;
    }

    public function getThumbnailLink($size = 'w320')
    {
        $link = trim($this->getUrl());
        $link = str_replace('https://drive.google.com/file/d/', 'https://drive.google.com/thumbnail?authuser=0&sz=' . $size . '&id=', $link);
        $link = str_replace('/view?usp=sharing', '', $link);

        return $link;
    }
}
