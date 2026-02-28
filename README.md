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

## Screenshots (Evidence)

### User Journey
![Home - Logged out](screenshots/01-home-logged-out.png)
![Register](screenshots/02-register.png)
![Login](screenshots/03-login.png)
![Home - Logged in](screenshots/04-home-logged-in.png)

### Learning Topics
![Topics](screenshots/05-topics.png)
![Topic content and video](screenshots/06-topic-content-video.png)

### Quizzes and Tests
![Quiz](screenshots/07-quiz.png)
![Quiz results](screenshots/08-quiz-results.png)
![Test results](screenshots/09-test-results.png)

### Progress Tracking and Leaderboard
![Dashboard progress](screenshots/10-dashboard-progress.png)
![Leaderboard](screenshots/11-leaderboard.png)

### Admin Interface
![Admin dashboard](screenshots/12-admin-dashboard.png)

### Database Proof (Persistence)
![Database structure](screenshots/13-db-structure.png)
![Scores saved](screenshots/14-db-user-scores.png)

## Notes
- All usernames shown are dummy demo accounts created for demonstration purposes.
- Sensitive configuration values (e.g., database credentials) are not included in the public repository.
