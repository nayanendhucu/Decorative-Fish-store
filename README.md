# Kerala Decorative Fish Store

A live inventory PHP website for selling decorative fishes from a home/farm in Kerala.

## Features
- Admin login and dashboard
- Customer register/login and personal order history
- Add/edit/remove fish listings dynamically
- Multiple image upload and optional video upload
- Live inventory display for customers
- Cart and checkout with pickup/delivery request
- Admin approval workflow for orders
- WhatsApp contact button for quick inquiries
- MySQL database using prepared statements
- Responsive, modern UI

## Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server (or XAMPP/WAMP for local development)
- Git (for cloning the repository)

## Installation

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/nayanendhucu/fish-store.git
   cd fish-store
   ```

2. **Set Up the Environment**:
   - Install XAMPP or WAMP and start Apache + MySQL.
   - Copy the `fish-store` folder into your web server directory (`htdocs` for XAMPP).

3. **Database Setup**:
   - Open phpMyAdmin (usually at `http://localhost/phpmyadmin`).
   - Create a new database named `fish_store_db`.
   - Import the `sql/fish_store.sql` file into the database.

4. **Configuration**:
   - Copy `includes/config.example.php` to `includes/config.php`.
   - Update `includes/config.php` with your database credentials and base URL if needed.
   - Set the project URL to `http://localhost/fish-store`.

5. **Run the Application**:
   - Open your browser and navigate to `http://localhost/fish-store`.

## Default Admin Login exanple
- Username: `adminname`
- Password: `password`

> 
## Folder Structure
- `/admin` — admin panel pages
- `/includes` — common PHP config, functions, header/footer
- `/assets/css` — stylesheet
- `/assets/js` — frontend script
- `/uploads/images` — fish images
- `/uploads/videos` — fish videos
- `/sql` — database schema file

## Usage
- **Admin**: Log in to manage fish listings, view orders, and approve/reject requests.
- **Customer**: Browse fishes, add to cart, register/login to place orders.
- The system displays only fishes marked available and with stock greater than zero.
- Orders are created as `pending` until the admin approves or rejects them.

## Contributing
1. Fork the repository.
2. Create a new branch for your feature: `git checkout -b feature-name`.
3. Make your changes and commit: `git commit -m 'Add some feature'`.
4. Push to the branch: `git push origin feature-name`.
5. Open a pull request.

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Notes
- Update `includes/config.php` if your project is served from a different URL path.
- Ensure uploaded images/videos are handled securely in production.
- For production deployment, consider using environment variables for sensitive data instead of hardcoding in config.php.
