# TaskTracker

## What the App Does

TaskTracker is a web-based task management application built with Laravel. It allows users to create, edit, view, and manage their tasks efficiently. Key features include:

- User authentication and registration
- Task creation with title, description, due date, and priority
- Task editing and deletion
- Dashboard for viewing all tasks
- User profile management
- Responsive design for mobile and desktop use

## Tech Stack

- **Backend**: Laravel (PHP framework)
- **Frontend**: Blade templates, Bootstrap CSS for styling
- **Database**: MySQL (via Laravel migrations)
- **Build Tool**: Vite for asset compilation
- **Package Managers**: Composer (PHP dependencies), NPM (Node.js dependencies)
- **Authentication**: Laravel Breeze (for auth scaffolding)

## How to Run Locally

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js and NPM
- MySQL or another supported database
- XAMPP or similar for local server (since the project is in htdocs)

### Installation Steps

1. **Clone the repository** (if not already done):
   ```
   git clone <repository-url>
   cd TaskTracker
   ```

2. **Install PHP dependencies**:
   ```
   composer install
   ```

3. **Install Node.js dependencies**:
   ```
   npm install
   ```

4. **Environment Configuration**:
   - Copy `.env.example` to `.env`:
     ```
     cp .env.example .env
     ```
   - Update the `.env` file with your database credentials and other settings (e.g., APP_NAME=TaskTracker, DB_CONNECTION=mysql, etc.).

5. **Generate application key**:
   ```
   php artisan key:generate
   ```

6. **Run database migrations**:
   ```
   php artisan migrate
   ```

7. **Seed the database** (optional, for sample data):
   ```
   php artisan db:seed
   ```

8. **Build assets**:
   ```
   npm run build
   ```
   Or for development:
   ```
   npm run dev
   ```

9. **Start the development server**:
   ```
   php artisan serve
   ```
   The application will be available at `http://localhost:8000`.

### Additional Notes

- If using XAMPP, ensure Apache and MySQL are running, and place the project in `htdocs`.
- For production, configure your web server (e.g., Apache/Nginx) to point to the `public` directory.

## Possible Future Improvements

- **Notifications**: Add email or in-app notifications for task due dates.
- **Task Categories/Tags**: Allow users to categorize tasks for better organization.
- **Collaboration**: Enable sharing tasks with other users or teams.
- **API Development**: Create a REST API for mobile app integration.
- **Advanced Filtering**: Add filters for tasks by priority, due date, status, etc.
- **File Attachments**: Allow attaching files to tasks.
- **Time Tracking**: Integrate time logging for tasks.
- **Reporting**: Generate reports on task completion and productivity.
- **Dark Mode**: Implement a dark theme option.
- **Multi-language Support**: Add internationalization (i18n) for multiple languages.
