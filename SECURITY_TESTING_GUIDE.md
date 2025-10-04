# Security Testing Guide for Course Enrollment System

## Overview
This guide provides comprehensive security testing procedures for the course enrollment system.

## 🔒 Security Features Implemented

### 1. **CSRF Protection**
- **Location**: All forms include `<?= csrf_field() ?>`
- **Validation**: Course controller validates CSRF tokens
- **Configuration**: Enabled in `app/Config/Security.php`

### 2. **Role-Based Access Control**
- **Student-Only Enrollment**: Only users with 'student' role can enroll
- **Session Validation**: Checks for logged-in status
- **Authorization**: Prevents unauthorized access to enrollment endpoints

### 3. **Input Validation**
- **Course ID Validation**: Numeric validation and existence checks
- **SQL Injection Prevention**: Using CodeIgniter's Query Builder
- **Data Sanitization**: Input cleaning and validation

### 4. **Session Security**
- **User ID from Session**: Uses session data, not client input
- **Session Hijacking Prevention**: Secure session management
- **Automatic Logout**: Session timeout handling

## 🧪 Security Testing Procedures

### Test 1: Authorization Bypass
**Objective**: Verify that unauthenticated users cannot enroll in courses

**Steps**:
1. **Logout** from the application
2. **Open browser developer tools** (F12)
3. **Go to Console tab**
4. **Execute the following JavaScript**:
   ```javascript
   fetch('/course/enroll', {
       method: 'POST',
       headers: {
           'Content-Type': 'application/x-www-form-urlencoded',
       },
       body: 'course_id=1&csrf_token=test'
   })
   .then(response => response.json())
   .then(data => console.log(data));
   ```

**Expected Result**: 
- Status Code: `401 Unauthorized`
- Response: `{"success": false, "message": "You must be logged in to enroll in courses."}`

### Test 2: SQL Injection Prevention
**Objective**: Verify that SQL injection attacks are prevented

**Steps**:
1. **Login as a student**
2. **Open browser developer tools**
3. **Go to Console tab**
4. **Execute malicious payload**:
   ```javascript
   // Test SQL injection in course_id parameter
   $.post('/course/enroll', {
       course_id: "1 OR 1=1",
       csrf_token: $('meta[name="csrf-token"]').attr('content')
   })
   .done(function(response) {
       console.log('Response:', response);
   })
   .fail(function(xhr) {
       console.log('Error:', xhr.responseJSON);
   });
   ```

**Expected Result**:
- Input validation should reject non-numeric course_id
- Response: `{"success": false, "message": "Invalid course ID."}`
- No SQL injection should occur

### Test 3: CSRF Protection
**Objective**: Verify CSRF protection is working

**Steps**:
1. **Login as a student**
2. **Attempt enrollment without CSRF token**:
   ```javascript
   $.post('/course/enroll', {
       course_id: 1
       // No CSRF token included
   })
   .done(function(response) {
       console.log('Unexpected success:', response);
   })
   .fail(function(xhr) {
       console.log('Expected failure:', xhr.responseJSON);
   });
   ```

**Expected Result**:
- Status Code: `400 Bad Request`
- Response: `{"success": false, "message": "Invalid security token."}`

### Test 4: Role-Based Access Control
**Objective**: Verify only students can enroll in courses

**Steps**:
1. **Login as a teacher or admin**
2. **Attempt to enroll in a course**:
   ```javascript
   $.post('/course/enroll', {
       course_id: 1,
       csrf_token: $('meta[name="csrf-token"]').attr('content')
   })
   ```

**Expected Result**:
- Status Code: `403 Forbidden`
- Response: `{"success": false, "message": "Only students can enroll in courses."}`

### Test 5: Duplicate Enrollment Prevention
**Objective**: Verify users cannot enroll in the same course twice

**Steps**:
1. **Login as a student**
2. **Enroll in a course successfully**
3. **Attempt to enroll in the same course again**

**Expected Result**:
- First enrollment: Success
- Second enrollment: `{"success": false, "message": "You are already enrolled in this course."}`

## 🛡️ Security Configuration Checklist

### CSRF Protection
- [ ] CSRF protection enabled in `app/Config/Security.php`
- [ ] CSRF tokens included in all forms
- [ ] CSRF validation in controllers

### Input Validation
- [ ] Course ID validation (numeric, exists)
- [ ] User role validation
- [ ] Session validation

### Database Security
- [ ] Using Query Builder (prevents SQL injection)
- [ ] Parameterized queries
- [ ] Input sanitization

### Session Security
- [ ] Secure session configuration
- [ ] Session timeout handling
- [ ] User ID from session, not request

## 🚨 Common Vulnerabilities to Test

### 1. **Insecure Direct Object References**
```javascript
// Try accessing other users' data
$.get('/course/details/1?user_id=999')
```

### 2. **Mass Assignment**
```javascript
// Try to set additional fields
$.post('/course/enroll', {
    course_id: 1,
    status: 'completed',
    grade: 'A+',
    csrf_token: token
})
```

### 3. **Business Logic Bypass**
```javascript
// Try to enroll in full courses
// Try to enroll in inactive courses
// Try to enroll with invalid dates
```

## 📊 Security Test Results Template

```
=== SECURITY TEST RESULTS ===

Test 1: Authorization Bypass
Status: [PASS/FAIL]
Details: [Description of results]

Test 2: SQL Injection Prevention
Status: [PASS/FAIL]
Details: [Description of results]

Test 3: CSRF Protection
Status: [PASS/FAIL]
Details: [Description of results]

Test 4: Role-Based Access Control
Status: [PASS/FAIL]
Details: [Description of results]

Test 5: Duplicate Enrollment Prevention
Status: [PASS/FAIL]
Details: [Description of results]

=== OVERALL SECURITY ASSESSMENT ===
Total Tests: 5
Passed: [X]
Failed: [Y]
Security Rating: [SECURE/NEEDS IMPROVEMENT/VULNERABLE]
```

## 🎯 Success Criteria

The system is considered secure when:
- ✅ All authorization tests pass
- ✅ SQL injection attempts are blocked
- ✅ CSRF protection is working
- ✅ Input validation is comprehensive
- ✅ Role-based access is enforced
- ✅ Business logic is protected

---

**Remember**: Security testing should be performed in a controlled environment. Never test on production systems without proper authorization.
