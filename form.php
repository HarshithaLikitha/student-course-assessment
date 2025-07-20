<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Student Marks - Course Assessment System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/modern-style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="index.php" class="logo">
                <div class="logo-icon">📚</div>
                <span>Course Assessment</span>
            </a>
            <a href="index.php" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
    </header>

    <!-- Main Container -->
    <div class="main-container">
        <div class="form-container">
            <div class="form-header">
                <h2>Enter Student Marks</h2>
                <p>Add new student assessment data to the system</p>
            </div>

            <form method="POST" id="studentForm">
                <div class="form-group">
                    <label class="form-label" for="reg_no">Register Number</label>
                    <input type="text" 
                           id="reg_no" 
                           name="reg_no" 
                           class="form-input" 
                           placeholder="e.g., 22KD1A0501" 
                           required
                           pattern="[0-9]{2}[A-Z]{2}[0-9]{1}[A-Z]{1}[0-9]{4}"
                           title="Format: 22KD1A0501">
                </div>

                <div class="form-group">
                    <label class="form-label" for="name">Student Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-input" 
                           placeholder="Enter full name" 
                           required
                           minlength="2">
                </div>

                <div class="form-group">
                    <label class="form-label" for="lab_internal">Lab Internal Marks</label>
                    <input type="number" 
                           id="lab_internal" 
                           name="lab_internal" 
                           class="form-input" 
                           placeholder="Out of 25" 
                           min="0" 
                           max="25" 
                           step="0.5"
                           required>
                    <small style="color: var(--text-secondary); font-size: 0.875rem;">Maximum: 25 marks</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="end_exam">End Exam Marks</label>
                    <input type="number" 
                           id="end_exam" 
                           name="end_exam" 
                           class="form-input" 
                           placeholder="Out of 50" 
                           min="0" 
                           max="50" 
                           step="0.5"
                           required>
                    <small style="color: var(--text-secondary); font-size: 0.875rem;">Maximum: 50 marks</small>
                </div>

                <button type="submit" class="btn btn-primary btn-full" data-original-text="Submit Student Data">
                    Submit Student Data
                </button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                include 'db_connect.php';

                $reg_no = trim($_POST['reg_no']);
                $name = trim($_POST['name']);
                $lab = floatval($_POST['lab_internal']);
                $end = floatval($_POST['end_exam']);

                // Validate input ranges
                if ($lab < 0 || $lab > 25) {
                    echo "<div class='alert alert-error'>
                            <span>❌ Lab internal marks must be between 0 and 25</span>
                          </div>";
                } elseif ($end < 0 || $end > 50) {
                    echo "<div class='alert alert-error'>
                            <span>❌ End exam marks must be between 0 and 50</span>
                          </div>";
                } else {
                    // Check if student already exists
                    $check_sql = "SELECT id FROM students WHERE reg_no = '$reg_no'";
                    $check_result = $conn->query($check_sql);
                    
                    if ($check_result->num_rows > 0) {
                        echo "<div class='alert alert-error'>
                                <span>❌ Student with register number $reg_no already exists</span>
                              </div>";
                    } else {
                        $sql = "INSERT INTO students (reg_no, name, lab_internal, end_exam) 
                                VALUES ('$reg_no', '$name', '$lab', '$end')";

                        if ($conn->query($sql) === TRUE) {
                            echo "<div class='alert alert-success'>
                                    <span>✅ Student data added successfully!</span>
                                  </div>";
                            echo "<script>
                                    setTimeout(() => {
                                        document.getElementById('studentForm').reset();
                                    }, 1000);
                                  </script>";
                        } else {
                            echo "<div class='alert alert-error'>
                                    <span>❌ Error: " . $conn->error . "</span>
                                  </div>";
                        }
                    }
                }

                $conn->close();
            }
            ?>

            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <h3 style="margin-bottom: 1rem; color: var(--text-primary);">Quick Actions</h3>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="display.php" class="btn btn-secondary">📋 View All Records</a>
                    <a href="performance.php" class="btn btn-secondary">📊 Check Performance</a>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="table-container" style="max-width: 500px; margin: 2rem auto;">
            <h3 style="margin-bottom: 1rem; color: var(--text-primary);">📝 Input Guidelines</h3>
            <ul style="color: var(--text-secondary); line-height: 1.8;">
                <li><strong>Register Number:</strong> Follow the format 22KD1A0501 (Year + Branch + Section + Roll)</li>
                <li><strong>Lab Internal:</strong> Enter marks out of 25 (can include decimals like 23.5)</li>
                <li><strong>End Exam:</strong> Enter marks out of 50 (can include decimals like 45.5)</li>
                <li><strong>Validation:</strong> All fields are required and will be validated automatically</li>
            </ul>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>Course Assessment System • Enter Student Data</p>
    </footer>

    <script src="assets/js/modern-interactions.js"></script>
    <script>
        // Additional form-specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const regNoInput = document.getElementById('reg_no');
            const nameInput = document.getElementById('name');
            
            // Auto-uppercase register number
            regNoInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
            
            // Auto-capitalize name
            nameInput.addEventListener('input', function() {
                this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());
            });
            
            // Calculate total marks display
            const labInput = document.getElementById('lab_internal');
            const examInput = document.getElementById('end_exam');
            
            function updateTotal() {
                const lab = parseFloat(labInput.value) || 0;
                const exam = parseFloat(examInput.value) || 0;
                const total = lab + exam;
                const percentage = ((total / 75) * 100).toFixed(1);
                
                // Remove existing total display
                const existingTotal = document.querySelector('.total-display');
                if (existingTotal) existingTotal.remove();
                
                if (lab > 0 || exam > 0) {
                    const totalDiv = document.createElement('div');
                    totalDiv.className = 'total-display';
                    totalDiv.style.cssText = `
                        margin-top: 1rem;
                        padding: 1rem;
                        background: var(--bg-secondary);
                        border-radius: var(--radius-md);
                        text-align: center;
                        border: 1px solid var(--border-color);
                    `;
                    totalDiv.innerHTML = `
                        <div style="font-size: 1.25rem; font-weight: 600; color: var(--primary-color);">
                            Total: ${total}/75 (${percentage}%)
                        </div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">
                            ${percentage >= 63 ? '✅ Above threshold (63%)' : '⚠️ Below threshold (63%)'}
                        </div>
                    `;
                    
                    examInput.parentElement.appendChild(totalDiv);
                }
            }
            
            labInput.addEventListener('input', updateTotal);
            examInput.addEventListener('input', updateTotal);
        });
    </script>
</body>
</html>

