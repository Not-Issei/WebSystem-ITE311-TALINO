# Parse Error Prevention Guide

## 🚨 What Causes Parse Errors?

Parse errors in PHP typically occur due to:
- **Git merge conflicts** (most common cause in this project)
- Missing semicolons, brackets, or quotes
- Incorrect PHP opening/closing tags
- Invalid syntax or typos

## 🔧 Tools Created for This Project

### 1. Syntax Checker (`check_syntax.php`)
Checks all PHP files for syntax errors:
```bash
php check_syntax.php
```

### 2. Project Validator (`validate_project.php`)
Comprehensive validation including syntax, merge conflicts, and required files:
```bash
php validate_project.php
```

### 3. Git Pre-commit Hook
Automatically prevents commits with syntax errors (located in `.git/hooks/pre-commit`)

## 🛠️ How to Fix Parse Errors

### Git Merge Conflicts
If you see markers like `<<<<<<< HEAD`, `=======`, or `>>>>>>>`:

1. **Identify the conflict sections**
2. **Choose which version to keep** (or merge both)
3. **Remove all conflict markers**
4. **Test the file** with `php -l filename.php`

### Syntax Errors
1. **Check the error message** - it usually points to the exact line
2. **Look for missing semicolons, brackets, or quotes**
3. **Validate with** `php -l filename.php`

## 🔍 Quick Commands

### Check single file syntax:
```bash
php -l path/to/file.php
```

### Check all project files:
```bash
php check_syntax.php
```

### Full project validation:
```bash
php validate_project.php
```

## 🚀 Best Practices

1. **Always run syntax check before committing**
2. **Resolve merge conflicts immediately**
3. **Use an IDE with PHP syntax highlighting**
4. **Test your code after making changes**
5. **Keep backups of working code**

## 🆘 Emergency Fix

If you encounter a parse error:

1. **Don't panic!**
2. **Check the error message for the file and line number**
3. **Run the project validator**: `php validate_project.php`
4. **Fix the identified issues**
5. **Verify with syntax checker**: `php check_syntax.php`

## 📞 Need Help?

If you're still having issues:
1. Check this guide first
2. Run the validation tools
3. Look at the Git history for recent changes
4. Ask for help with the specific error message

---
*Last updated: 2025-10-04*
