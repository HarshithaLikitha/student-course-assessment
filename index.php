<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Assessment System - Modern Dashboard</title>
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
            <div style="color: var(--text-secondary); font-size: 0.875rem;">
                Academic Year 2024-2025
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Hero Section -->
        <section class="hero">
            <h1>Course Assessment System</h1>
            <p>Comprehensive platform for managing student assessments, tracking performance, and generating detailed reports for academic excellence.</p>
        </section>

        <!-- Navigation Grid -->
        <div class="nav-grid">
            <a href="form.php" class="nav-card">
                <div class="nav-card-icon">➕</div>
                <h3>Enter Student Marks</h3>
                <p>Add new student records with lab internal and end exam marks. Quick and easy data entry with validation.</p>
            </a>

            <a href="display.php" class="nav-card">
                <div class="nav-card-icon">📋</div>
                <h3>View Student Records</h3>
                <p>Browse and search through all student records. View comprehensive data in an organized table format.</p>
            </a>

            <a href="performance.php" class="nav-card">
                <div class="nav-card-icon">📊</div>
                <h3>Direct CO Attainment</h3>
                <p>Analyze course outcome attainment based on direct assessment methods and performance thresholds.</p>
            </a>

            <a href="feedback.php" class="nav-card">
                <div class="nav-card-icon">🗣️</div>
                <h3>Student Feedback</h3>
                <p>Collect and analyze indirect assessment data through student feedback and course evaluations.</p>
            </a>

            <a href="final_attainment.php" class="nav-card">
                <div class="nav-card-icon">🎯</div>
                <h3>Final CO Attainment</h3>
                <p>Calculate comprehensive course outcome attainment combining direct and indirect assessment methods.</p>
            </a>

            <a href="co_po_mapping.php" class="nav-card">
                <div class="nav-card-icon">🔗</div>
                <h3>CO-PO Mapping</h3>
                <p>Define and manage the mapping between Course Outcomes and Program Outcomes for curriculum alignment.</p>
            </a>

            <a href="po_attainment.php" class="nav-card">
                <div class="nav-card-icon">📐</div>
                <h3>PO/PSO Attainment</h3>
                <p>Calculate Program Outcome and Program Specific Outcome attainment levels based on CO achievements.</p>
            </a>

            <a href="assessment_summary.php" class="nav-card">
                <div class="nav-card-icon">📄</div>
                <h3>Assessment Summary</h3>
                <p>Generate comprehensive reports and summaries of all assessment data and attainment calculations.</p>
            </a>
        </div>

        <!-- Quick Stats Section -->
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">System Overview</h2>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div style="text-align: center; padding: 1.5rem; background: var(--bg-secondary); border-radius: var(--radius-lg);">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--primary-color); margin-bottom: 0.5rem;">
                        <?php
                        include 'db_connect.php';
                        $result = $conn->query("SELECT COUNT(*) as count FROM students");
                        $count = $result ? $result->fetch_assoc()['count'] : 0;
                        echo $count;
                        $conn->close();
                        ?>
                    </div>
                    <div style="color: var(--text-secondary); font-weight: 500;">Total Students</div>
                </div>
                
                <div style="text-align: center; padding: 1.5rem; background: var(--bg-secondary); border-radius: var(--radius-lg);">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--accent-color); margin-bottom: 0.5rem;">5</div>
                    <div style="color: var(--text-secondary); font-weight: 500;">Course Outcomes</div>
                </div>
                
                <div style="text-align: center; padding: 1.5rem; background: var(--bg-secondary); border-radius: var(--radius-lg);">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--warning-color); margin-bottom: 0.5rem;">8</div>
                    <div style="color: var(--text-secondary); font-weight: 500;">Assessment Modules</div>
                </div>
                
                <div style="text-align: center; padding: 1.5rem; background: var(--bg-secondary); border-radius: var(--radius-lg);">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--success-color); margin-bottom: 0.5rem;">100%</div>
                    <div style="color: var(--text-secondary); font-weight: 500;">System Uptime</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p><strong>Developed by Team <i>Rocking Girls Of CSIT</i></strong></p>
        <p>Modern Course Assessment System • Built with PHP & Modern Web Technologies</p>
    </footer>

    <script src="assets/js/modern-interactions.js"></script>
</body>
</html>

