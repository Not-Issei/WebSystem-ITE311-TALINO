<?php

namespace App\Controllers;

use App\Models\MaterialModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use CodeIgniter\HTTP\ResponseInterface;

class Materials extends BaseController
{
    protected $materialModel;
    protected $courseModel;
    protected $enrollmentModel;

    public function __construct()
    {
        $this->materialModel = new MaterialModel();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        
        // Load form helper
        helper(['form', 'url']);
    }

    /**
     * Display upload form and handle file upload
     *
     * @param int $course_id
     * @return mixed
     */
    public function upload($course_id)
    {
        // Check if user is logged in and has permission
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please log in to access this page.');
        }

        $user_role = session()->get('role');
        if (!in_array($user_role, ['admin', 'teacher'])) {
            return redirect()->to('/dashboard')->with('error', 'You do not have permission to upload materials.');
        }

        // Get course information
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            return redirect()->to('/admin/courses')->with('error', 'Course not found.');
        }

        // Handle POST request (file upload)
        if ($this->request->getMethod() === 'POST') {
            return $this->handleFileUpload($course_id);
        }

        // Display upload form
        $data = [
            'title' => 'Upload Material - ' . $course['course_name'],
            'course' => $course,
            'materials' => $this->materialModel->getMaterialsByCourse($course_id)
        ];

        return view('materials/upload', $data);
    }

    /**
     * Handle file upload process
     *
     * @param int $course_id
     * @return mixed
     */
    private function handleFileUpload($course_id)
    {
        $validationRules = [
            'material_file' => [
                'rules' => 'uploaded[material_file]|max_size[material_file,10240]|ext_in[material_file,pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,rar]',
                'errors' => [
                    'uploaded' => 'Please select a file to upload.',
                    'max_size' => 'File size cannot exceed 10MB.',
                    'ext_in' => 'Only PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT, ZIP, and RAR files are allowed.'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('material_file');
        
        if ($file->isValid() && !$file->hasMoved()) {
            // Create upload directory if it doesn't exist
            $uploadPath = WRITEPATH . 'uploads/materials/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $newName = $file->getRandomName();
            
            try {
                // Move file to upload directory
                $file->move($uploadPath, $newName);

                // Prepare data for database
                $materialData = [
                    'course_id' => $course_id,
                    'file_name' => $file->getClientName(),
                    'file_path' => $uploadPath . $newName,
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getClientMimeType(),
                    'uploaded_by' => session()->get('user_id')
                ];

                // Save to database
                if ($this->materialModel->insertMaterial($materialData)) {
                    return redirect()->to('/admin/course/' . $course_id . '/upload')
                                   ->with('success', 'Material uploaded successfully!');
                } else {
                    // Delete uploaded file if database insert fails
                    unlink($uploadPath . $newName);
                    return redirect()->back()->with('error', 'Failed to save material information.');
                }

            } catch (\Exception $e) {
                log_message('error', 'File upload error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to upload file. Please try again.');
            }
        }

        return redirect()->back()->with('error', 'Invalid file or file upload failed.');
    }

    /**
     * Delete a material
     *
     * @param int $material_id
     * @return mixed
     */
    public function delete($material_id)
    {
        // Check if user is logged in and has permission
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please log in to access this page.');
        }

        $user_role = session()->get('role');
        if (!in_array($user_role, ['admin', 'teacher'])) {
            return redirect()->to('/dashboard')->with('error', 'You do not have permission to delete materials.');
        }

        // Get material information
        $material = $this->materialModel->getMaterialById($material_id);
        if (!$material) {
            return redirect()->back()->with('error', 'Material not found.');
        }

        // Check if user has permission to delete this material
        if ($user_role === 'teacher' && $material['uploaded_by'] != session()->get('user_id')) {
            return redirect()->back()->with('error', 'You can only delete materials you uploaded.');
        }

        // Delete material from database and get file path
        $file_path = $this->materialModel->deleteMaterial($material_id);
        
        if ($file_path) {
            // Delete physical file
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            return redirect()->to('/admin/course/' . $material['course_id'] . '/upload')
                           ->with('success', 'Material deleted successfully!');
        }

        return redirect()->back()->with('error', 'Failed to delete material.');
    }

    /**
     * Handle file download
     *
     * @param int $material_id
     * @return mixed
     */
    public function download($material_id)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please log in to access this page.');
        }

        // Get material information
        $material = $this->materialModel->getMaterialById($material_id);
        if (!$material) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Material not found.');
        }

        $user_role = session()->get('role');
        $user_id = session()->get('user_id');

        // Check permissions
        if ($user_role === 'student') {
            // Students can only download materials from courses they're enrolled in
            if (!$this->materialModel->isStudentEnrolledInMaterialCourse($material_id, $user_id)) {
                return redirect()->to('/student/dashboard')
                               ->with('error', 'You are not enrolled in this course.');
            }
        } elseif ($user_role === 'teacher') {
            // Teachers can download materials from their courses or materials they uploaded
            if ($material['uploaded_by'] != $user_id) {
                // Check if teacher is assigned to this course (you may need to implement this check)
                // For now, allow all teachers to download
            }
        }
        // Admins can download any material

        // Check if file exists
        if (!file_exists($material['file_path'])) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        // Force download
        return $this->response->download($material['file_path'], null)
                             ->setFileName($material['file_name']);
    }

    /**
     * Display materials for a specific course (for students)
     *
     * @param int $course_id
     * @return mixed
     */
    public function viewCourse($course_id)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please log in to access this page.');
        }

        $user_id = session()->get('user_id');
        $user_role = session()->get('role');

        // Get course information
        $course = $this->courseModel->find($course_id);
        if (!$course) {
            return redirect()->to('/student/dashboard')->with('error', 'Course not found.');
        }

        // Check if student is enrolled (for students only)
        if ($user_role === 'student') {
            $enrollment = $this->enrollmentModel->where('student_id', $user_id)
                                               ->where('course_id', $course_id)
                                               ->where('status', 'enrolled')
                                               ->first();
            if (!$enrollment) {
                return redirect()->to('/student/dashboard')
                               ->with('error', 'You are not enrolled in this course.');
            }
        }

        // Get materials for this course
        $materials = $this->materialModel->getMaterialsByCourse($course_id);

        $data = [
            'title' => 'Course Materials - ' . $course['course_name'],
            'course' => $course,
            'materials' => $materials
        ];

        return view('materials/course_materials', $data);
    }

    /**
     * Display all materials for enrolled courses (student dashboard)
     *
     * @return mixed
     */
    public function studentMaterials()
    {
        // Check if user is logged in as student
        if (!session()->get('logged_in') || session()->get('role') !== 'student') {
            return redirect()->to('/login')->with('error', 'Access denied.');
        }

        $student_id = session()->get('user_id');
        
        // Get materials for all enrolled courses
        $materials = $this->materialModel->getMaterialsForStudent($student_id);

        $data = [
            'title' => 'My Course Materials',
            'materials' => $materials
        ];

        return view('materials/student_materials', $data);
    }

    /**
     * Simple test method for debugging
     */
    public function test()
    {
        // Simple test without authentication
        $course = [
            'id' => 1,
            'course_name' => 'Test Course',
            'course_code' => 'TEST101'
        ];
        
        $data = [
            'title' => 'Test Upload Material',
            'course' => $course,
            'materials' => []
        ];

        return view('materials/upload', $data);
    }
}
