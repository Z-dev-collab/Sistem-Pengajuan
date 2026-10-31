# Deploy Guide — kelurahan-website

## Quick Deploy (Fly.io — Free, SQLite)
Best for: Simple deploy, no external DB needed, free tier available.

```bash
# 1. Push ke GitHub dulu
git init
git add .
git commit -m "Deploy config"
git branch -M main
git remote add origin <your-github-url>
git push -u origin main

# 2. Login Fly
fly auth login

# 3. Deploy (akan buat app baru + mount volume)
fly launch --no-deploy
#   - Pilih "Yes" saat ditanya PostgreSQL (skip)
#   - App name: kelurahan-website (atau sesuai preference)
#   - Region: Singapore (sin)

# 4. Buat persistent volume untuk SQLite
fly volumes create data --size 1

# 5. Update fly.toml agar DB path ke volume
#    Ganti: value: /opt/database.sqlite → value: /data/database.sqlite
#    Atau set via env setelah deploy

# 6. Deploy
fly deploy
```

**Set environment variables setelah deploy:**
```bash
fly secrets set APP_ENV=production
fly secrets set APP_DEBUG=false
fly secrets set DB_DATABASE=/data/database.sqlite
fly secrets set MAIL_MAILER=log
```

---

## Alternative: Railway (Free credits $5/bln)
Railway butuh PostgreSQL (tidak support SQLite). Cocok jika ingin scaling ke depan.

```bash
# 1. Push ke GitHub (sama seperti di atas)
# 2. https://railway.app → New Project → Deploy from GitHub
# 3. Tambah service: PostgreSQL
# 4. Railway otomatis baca railway.toml
# 5. Set vars: APP_ENV, APP_DEBUG, DB_CONNECTION=mysql/postgres
#    (konfigurasi database akan di-set otomatis oleh Railway)
```

---

## Catatan Penting
- `APP_KEY` sudah ada di `.env` — jangan di-reset di production
- File upload akan tersimpan di `/storage/app/public/`
- SQLite di Fly.io butuh **persistent volume** agar data tidak hilang saat restart
- Mail saat ini pakai driver `log` — untuk production butuh SMTP (Gmail/SMTP lokal)
