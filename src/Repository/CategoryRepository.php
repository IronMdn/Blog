<?php
/**
 * Created by Formateur
 * Date: 05/07/2017
 * Time: 16:06
 */

namespace Repository;

use Entity\Category;


/**
 * Class CategoryRepository
 * @package Repository
 */
class CategoryRepository extends RepositoryAbstract
{
    public function getTable()
    {
        return 'category';
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $dbCategories = $this->db->fetchAll('SELECT * FROM category ORDER BY id DESC');
        $categories = []; // le tableau dans lequel vont être stockées les entités Category

        foreach ($dbCategories as $dbCategory) {
            $category = $this->buildFromArray($dbCategory);

            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * @param int $id
     * @return Category|null
     */
    public function find($id)
    {
        $dbCategory = $this->db->fetchAssoc(
            'SELECT * FROM category WHERE id = :id',
            [':id' => $id]
        );

        if (!empty($dbCategory)) {
            return $this->buildFromArray($dbCategory);
        }

        return null;
    }

    public function save(Category $category)
    {
        $data = ['name' => $category->getName()];

        $where = !empty($category->getId())
            ? ['id' => $category->getId()] // modification
            : null // création
        ;

        $this->persist($data, $where);
    }

    public function delete(Category $category)
    {
        $this->db->delete('category', ['id' => $category->getId()]);
    }

    /**
     * @param array $dbCategory
     * @return Category
     */
    public function buildFromArray(array $dbCategory)
    {
        $category = new Category();

        $category
            ->setId($dbCategory['id'])
            ->setName($dbCategory['name'])
        ;

        return $category;
    }
}
