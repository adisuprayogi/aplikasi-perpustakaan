#!/bin/bash

echo "📸 Screenshot Automation for Aplikasi Perpustakaan"
echo "============================================="
echo ""

# Create screenshots directory if it doesn't exist
mkdir -p screenshots

# Check if Laravel server is running
if ! curl -s http://localhost:8000 > /dev/null; then
    echo "🚀 Starting Laravel server..."
    php artisan serve --no-interaction > /dev/null 2>&1 &
    echo "   Waiting for server to start..."
    sleep 5
    echo ""
fi

# Run all screenshot automation tests
echo "📸 Capturing screenshots..."
echo ""

echo "1️⃣ Public Pages..."
npx playwright test screenshots-public.spec.js --project=chromium

echo ""
echo "2️⃣ Dashboard..."
npx playwright test screenshots-dashboard.spec.js --project=chromium

echo ""
echo "3️⃣ Collections & Members..."
npx playwright test screenshots-collections-members.spec.js --project=chromium

echo ""
echo "4️⃣ Loans & Circulation..."
npx playwright test screenshots-loans.spec.js --project=chromium

echo ""
echo "5️⃣ Reports..."
npx playwright test screenshots-reports.spec.js --project=chromium

echo ""
echo "6️⃣ Settings & Admin..."
npx playwright test screenshots-settings.spec.js --project=chromium

echo ""
echo "7️⃣ Digital Library & Repository..."
npx playwright test screenshots-digital-library-repo.spec.js --project=chromium

echo ""
echo "8️⃣ Mobile Responsive..."
npx playwright test screenshots-mobile.spec.js --project=chromium

echo ""
echo "✅ Screenshot capture complete!"
echo "📁 Screenshots saved to: ./screenshots/"
echo ""
echo "Next step: Generate User Manual from screenshots"
