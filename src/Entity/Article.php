<?php
/**
 * Created by Formateur
 * Date: 06/07/2017
 * Time: 16:51
 */

namespace Entity;


/**
 * Class Article
 * @package Entity
 */
class Article
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var var string
     */
    private $title;

    /**
     * @var string
     */
    private $header;

    /**
     * @var string
     */
    private $content;

    /**
     * @var Category
     */
    private $category;

    /**
     * @var User
     */
    private $author;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return var
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param var $title
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
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param string $header
     * @return $this
     */
    public function setHeader($header)
    {
        $this->header = $header;

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
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getCategoryName()
    {
        if (!is_null($this->category)) {
            return $this->category->getName();
        }

        return '';
    }

    /**
     * @return int|null
     */
    public function getCategoryId()
    {
        if (!is_null($this->category)) {
            return $this->category->getId();
        }

        return null;
    }

    /**
     * @param Category $category
     * @return Article
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return Article
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        if (!is_null($this->author)) {
            return $this->author->getId();
        }
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        if (!is_null($this->author)) {
            return $this->author->getFullName();
        }
    }
}
