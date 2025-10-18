<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['title', 'content', 'created_at'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'title' => 'required|max_length[255]',
        'content' => 'required',
    ];
    protected $validationMessages = [
        'title' => [
            'required' => 'Announcement title is required.',
            'max_length' => 'Title cannot exceed 255 characters.'
        ],
        'content' => [
            'required' => 'Announcement content is required.'
        ]
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get all announcements ordered by created_at descending (newest first)
     *
     * @return array
     */
    public function getAllAnnouncements()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Get announcements with pagination
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAnnouncementsPaginated($limit = 10, $offset = 0)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit, $offset)
                    ->findAll();
    }

    /**
     * Get recent announcements
     *
     * @param int $limit
     * @return array
     */
    public function getRecentAnnouncements($limit = 5)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
