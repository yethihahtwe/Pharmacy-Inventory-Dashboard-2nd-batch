# Pharmacy Inventory Management System

A Laravel-based pharmacy inventory management application built with Filament PHP, created as part of a technical training program for healthcare organizations. This project demonstrates practical implementation of web development concepts using modern tools and frameworks.

## 🎯 Project Overview

This application helps manage pharmacy inventory with features including:
- Stock management (inflow/outflow tracking)
- Batch and expiry date monitoring
- Donor contribution tracking
- Multi-warehouse distribution management
- Dashboard with data visualization
- Automated expiry alerts

## 🛠 Built With

- [Laravel](https://laravel.com/) - PHP Framework
- [Filament](https://filamentphp.com/) - Admin Panel Builder
- [MySQL](https://www.mysql.com/) - Database
- [Tailwind CSS](https://tailwindcss.com/) - Styling
- [Chart.js](https://www.chartjs.org/) - Data Visualization

## 🚀 Features

1. **Inventory Management**
   - Track medicine inflow/outflow
   - Manage multiple batches
   - Monitor expiry dates
   - Calculate current stock levels

2. **Warehouse Management**
   - Multi-warehouse support
   - Track inter-warehouse transfers
   - Distribution statistics

3. **Donor Management**
   - Track donations by source
   - Donor contribution analytics
   - Historical donation records

4. **Dashboard & Analytics**
   - Real-time stock levels
   - Expiry alerts
   - Distribution patterns
   - Donor contribution charts

## ⚙️ Installation

1. Clone the repository
```bash
git clone [repository-url]
cd pharmacy-training-app
```

2. Install dependencies
```bash
composer install
npm install
```

3. Create environment file
```bash
cp .env.example .env
```

4. Configure your database in `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pharmacy_training_app
DB_USERNAME=root
DB_PASSWORD=
```

5. Generate application key
```bash
php artisan key:generate
```

6. Run migrations and seeders
```bash
php artisan migrate
php artisan db:seed
```

7. Build assets
```bash
npm run dev
```

8. Start the development server
```bash
php artisan serve
```

## 📖 Training Context

This project was developed as part of a 3-day technical training program for healthcare organizations. The training covered:
- Modern web development practices
- Laravel framework fundamentals
- Database design principles
- CRUD operations
- Admin panel development with Filament
- Data visualization techniques

The project structure and implementation reflect real-world development practices while maintaining accessibility for learning purposes.

## 👥 Target Audience

- Healthcare Organizations
- NGO Technical Staff
- Web Developers in Healthcare Sector
- IT Training Programs

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](issues-url).

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

- Participants who contributed to the project's development through their feedback and engagement

## 📞 Contact

For questions and support, please contact me via email(yethihahtwe319@gmail[dot]com).

---
*This project was created as an educational resource and can be modified to suit specific organizational needs.*
