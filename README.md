# CyberDefend Academy

A database-driven cybersecurity learning platform built as my university Computing Project. Users can learn core cybersecurity topics, complete quizzes and topic tests, and track progress via a dashboard and leaderboard. An admin interface supports content management (CRUD).

## Key Features
- User authentication (register/login/logout) with session-based access control
- Topic learning pages with embedded video content
- Per-topic **Quick Quiz** (mixed MCQ + fill-in)
- Per-topic **Test** (10 questions)
- Automatic scoring and saving results to the database
- User dashboard showing progress per topic (Quiz, Test, Total)
- Leaderboard ranking users by total score
- Admin dashboard for content management (Insert/Update/Delete/View)

## Tech Stack
- PHP
- MySQL (phpMyAdmin)
- HTML/CSS (global stylesheet)
- JavaScript (client-side quiz logic; CSP-safe external scripts)

- ## How to Run (Local)
1. Install XAMPP.
2. Copy the project folder into `C:\xampp\htdocs\CyberDefend\`
3. Start Apache and MySQL in XAMPP.
4. Create a MySQL database called `cyberdefend`.
5. Import the SQL schema (see `/database/` if included).
6. Open `http://localhost/CyberDefend/` in your browser.

## Screenshots (Evidence)

### User Journey
![Home - Logged out](screenshots/home-logged-out.png)
![Register](screenshots/register.png)
![Login](screenshots/login.png)
![Home - Logged in](screenshots/home-logged-in.png)

### Learning Topics
![Topics](screenshots/topics.png)
![Topic content and video](screenshots/topic-content-video.png)

### Quizzes and Tests
![Quiz](screenshots/quiz.png)
![Quiz results](screenshots/quiz-results.png)
![Test results](screenshots/test-results.png)

### Progress Tracking and Leaderboard
![Dashboard progress](screenshots/dashboard-progress.png)
![Leaderboard](screenshots/leaderboard.png)

### Admin Interface
![Admin dashboard](screenshots/admin-dashboard.png)

### Database Proof (Persistence)
![Database structure](screenshots/db-structure.png)
![Scores saved](screenshots/db-user-scores.png)

## Notes
- All usernames shown are dummy demo accounts created for demonstration purposes.
- Sensitive configuration values (e.g., database credentials) are not included in the public repository.
