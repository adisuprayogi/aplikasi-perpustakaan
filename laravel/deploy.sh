#!/bin/bash

###########################################
# DEPLOYMENT SCRIPT FOR PRODUCTION SERVER
# Aplikasi Perpustakaan Digital
###########################################

set -e  # Exit on error

echo "============================================"
echo "  DEPLOYMENT - Aplikasi Perpustakaan"
echo "============================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Step 1: Check .env file
echo -e "${YELLOW}Step 1: Checking environment configuration...${NC}"
if [ ! -f .env ]; then
    echo -e "${RED}Error: .env file not found!${NC}"
    echo "Please copy .env.production.example to .env and configure it."
    exit 1
fi
echo -e "${GREEN}✓ .env file found${NC}"
echo ""

# Step 2: Install dependencies
echo -e "${YELLOW}Step 2: Installing composer dependencies...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction
echo -e "${GREEN}✓ Composer dependencies installed${NC}"
echo ""

# Step 3: Install npm dependencies
echo -e "${YELLOW}Step 3: Installing npm dependencies...${NC}"
npm ci
echo -e "${GREEN}✓ NPM dependencies installed${NC}"
echo ""

# Step 4: Build assets
echo -e "${YELLOW}Step 4: Building assets for production...${NC}"
npm run build
echo -e "${GREEN}✓ Assets built${NC}"
echo ""

# Step 5: Clear and cache configurations
echo -e "${YELLOW}Step 5: Optimizing application...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓ Application optimized${NC}"
echo ""

# Step 6: Run database migrations
echo -e "${YELLOW}Step 6: Running database migrations...${NC}"
php artisan migrate --force
echo -e "${GREEN}✓ Migrations completed${NC}"
echo ""

# Step 7: Seed database (only on fresh install or when needed)
echo -e "${YELLOW}Step 7: Seeding database...${NC}"
# Seed permissions
php artisan db:seed --class=PermissionSeeder --force
echo -e "${GREEN}✓ Database seeded${NC}"
echo ""

# Step 8: Create storage links
echo -e "${YELLOW}Step 8: Creating storage links...${NC}"
php artisan storage:link
echo -e "${GREEN}✓ Storage links created${NC}"
echo ""

# Step 9: Set permissions
echo -e "${YELLOW}Step 9: Setting file permissions...${NC}"
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
echo -e "${GREEN}✓ Permissions set${NC}"
echo ""

# Step 10: Clear and restart queues
echo -e "${YELLOW}Step 10: Restarting queue workers...${NC}"
php artisan queue:restart
echo -e "${GREEN}✓ Queue workers restarted${NC}"
echo ""

echo "============================================"
echo -e "${GREEN}  DEPLOYMENT COMPLETED SUCCESSFULLY!${NC}"
echo "============================================"
echo ""
echo "Next steps:"
echo "1. Verify the application is working: https://your-domain.com"
echo "2. Login with admin credentials"
echo "3. Check the database tables are created"
echo ""
