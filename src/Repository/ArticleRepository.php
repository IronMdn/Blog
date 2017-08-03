<?php
/**
 * Created by Formateur
 * Date: 06/07/2017
 * Time: 16:53
 */

namespace Repository;

use Entity\Article;
use Entity\Category;
use Entity\User;


/**
 * Class ArticleRepository
 * @package Repository
 */
class ArticleRepository extends RepositoryAbstract
{
    public function getTable()
    {
        return 'article';
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $query = <<<EOS
SELECT a.*, c.name, u.lastname, u.firstname
FROM article a
JOIN category c ON a.category_id = c.id
JOIN user u ON a.author_id = u.id
ORDER BY a.id DESC
EOS;

        $dbArticles = $this->db->fetchAll($query);
        $articles = []; // le tableau dans lequel vont être stockées les entités Article

        foreach ($dbArticles as $dbArticle) {
            $article = $this->buildFromArray($dbArticle);

            $articles[] = $article;
        }

        return $articles;
    }

    public function find($id)
    {
        $query = <<<EOS
SELECT a.*, c.name, u.lastname, u.firstname
FROM article a
JOIN category c ON a.category_id = c.id
JOIN user u ON a.author_id = u.id
WHERE a.id = :id
EOS;

        $dbArticle = $this->db->fetchAssoc(
            $query,
            [':id' => $id]
        );

        if (!empty($dbArticle)) {
            return $this->buildFromArray($dbArticle);
        }

        return null;
    }

    public function save(Article $article)
    {
        $data = [
            'title' => $article->getTitle(),
            'header' => $article->getHeader(),
            'content' => $article->getContent(),
            'category_id' => $article->getCategoryId()
        ];

        $where = !empty($article->getId())
            ? ['id' => $article->getId()] // modification
            : null // création
        ;

        $this->persist($data, $where);
    }

    public function delete(Article $article)
    {
        $this->db->delete('article', ['id' => $article->getId()]);
    }

    /**
     * @param array $dbArticle
     * @return Article
     */
    public function buildFromArray(array $dbArticle)
    {
        $article = new Article();

        $category = new Category();

        $category
            ->setId($dbArticle['category_id'])
            ->setName($dbArticle['name'])
        ;

        $author = new User();

        $author
            ->setId($dbArticle['author_id'])
            ->setLastname($dbArticle['lastname'])
            ->setFirstname($dbArticle['firstname'])
        ;

        $article
            ->setId($dbArticle['id'])
            ->setTitle($dbArticle['title'])
            ->setHeader($dbArticle['header'])
            ->setContent($dbArticle['content'])
            ->setCategory($category)
            ->setAuthor($author)
        ;

        return $article;
    }
}
