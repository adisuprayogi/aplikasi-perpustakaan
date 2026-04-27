#!/bin/bash

###########################################
# QUICK FIX: Database Migration Script
# Use this when "Table not found" error occurs
###########################################

set -e

echo "============================================"
echo "  FIXING DATABASE MIGRATIONS"
echo "============================================"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Step 1: Check .env
echo -e "${YELLOW}Step 1: Checking .env...${NC}"
if [ ! -f .env ]; then
    echo -e "${RED}Error: .env not found!${NC}"
    exit 1
fi
source .env
echo -e "${GREEN}✓ .env loaded${NC}"
echo "Database: $DB_DATABASE"
echo ""

# Step 2: Test Database Connection
echo -e "${YELLOW}Step 2: Testing database connection...${NC}"
if php artisan db:show &>/dev/null; then
    echo -e "${GREEN}✓ Database connection OK${NC}"
else
    echo -e "${RED}✗ Database connection failed!${NC}"
    echo "Check your .env DB_* settings"
    exit 1
fi
echo ""

# Step 3: Check Current Migration Status
echo -e "${YELLOW}Step 3: Checking migration status...${NC}"
MIGRATION_COUNT=$(php artisan migrate:status 2>/dev/null | grep -c "Ran" || echo "0")
echo "Current migrations: $MIGRATION_COUNT"
echo ""

# Step 4: Run Migrations
echo -e "${YELLOW}Step 4: Running migrations...${NC}"
php artisan migrate --force

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Migrations completed successfully${NC}"
else
    echo -e "${RED}✗ Migration failed!${NC}"
    echo "Check the error above and fix manually"
    exit 1
fi
echo ""

# Step 5: Verify Tables
echo -e "${YELLOW}Step 5: Verifying tables...${NC}"
php artisan db:table collections >/dev/null 2>&1 && echo -e "${GREEN}✓ collections table exists${NC}" || echo -e "${RED}✗ collections table missing${NC}"
php artisan db:table members >/dev/null 2>&1 && echo -e "${GREEN}✓ members table exists${NC}" || echo -e "${RED}✗ members table missing${NC}"
php artisan db:table loans >/dev/null 2>&1 && echo -e "${GREEN}✓ loans table exists${NC}" || echo -e "${RED}✗ loans table missing${NC}"
php artisan db:table users >/dev/null 2>&1 && echo -e "${GREEN}✓ users table exists${NC}" || echo -e "${RED}✗ users table missing${NC}"
echo ""

# Step 6: Seed Permissions
echo -e "${YELLOW}Step 6: Seeding permissions...${NC}"
php artisan db:seed --class=PermissionSeeder --force
echo -e "${GREEN}✓ Permissions seeded${NC}"
echo ""

# Step 7: Clear Cache
echo -e "${YELLOW}Step 7: Clearing cache...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo -e "${GREEN}✓ Cache cleared${NC}"
echo ""

echo "============================================"
echo -e "${GREEN}  DATABASE FIX COMPLETED!${NC}"
echo "============================================"
echo ""
echo "Test the application at: $APP_URL"
echo "If you still see errors, check: storage/logs/laravel.log"
echo ""
