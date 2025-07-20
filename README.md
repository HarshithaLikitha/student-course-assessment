# 📘 Modern Course Assessment System

A complete web-based solution for tracking, analyzing, and reporting student performance and outcome attainment — built using **PHP**, **MySQL (XAMPP)**, and **modern front-end styling**.

---

## 🚀 Features

- 🔢 Enter & manage student marks (Lab Internals & End Exams)
- 📊 Automatic Course Outcome (CO) attainment analysis
- 🗣️ Indirect attainment via student feedback
- 🎯 Final CO attainment combining direct & indirect metrics
- 🔗 CO to PO/PSO mapping with weighted calculations
- 📄 Assessment summary with export-ready results
- 👨‍🏫 Course information (faculty, subject, academic year, etc.)

---

## 📁 Project Structure

📁 student-course-assessment/
├── assets/ # CSS and JS assets
├── database.sql # SQL dump for setting up DB schema
├── db_connect.php # DB connection file (use your local credentials)
├── index.php # Main dashboard with navigation
├── form.php # Enter student marks
├── display.php # Display student records
├── performance.php # Direct CO calculation
├── feedback.php # Indirect feedback form
├── final_attainment.php # Combines direct + indirect scores
├── co_po_mapping.php # Map COs to POs/PSOs
├── po_attainment.php # Calculate PO/PSO scores
├── assessment_summary.php # Full report summary
├── report.php # Printable version of the report


---

## 🛠️ Technologies Used

- **PHP 8.2**
- **MySQL** (via XAMPP)
- **HTML5 + CSS3**
- **Custom Modern CSS UI**
- **Google Fonts: Inter**
- **Apache 2.4 (XAMPP)**

---

🧑‍🏫 About the Project
This system is designed for internal use in educational institutes to:
Measure Course Outcomes (CO) through internal & external assessments
Track Program Outcomes (POs/PSOs)
Improve faculty performance through feedback mapping
Maintain accurate and structured academic records
