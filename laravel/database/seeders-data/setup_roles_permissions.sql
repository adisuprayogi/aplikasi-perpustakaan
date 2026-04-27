-- ============================================
-- COMPLETE ROLE & PERMISSION SETUP
-- Aplikasi Perpustakaan Digital
-- Run this on your server database
-- ============================================

-- Disable foreign key checks for import
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- 1. ROLES TABLE
-- ============================================
TRUNCATE TABLE `roles`;

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(2, 'branch_admin', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(3, 'circulation_staff', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(4, 'catalog_staff', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(5, 'report_viewer', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(6, 'member', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(7, 'admin', 'web', '2026-02-02 04:15:50', '2026-02-02 04:15:50');

-- ============================================
-- 2. PERMISSIONS TABLE (77 permissions)
-- ============================================
TRUNCATE TABLE `permissions`;

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard.view', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(2, 'branches.view', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(3, 'branches.create', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(4, 'branches.edit', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(5, 'branches.delete', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(6, 'users.view', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(7, 'users.create', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(8, 'users.edit', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(9, 'users.delete', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(10, 'users.manage_roles', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(11, 'members.view', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(12, 'members.create', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(13, 'members.edit', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(14, 'members.delete', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(15, 'members.register', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(16, 'members.renew', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(17, 'members.suspend', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(18, 'collections.view', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(19, 'collections.create', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(20, 'collections.edit', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(21, 'collections.delete', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(22, 'collections.import', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(23, 'collections.export', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(24, 'items.view', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(25, 'items.create', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(26, 'items.edit', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(27, 'items.delete', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(28, 'items.transfer', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(29, 'loans.view', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(30, 'loans.create', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(31, 'loans.return', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(32, 'loans.renew', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(33, 'loans.overdue', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(34, 'reservations.view', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(35, 'reservations.create', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(36, 'reservations.cancel', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(37, 'reservations.manage', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(38, 'fines.view', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(39, 'fines.waive', 'web', '2026-01-27 04:10:06', '2026-01-27 04:10:06'),
(40, 'payments.view', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(41, 'payments.create', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(42, 'payments.process', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(43, 'authors.manage', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(44, 'subjects.manage', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(45, 'publishers.manage', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(46, 'classifications.manage', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(47, 'reports.loans', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(48, 'reports.members', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(49, 'reports.collections', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(50, 'reports.fines', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(51, 'reports.circulation', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(52, 'settings.view', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(53, 'settings.edit', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(54, 'settings.manage', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(55, 'logs.view', 'web', '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(56, 'loan-rules.view', 'web', '2026-01-27 06:28:44', '2026-01-27 06:28:44'),
(57, 'loan-rules.view-any', 'web', '2026-01-27 06:28:44', '2026-01-27 06:28:44'),
(58, 'loan-rules.create', 'web', '2026-01-27 06:28:44', '2026-01-27 06:28:44'),
(59, 'loan-rules.update', 'web', '2026-01-27 06:28:44', '2026-01-27 06:28:44'),
(60, 'loan-rules.delete', 'web', '2026-01-27 06:28:44', '2026-01-27 06:28:44'),
(61, 'payments.view-any', 'web', '2026-01-27 07:14:20', '2026-01-27 07:14:20'),
(62, 'payments.waive', 'web', '2026-01-27 07:14:21', '2026-01-27 07:14:21'),
(63, 'payments.delete', 'web', '2026-01-27 07:14:21', '2026-01-27 07:14:21'),
(64, 'reservations.delete', 'web', '2026-01-28 01:58:08', '2026-01-28 01:58:08'),
(65, 'digital_files.view', 'web', '2026-01-29 02:19:46', '2026-01-29 02:19:46'),
(66, 'digital_files.create', 'web', '2026-01-29 02:19:46', '2026-01-29 02:19:46'),
(67, 'digital_files.edit', 'web', '2026-01-29 02:19:46', '2026-01-29 02:19:46'),
(68, 'digital_files.delete', 'web', '2026-01-29 02:19:46', '2026-01-29 02:19:46'),
(69, 'digital_files.download', 'web', '2026-01-29 02:19:46', '2026-01-29 02:19:46'),
(70, 'transfers.view', 'web', '2026-01-29 02:19:46', '2026-01-29 02:19:46'),
(71, 'transfers.create', 'web', '2026-01-29 02:19:46', '2026-01-29 02:19:46'),
(72, 'transfers.manage', 'web', '2026-01-29 02:19:46', '2026-01-29 02:19:46'),
(73, 'repositories.view', 'web', '2026-01-29 04:27:13', '2026-01-29 04:27:13'),
(74, 'repositories.create', 'web', '2026-01-29 04:27:13', '2026-01-29 04:27:13'),
(75, 'repositories.edit', 'web', '2026-01-29 04:27:13', '2026-01-29 04:27:13'),
(76, 'repositories.delete', 'web', '2026-01-29 04:27:13', '2026-01-29 04:27:13'),
(77, 'repositories.moderate', 'web', '2026-01-29 04:27:13', '2026-01-29 04:27:13');

-- ============================================
-- 3. ROLE_HAS_PERMISSIONS TABLE
-- ============================================
TRUNCATE TABLE `role_has_permissions`;

-- super_admin (role_id = 1) - ALL 77 permissions
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),
(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),
(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),
(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),
(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),
(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),
(61,1),(62,1),(63,1),(64,1),(65,1),(66,1),(67,1),(68,1),(69,1),(70,1),
(71,1),(72,1),(73,1),(74,1),(75,1),(76,1),(77,1);

-- admin (role_id = 7) - ALL 77 permissions (same as super_admin)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1,7),(2,7),(3,7),(4,7),(5,7),(6,7),(7,7),(8,7),(9,7),(10,7),
(11,7),(12,7),(13,7),(14,7),(15,7),(16,7),(17,7),(18,7),(19,7),(20,7),
(21,7),(22,7),(23,7),(24,7),(25,7),(26,7),(27,7),(28,7),(29,7),(30,7),
(31,7),(32,7),(33,7),(34,7),(35,7),(36,7),(37,7),(38,7),(39,7),(40,7),
(41,7),(42,7),(43,7),(44,7),(45,7),(46,7),(47,7),(48,7),(49,7),(50,7),
(51,7),(52,7),(53,7),(54,7),(55,7),(56,7),(57,7),(58,7),(59,7),(60,7),
(61,7),(62,7),(63,7),(64,7),(65,7),(66,7),(67,7),(68,7),(69,7),(70,7),
(71,7),(72,7),(73,7),(74,7),(75,7),(76,7),(77,7);

-- branch_admin (role_id = 2) - 39 permissions
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1,2),(2,2),(6,2),(7,2),(8,2),(11,2),(12,2),(13,2),(16,2),(17,2),
(18,2),(24,2),(29,2),(30,2),(31,2),(32,2),(34,2),(35,2),(38,2),(39,2),
(40,2),(42,2),(47,2),(48,2),(49,2),(50,2),(51,2),(52,2),(65,2),(66,2),
(67,2),(68,2),(70,2),(71,2),(72,2),(73,2),(74,2),(75,2),(77,2);

-- circulation_staff (role_id = 3) - 13 permissions
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1,3),(11,3),(18,3),(24,3),(29,3),(30,3),(31,3),(32,3),(34,3),(35,3),
(38,3),(40,3),(42,3);

-- catalog_staff (role_id = 4) - 19 permissions
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1,4),(18,4),(19,4),(20,4),(22,4),(23,4),(24,4),(25,4),(26,4),(43,4),
(44,4),(45,4),(46,4),(65,4),(66,4),(67,4),(73,4),(74,4),(75,4);

-- report_viewer (role_id = 5) - 6 permissions
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1,5),(47,5),(48,5),(49,5),(50,5),(51,5);

-- member (role_id = 6) - 6 permissions
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1,6),(18,6),(29,6),(34,6),(35,6),(36,6);

-- ============================================
-- 4. USERS TABLE
-- ============================================
-- NOTE: Adjust branch_id based on your actual branches table
-- Get the first branch ID: SELECT id FROM branches LIMIT 1;

-- Clear existing users (optional - comment out if you want to keep existing users)
-- TRUNCATE TABLE `users`;

-- Insert users (make sure branch_id exists)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `branch_id`, `phone`, `is_active`, `email_verified_at`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrator', 'superadmin@library.test', '$2y$10$YourHashedPasswordHere', 1, '081234567890', 1, NOW(), '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(2, 'Administrator', 'admin@library.test', '$2y$10$YourHashedPasswordHere', 1, '081234567891', 1, NOW(), '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(3, 'Branch Administrator', 'branchadmin@library.test', '$2y$10$YourHashedPasswordHere', 1, '081234567892', 1, NOW(), '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(4, 'Circulation Staff', 'circulation@library.test', '$2y$10$YourHashedPasswordHere', 1, '081234567893', 1, NOW(), '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(5, 'Catalog Staff', 'catalog@library.test', '$2y$10$YourHashedPasswordHere', 1, '081234567894', 1, NOW(), '2026-01-27 04:10:07', '2026-01-27 04:10:07'),
(6, 'Report Viewer', 'report@library.test', '$2y$10$YourHashedPasswordHere', 1, '081234567895', 1, NOW(), '2026-01-27 04:10:07', '2026-01-27 04:10:07');

-- ============================================
-- 5. MODEL_HAS_ROLES TABLE
-- ============================================
TRUNCATE TABLE `model_has_roles`;

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),  -- super_admin
(7, 'App\\Models\\User', 2),  -- admin
(2, 'App\\Models\\User', 3),  -- branch_admin
(3, 'App\\Models\\User', 4),  -- circulation_staff
(4, 'App\\Models\\User', 5),  -- catalog_staff
(5, 'App\\Models\\User', 6);  -- report_viewer

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- 6. UPDATE PASSWORDS (run this after importing)
-- ============================================
-- Run these commands via php artisan tinker or update the passwords directly:
--
-- superadmin@library.test -> super123
-- admin@library.test -> password123
-- branchadmin@library.test -> branch123
-- circulation@library.test -> circulation123
-- catalog@library.test -> catalog123
-- report@library.test -> report123

SELECT 'Roles, Permissions, and Users setup completed!' AS Status;
