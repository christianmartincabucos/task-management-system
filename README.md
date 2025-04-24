# Task Management System

A full-stack task management system built with Laravel and Vue 3.

## Features

- User authentication with Laravel Sanctum
- Task management with CRUD operations
- Task filtering and searching
- Drag-and-drop task reordering
- Real-time updates with WebSockets
- Admin dashboard
- Responsive design with TailwindCSS

## Running with Docker

The easiest way to run the application is using Docker:

1. Clone the repository:
   \`\`\`bash
   git clone https://github.com/yourusername/task-management.git
   cd task-management
   \`\`\`

2. Copy the environment file:
   \`\`\`bash
   cp .env.example .env
   \`\`\`

3. Start the Docker containers:
   \`\`\`bash
   docker-compose up -d
   \`\`\`

4. Install backend dependencies:
   \`\`\`bash
   docker-compose exec backend composer install
   \`\`\`

5. Generate application key:
   \`\`\`bash
   docker-compose exec backend php artisan key:generate
   \`\`\`

6. Run migrations:
   \`\`\`bash
   docker-compose exec backend php artisan migrate
   \`\`\`

7. Access the application:
   - Backend API: http://localhost:8000
   - Frontend: http://localhost:5173

## Manual Setup

### Backend Setup

1. Navigate to the backend directory:
   \`\`\`bash
   cd backend
   \`\`\`

2. Install dependencies:
   \`\`\`bash
   composer install
   \`\`\`

3. Copy the environment file:
   \`\`\`bash
   cp .env.example .env
   \`\`\`

4. Generate application key:
   \`\`\`bash
   php artisan key:generate
   \`\`\`

5. Configure your database in the `.env` file:
   \`\`\`
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_management
   DB_USERNAME=root
   DB_PASSWORD=
   \`\`\`

6. Run migrations:
   \`\`\`bash
   php artisan migrate
   \`\`\`

7. Start the Laravel server:
   \`\`\`bash
   php artisan serve
   \`\`\`

8. In a separate terminal, start Laravel Echo Server:
   \`\`\`bash
   laravel-echo-server start
   \`\`\`

### Frontend Setup

1. Navigate to the frontend directory:
   \`\`\`bash
   cd frontend
   \`\`\`

2. Install dependencies:
   \`\`\`bash
   npm install
   \`\`\`

3. Create a `.env` file:
   \`\`\`
   VITE_API_URL=http://localhost:8000
   VITE_PUSHER_APP_KEY=your_pusher_key
   VITE_PUSHER_APP_CLUSTER=your_pusher_cluster
   \`\`\`

4. Start the development server:
   \`\`\`bash
   npm run dev
   \`\`\`

## Testing

Run the backend tests:

\`\`\`bash
cd backend
php artisan test
\`\`\`

With Docker:
\`\`\`bash
docker-compose exec backend php artisan test
\`\`\`

## API Documentation

The API documentation is available as a Postman collection in the `backend/postman_collection.json` file. Import this file into Postman to explore the API endpoints.

## Scheduled Tasks

The application includes a scheduled task to clean up old tasks. To set up the cron job, add the following to your server's crontab:

\`\`\`
* * * * * cd /path-to-your-project/backend && php artisan schedule:run >> /dev/null 2>&1
\`\`\`

With Docker, the scheduler is already configured to run automatically.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
\`\`\`

## Updating Backend .env.example

Let's update the backend `.env.example` file to work with Docker:
