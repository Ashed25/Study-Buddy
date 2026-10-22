# Study Buddy

A web-based e-learning platform where tutors publish video courses, students enroll, learn and earn reward points, and an admin moderates content and tracks revenue.

Built with **PHP**, **MySQL**, **Bootstrap** and **jQuery**.

## Features

There are three roles. After login, each role goes to its own dashboard.

### Students
- Browse courses by category, or search for them.
- Enroll in courses and view the bill.
- Watch video lectures, with a progress bar for each course in **My Courses**.
- Earn **reward points**: 10 for enrolling and 10 for each completed lecture.
- **Redeem points** for free access to reward courses.

### Tutors
- Submit courses and upload video lectures.
- Track earnings. Tutors receive 40% of each course sale.
- Request withdrawals from their balance (amounts are checked against what's available).

### Admin
- Dashboard with totals: students, tutors, courses, pending reviews, categories, feedback and platform profit.
- Review pending courses: approve them, or reject them with a reason.
- Manage categories and sub-categories.
- Create, edit and delete reward courses and their point costs.
- View students, tutors and payments.

## Tech stack

| | |
|---|---|
| Backend | PHP (PDO / MySQLi) |
| Database | MySQL |
| Frontend | HTML, CSS, Bootstrap, jQuery, Font Awesome, Animate.css |
| Local server | XAMPP / WAMP (Apache + MySQL) |

## Getting started

1. Install [XAMPP](https://www.apachefriends.org) and start **Apache** and **MySQL**.
2. Clone the repo into `htdocs`:
   ```bash
   cd C:/xampp/htdocs
   git clone https://github.com/Ashed25/Study-Buddy.git
   ```
3. Create a MySQL database in phpMyAdmin. The main tables are `users`, `courses`, `categories`, `tasks` (lectures), `enrollments`, `user_task` (progress), `rewards`, `feedback` and `withdrawals`.
   Passwords are stored as bcrypt hashes, so `users.password` must be at least `VARCHAR(255)`:
   ```sql
   ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL;
   ```
4. Set your database connection details in `admin/inc/db.php`.
5. Open http://localhost/Study-Buddy.

## Project structure

```
index.php, login.php, signup.php, search.php   public pages
user/       student dashboard: courses, video player, progress, rewards
teacher/    tutor dashboard: courses, lectures, earnings, withdrawals
admin/      admin dashboard: approvals, categories, rewards, reports
inc/        shared header/footer and helpers
video/      uploaded lecture videos
css/, img/, design/, font-awesome/   styles and front-end libraries
```
