#!/usr/bin/env bash
# Siteyi FTP ile canlıya yükler.
#   ./scripts/deploy.sh            → sadece kod (src, templates, public)
#   ./scripts/deploy.sh --with-data → veritabanı + uploads da yüklenir (canlı veriyi EZER)
set -euo pipefail
cd "$(dirname "$0")/.."

[ -f .env.deploy ] || { echo ".env.deploy yok. .env.deploy.example dosyasını kopyalayıp doldurun."; exit 1; }
set -a; . ./.env.deploy; set +a

export WITH_DATA=0
if [ "${1:-}" = "--with-data" ]; then
  read -r -p "Canlı veritabanı ve resimler ÜZERİNE YAZILACAK. Emin misiniz? (evet/hayir) " a
  [ "$a" = "evet" ] || { echo "İptal."; exit 1; }
  export WITH_DATA=1
fi

php scripts/deploy.php
echo
echo "Kontrol:"
curl -s -o /dev/null -w "  ana sayfa: %{http_code}\n" -m 20 "${APP_URL:-https://sezaierenmobilya.com}/" || true
