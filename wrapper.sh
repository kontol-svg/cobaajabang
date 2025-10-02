#!/bin/bash
# wrapper.sh - baca env SECRET, lalu jalankan gsocket_x.sh

if [[ -z "$SECRET" ]]; then
    echo "SECRET unset"
    exit 1
fi

# set env agar gsocket_x.sh bisa mengaksesnya
export APP_SECRET="$SECRET"

# jalankan skrip asli
exec bash ~/gsocket_x.sh
