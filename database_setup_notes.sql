-- Legatura Database Setup
-- This file contains all the table creation statements for reference

-- Note: Run these manually in phpMyAdmin if tables don't exist yet
-- The occupations and valid_ids tables already have INSERT statements included

-- You can also run the seeders after setting up Laravel:
-- php artisan db:seed --class=adminUserSeeder
-- php artisan db:seed --class=contractorTypesSeeder

-- Sample contractor types to insert manually (if seeder not used):
INSERT INTO contractor_types (type_name) VALUES
('General Contractor'),
('Electrical Contractor'),
('Plumbing Contractor'),
('HVAC Contractor'),
('Roofing Contractor'),
('Landscaping Contractor'),
('Painting Contractor'),
('Carpentry Contractor'),
('Masonry Contractor'),
('Concrete Contractor'),
('Excavation Contractor'),
('Demolition Contractor'),
('Flooring Contractor'),
('Insulation Contractor'),
('Drywall Contractor');

-- Default admin user (password: Admin123@!)
-- Run via seeder: php artisan db:seed --class=adminUserSeeder
-- Or insert manually (password hash is for 'Admin123@!'):
-- INSERT INTO admin_users (username, email, password_hash, last_name, middle_name, first_name, is_active, created_at)
-- VALUES ('admin123', 'admin@gmail.com', '$2y$12$...', 'admin', 'admin', 'admin', 1, NOW());
