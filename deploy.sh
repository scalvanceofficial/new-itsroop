#!/bin/bash
# ─────────────────────────────────────────────────────────────────────────────
# deploy.sh  –  Run this on the live server after every git pull
#
# Usage:
#   chmod +x deploy.sh   (first time only)
#   ./deploy.sh
# ─────────────────────────────────────────────────────────────────────────────

set -e   # Stop on first error

echo ""
echo "┌─────────────────────────────────────┐"
echo "│        ITSROOP DEPLOY SCRIPT        │"
echo "└─────────────────────────────────────┘"
echo ""

# ── 1. Pull latest code ───────────────────────────────────────────────────────
echo "📦 Pulling latest code from GitHub..."
git pull origin main

# ── 2. Install/update PHP dependencies ───────────────────────────────────────
echo ""
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --quiet

# ── 3. Clear all Laravel caches ──────────────────────────────────────────────
echo ""
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# ── 4. Run database migrations ────────────────────────────────────────────────
echo ""
echo "🗄️  Running migrations..."
php artisan migrate --force

# ── 5. Sync orphaned product images ──────────────────────────────────────────
echo ""
echo "🖼️  Syncing product images..."
php artisan images:sync

# ── 6. Ensure storage symlink exists ─────────────────────────────────────────
echo ""
echo "🔗 Ensuring storage symlink..."
php artisan storage:link 2>/dev/null || echo "   (symlink already exists — OK)"

# ── 7. Optimise for production ────────────────────────────────────────────────
echo ""
echo "⚡ Optimising..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "┌─────────────────────────────────────┐"
echo "│         ✅ Deploy complete!         │"
echo "└─────────────────────────────────────┘"
echo ""
