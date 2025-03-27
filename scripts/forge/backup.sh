#!/bin/bash
#
# Backup for Abhayagiri Website
#
# https://github.com/abhayagiri/abhayagiri-website/blob/dev/scripts/forge/backup.sh
#

set -e

cd "$HOME"
mkdir -p backup
cd backup

BASE_DIR="$(pwd)"

WEBSITE="www.abhayagiri.org"
RCLONE_MEDIA_SRC="do-spaces:abhayagiri"
RCLONE_PRIMARY_DEST="gd-backup:"
RCLONE_SECONDARY_DEST="do-spaces:abhayagiri-backups"
RCLONE_OPTIONS="--verbose --tpslimit 20 --fast-list"
REPOSITORY_URL="https://github.com/abhayagiri/abhayagiri-website.git"

ACCESS_LOG_PATH="/var/log/nginx/$WEBSITE-access.log"
ERROR_LOG_PATH="/var/log/nginx/$WEBSITE-error.log"
LARAVEL_LOG_DIR="$HOME/$WEBSITE/storage/logs"
BACKUP_PATH="$HOME/$WEBSITE/storage/backups/abhayagiri-private-database-latest.sql.bz2"
BACKUP_LOG_PATH="$(mktemp)"

function copy-dated {
  if ! test -f "$1"; then
    echo "Warning: $1 does not exist"
    return
  fi
  log_path="$2/$(date -r "$1" "+%Y/%m/%Y%m%d-$3")"
  mkdir -p "$(dirname "$log_path")"
  cp -vf "$1" "$log_path"
}

(
  # Prepare temporary backup directory
  echo "Starting backup process at $(date)"

  # Ensure we have latest repository for reference
  if ! test -d src; then
    git clone "$REPOSITORY_URL" src
  fi
  ( cd src && git fetch --all && git reset --hard origin/master)

  # Copy database backup
  copy-dated "$BACKUP_PATH" "$BASE_DIR/databases" abhayagiri-database.sql.bz2

  # Copy logs
  copy-dated "$ACCESS_LOG_PATH"    "$BASE_DIR/logs/access"  access.log
  copy-dated "$ACCESS_LOG_PATH.1"  "$BASE_DIR/logs/access"  access.log
  copy-dated "$ERROR_LOG_PATH"     "$BASE_DIR/logs/error"   error.log
  copy-dated "$ERROR_LOG_PATH.1"   "$BASE_DIR/logs/error"   error.log
  copy-dated "$LARAVEL_LOG_DIR/laravel-$(date "+%Y-%m-%d").log" \
                                   "$BASE_DIR/logs/laravel" laravel.log
  copy-dated "$LARAVEL_LOG_DIR/laravel-$(date -d yesterday "+%Y-%m-%d").log" \
                                   "$BASE_DIR/logs/laravel" laravel.log

  # Backup to Google Drive
  echo "Backing up to Google Drive..."
  rclone copy $RCLONE_OPTIONS \
    "$BASE_DIR/src/"           "$RCLONE_PRIMARY_DEST/src/"

  rclone copy $RCLONE_OPTIONS \
    "$BASE_DIR/databases/"     "$RCLONE_PRIMARY_DEST/databases/"

  rclone copy $RCLONE_OPTIONS \
    "$BASE_DIR/logs/"          "$RCLONE_PRIMARY_DEST/logs/"

  rclone sync $RCLONE_OPTIONS \
    "$RCLONE_MEDIA_SRC/media/" "$RCLONE_PRIMARY_DEST/media/"

  # Backup to Digital Ocean Spaces
  echo "Backing up to Digital Ocean Spaces..."
  rclone copy $RCLONE_OPTIONS \
    "$BASE_DIR/src/"           "$RCLONE_SECONDARY_DEST/src/"

  rclone copy $RCLONE_OPTIONS \
    "$BASE_DIR/databases/"     "$RCLONE_SECONDARY_DEST/databases/"

  rclone copy $RCLONE_OPTIONS \
    "$BASE_DIR/logs/"          "$RCLONE_SECONDARY_DEST/logs/"

  # Note: We don't need to sync media to SECONDARY_DEST since it's already on DO Spaces

  # Clean up temporary files
  echo "Cleaning up temporary files from server to save space..."
  rm -rf "$BASE_DIR/src"
  rm -rf "$BASE_DIR/databases"
  rm -rf "$BASE_DIR/logs"

  echo "Backup completed at $(date)"
) >> "$BACKUP_LOG_PATH" 2>&1

# Copy the backup log to both destinations
rclone copyto \
  "$BACKUP_LOG_PATH" \
  "$RCLONE_PRIMARY_DEST/logs/backups/$(date "+%Y/%m/%Y%m%d-%H%M%S")-backup.log"

rclone copyto \
  "$BACKUP_LOG_PATH" \
  "$RCLONE_SECONDARY_DEST/logs/backups/$(date "+%Y/%m/%Y%m%d-%H%M%S")-backup.log"

rm -f "$BACKUP_LOG_PATH"