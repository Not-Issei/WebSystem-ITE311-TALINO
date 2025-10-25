<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'course_id',
        'file_name',
        'file_path',
        'file_size',
        'file_type',
        'uploaded_by'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'course_id' => 'required|integer',
        'file_name' => 'required|max_length[255]',
        'file_path' => 'required|max_length[255]',
        'uploaded_by' => 'required|integer'
    ];
    protected $validationMessages = [];
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
     * Insert a new material record
     *
     * @param array $data
     * @return int|bool
     */
    public function insertMaterial($data)
    {
        return $this->insert($data);
    }

    /**
     * Get all materials for a specific course
     *
     * @param int $course_id
     * @return array
     */
    public function getMaterialsByCourse($course_id)
    {
        return $this->select('materials.*, users.name as uploaded_by_name, courses.course_name')
                    ->join('users', 'users.id = materials.uploaded_by', 'left')
                    ->join('courses', 'courses.id = materials.course_id', 'left')
                    ->where('materials.course_id', $course_id)
                    ->orderBy('materials.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get material by ID with course and user information
     *
     * @param int $material_id
     * @return array|null
     */
    public function getMaterialById($material_id)
    {
        return $this->select('materials.*, users.name as uploaded_by_name, courses.course_name, courses.id as course_id')
                    ->join('users', 'users.id = materials.uploaded_by', 'left')
                    ->join('courses', 'courses.id = materials.course_id', 'left')
                    ->where('materials.id', $material_id)
                    ->first();
    }

    /**
     * Get materials for courses that a student is enrolled in
     *
     * @param int $student_id
     * @return array
     */
    public function getMaterialsForStudent($student_id)
    {
        return $this->select('materials.*, users.name as uploaded_by_name, courses.course_name, courses.course_code')
                    ->join('courses', 'courses.id = materials.course_id', 'inner')
                    ->join('enrollments', 'enrollments.course_id = courses.id', 'inner')
                    ->join('users', 'users.id = materials.uploaded_by', 'left')
                    ->where('enrollments.user_id', $student_id)
                    ->where('enrollments.status', 'active')
                    ->orderBy('courses.course_name', 'ASC')
                    ->orderBy('materials.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Check if a student is enrolled in the course that contains the material
     *
     * @param int $material_id
     * @param int $student_id
     * @return bool
     */
    public function isStudentEnrolledInMaterialCourse($material_id, $student_id)
    {
        $result = $this->select('materials.id')
                       ->join('courses', 'courses.id = materials.course_id', 'inner')
                       ->join('enrollments', 'enrollments.course_id = courses.id', 'inner')
                       ->where('materials.id', $material_id)
                       ->where('enrollments.user_id', $student_id)
                       ->where('enrollments.status', 'active')
                       ->first();

        return !empty($result);
    }

    /**
     * Delete material and return file path for cleanup
     *
     * @param int $material_id
     * @return string|bool File path if successful, false if failed
     */
    public function deleteMaterial($material_id)
    {
        $material = $this->find($material_id);
        if (!$material) {
            return false;
        }

        $file_path = $material['file_path'];
        
        if ($this->delete($material_id)) {
            return $file_path;
        }

        return false;
    }

    /**
     * Get materials count by course
     *
     * @param int $course_id
     * @return int
     */
    public function getMaterialsCountByCourse($course_id)
    {
        return $this->where('course_id', $course_id)->countAllResults();
    }

    /**
     * Get recent materials (for dashboard)
     *
     * @param int $limit
     * @return array
     */
    public function getRecentMaterials($limit = 5)
    {
        return $this->select('materials.*, users.name as uploaded_by_name, courses.course_name')
                    ->join('users', 'users.id = materials.uploaded_by', 'left')
                    ->join('courses', 'courses.id = materials.course_id', 'left')
                    ->orderBy('materials.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
