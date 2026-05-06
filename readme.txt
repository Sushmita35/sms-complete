README - Student Record and Attendance Management System
Overview
This project is a web-based Student Record and Attendance Management System developed as part of a BSc (Hons) Computer Science Final Year Project at De Montfort University.

The system integrates student management, attendance monitoring, academic results management, fee tracking, and automated attendance alerts within a single platform.
System Features
• Student Management
• Attendance Management
• Automated Attendance Alerts
• Academic Results Management
• Fee Management
• Dashboard and Reporting
Technologies Used
• PHP 8.x
• MySQL 8.x
• Tailwind CSS
• JavaScript
• Chart.js
• PHPMailer
• XAMPP
• GitHub
Quick Start Guide
This README is intended to guide supervisors, lecturers, or evaluators on how to run and use the system locally.
System Requirements
• PHP 8.x or later
• MySQL 5.7 or later
• Apache Web Server
• XAMPP / WAMP / LAMP
• Modern web browser
Installation and Setup
1. Clone or download the GitHub repository.

2. Move the project folder into:
C:\xampp\htdocs\student-management-system

3. Start Apache and MySQL using XAMPP.

4. Open phpMyAdmin and import the provided SQL file:
student-management-system.sql

5. Open config/db.php and ensure the database credentials match your local setup.
Running the System
1. Open your browser.

2. Navigate to:
http://localhost/sms-complete/login.php

3. Login using the administrator credentials.

4. Use the sidebar navigation to access:
• Student Management
• Attendance Management
• Results Management
• Fee Management
• Reports and Dashboard
Email Configuration
The system uses PHPMailer with Gmail SMTP for attendance alert notifications.

Configure SMTP settings inside:
attendance/send_alert.php
Automated Attendance Alert Configuration
The attendance alert process is configured to run automatically every day at 9:45 AM using Windows Task Scheduler.

Program:
C:\xampp\php\php.exe

Script:
C:\xampp\htdocs\student-management-system\attendance\attendance_alert.php
Default Login Credentials
Username: admin
Password: admin123
Future Improvements
• Student portal
• Parent portal
• Role-based access control
• Mobile application support
• Advanced analytics
• Cloud deployment
Author
Sushmita Chhetri
BSc (Hons) Computer Science
De Montfort University

Supervisor: Bernice Bryan
