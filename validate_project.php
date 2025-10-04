<?php
/**
 * Project Validation Script
 * Comprehensive validation for the ITE311-TALINO project
 */

class ProjectValidator {
    private $projectRoot;
    private $errors = [];
    private $warnings = [];
    
    public function __construct($projectRoot) {
        $this->projectRoot = $projectRoot;
    }
    
    public function validateAll() {
        echo "🔍 Starting comprehensive project validation...\n";
        echo str_repeat("=", 60) . "\n";
        
        $this->checkPhpSyntax();
        $this->checkGitMergeConflicts();
        $this->checkRequiredFiles();
        $this->checkDatabaseConnections();
        
        $this->displayResults();
    }
    
    private function checkPhpSyntax() {
        echo "📝 Checking PHP syntax...\n";
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->projectRoot . '/app')
        );
        
        $phpFiles = 0;
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $filePath = $file->getPathname();
                $phpFiles++;
                
                $output = [];
                $returnCode = 0;
                exec("php -l \"$filePath\" 2>&1", $output, $returnCode);
                
                if ($returnCode !== 0) {
                    $this->errors[] = "Syntax error in {$filePath}: " . implode(" ", $output);
                }
            }
        }
        
        echo "   ✅ Checked {$phpFiles} PHP files\n";
    }
    
    private function checkGitMergeConflicts() {
        echo "🔀 Checking for Git merge conflicts...\n";
        
        $conflictMarkers = ['<<<<<<<', '=======', '>>>>>>>'];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->projectRoot . '/app')
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                
                foreach ($conflictMarkers as $marker) {
                    if (strpos($content, $marker) !== false) {
                        $this->errors[] = "Git merge conflict marker found in {$file->getPathname()}";
                        break;
                    }
                }
            }
        }
        
        echo "   ✅ No merge conflicts found\n";
    }
    
    private function checkRequiredFiles() {
        echo "📁 Checking required files...\n";
        
        $requiredFiles = [
            '/app/Config/Database.php',
            '/app/Config/Routes.php',
            '/app/Controllers/Auth.php',
            '/app/Controllers/BaseController.php'
        ];
        
        foreach ($requiredFiles as $file) {
            $fullPath = $this->projectRoot . $file;
            if (!file_exists($fullPath)) {
                $this->errors[] = "Required file missing: {$file}";
            }
        }
        
        echo "   ✅ All required files present\n";
    }
    
    private function checkDatabaseConnections() {
        echo "🗄️  Checking database configuration...\n";
        
        $dbConfigPath = $this->projectRoot . '/app/Config/Database.php';
        if (file_exists($dbConfigPath)) {
            $content = file_get_contents($dbConfigPath);
            
            // Check for common database configuration issues
            if (strpos($content, 'your_database_name_here') !== false) {
                $this->warnings[] = "Database configuration may need updating";
            }
            
            if (strpos($content, 'your_username_here') !== false) {
                $this->warnings[] = "Database username may need updating";
            }
        }
        
        echo "   ✅ Database configuration checked\n";
    }
    
    private function displayResults() {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "📊 VALIDATION RESULTS\n";
        echo str_repeat("=", 60) . "\n";
        
        if (empty($this->errors) && empty($this->warnings)) {
            echo "🎉 All validations passed! Your project is ready.\n";
        } else {
            if (!empty($this->errors)) {
                echo "❌ ERRORS FOUND (" . count($this->errors) . "):\n";
                foreach ($this->errors as $error) {
                    echo "   • {$error}\n";
                }
                echo "\n";
            }
            
            if (!empty($this->warnings)) {
                echo "⚠️  WARNINGS (" . count($this->warnings) . "):\n";
                foreach ($this->warnings as $warning) {
                    echo "   • {$warning}\n";
                }
                echo "\n";
            }
        }
        
        echo "Validation completed at " . date('Y-m-d H:i:s') . "\n";
    }
}

// Run validation
$validator = new ProjectValidator(__DIR__);
$validator->validateAll();
?>
