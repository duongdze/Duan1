<?php
require_once 'models/BaseModel.php';

class TourCategory extends BaseModel
{
    protected $table = 'tour_categories';
    protected $columns = [
        'id',
        'name',
        'slug',
        'description',
        'icon',
        'type',
        'parent_id',
        'sort_order',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all active categories with type filter
     */
    public function getActiveCategories($type = null)
    {
        $where = 'is_active = 1';
        $params = [];

        if ($type) {
            $where .= ' AND type = :type';
            $params['type'] = $type;
        }

        return $this->select('*', $where . ' ORDER BY sort_order ASC, name ASC', $params);
    }

    /**
     * Get categories by type
     */
    public function getByType($type)
    {
        return $this->select('*', 'type = :type ORDER BY sort_order ASC, name ASC', ['type' => $type]);
    }

    /**
     * Get parent categories
     */
    public function getParentCategories()
    {
        return $this->select('*', 'parent_id IS NULL OR parent_id = 0 ORDER BY sort_order ASC, name ASC');
    }

    /**
     * Get child categories by parent ID
     */
    public function getChildCategories($parentId)
    {
        return $this->select('*', 'parent_id = :parent_id ORDER BY sort_order ASC, name ASC', ['parent_id' => $parentId]);
    }

    /**
     * Get category with tour count
     */
    public function getCategoryWithTourCount($categoryId)
    {
        $sql = "SELECT tc.*, COUNT(t.id) as tour_count 
                FROM {$this->table} tc 
                LEFT JOIN tours t ON tc.id = t.category_id 
                WHERE tc.id = :id 
                GROUP BY tc.id";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['id' => $categoryId]);
        return $stmt->fetch();
    }

    /**
     * Create slug from name
     */
    public function createSlug($name, $id = null)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));

        // Check if slug exists
        $where = 'slug = :slug';
        $params = ['slug' => $slug];

        if ($id) {
            $where .= ' AND id != :id';
            $params['id'] = $id;
        }

        $existing = $this->select('id', $where, $params);

        if (!empty($existing)) {
            $slug .= '-' . time();
        }

        return $slug;
    }

    /**
     * Toggle category status
     */
    public function toggleStatus($id)
    {
        $category = $this->findById($id);
        if (!$category) {
            return false;
        }

        $newStatus = $category['is_active'] ? 0 : 1;
        return $this->update(['is_active' => $newStatus, 'updated_at' => date('Y-m-d H:i:s')], 'id = :id', ['id' => $id]);
    }

    /**
     * Get category tree structure
     */
    public function getCategoryTree()
    {
        $categories = $this->select('*', 'is_active = 1 ORDER BY sort_order ASC, name ASC');
        $tree = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == null || $category['parent_id'] == 0) {
                $category['children'] = [];
                $tree[$category['id']] = $category;
            }
        }

        foreach ($categories as $category) {
            if ($category['parent_id'] != null && $category['parent_id'] != 0 && isset($tree[$category['parent_id']])) {
                $tree[$category['parent_id']]['children'][] = $category;
            }
        }

        return array_values($tree);
    }

    /**
     * Find category by ID
     */
    public function findById($id)
    {
        return $this->find('*', 'id = :id', ['id' => $id]);
    }

    /**
     * Find category by slug
     */
    public function findBySlug($slug)
    {
        return $this->find('*', 'slug = :slug', ['slug' => $slug]);
    }

    /**
     * Update category by ID
     */
    public function updateById($id, $data)
    {
        // Auto-generate slug if not provided
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = $this->createSlug($data['name'], $id);
        }

        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->update($data, 'id = :id', ['id' => $id]);
    }

    /**
     * Insert new category
     */
    public function insertCategory($data)
    {
        // Auto-generate slug if not provided
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = $this->createSlug($data['name']);
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['is_active'] = $data['is_active'] ?? 1;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $this->insert($data);
    }

    /**
     * Delete category (check if it has tours or children)
     */
    public function deleteCategory($id)
    {
        // Check if category has tours
        require_once 'models/Tour.php';
        $tourModel = new Tour();
        $tours = $tourModel->select('id', 'category_id = :category_id', ['category_id' => $id]);

        if (!empty($tours)) {
            throw new Exception('Không thể xóa danh mục này vì đang có tour sử dụng.');
        }

        // Check if category has children
        $children = $this->getChildCategories($id);
        if (!empty($children)) {
            throw new Exception('Không thể xóa danh mục này vì có danh mục con.');
        }

        return $this->delete('id = :id', ['id' => $id]);
    }
}
