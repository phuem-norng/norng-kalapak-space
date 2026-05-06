# Deploy Guide (Vercel + Render + Neon)

## 1) Create Neon database

1. Create a project in Neon.
2. Copy connection values for:
   - `host`
   - `database`
   - `user`
   - `password`
   - `port` (`5432`)
3. Neon requires SSL, so use `DB_SSLMODE=require`.

## 2) Deploy backend to Render

1. In Render: **New -> Blueprint** and select this repository.
2. Render reads `render.yaml` and creates `kalapak-backend`.
3. In backend service env vars, set:
   - `APP_URL=https://<your-backend>.onrender.com`
   - `FRONTEND_URL=https://<your-frontend>.vercel.app`
   - `SANCTUM_STATEFUL_DOMAINS=<your-frontend>.vercel.app`
   - `DB_HOST=<neon-host>`
   - `DB_PORT=5432`
   - `DB_DATABASE=<neon-db>`
   - `DB_USERNAME=<neon-user>`
   - `DB_PASSWORD=<neon-password>`
   - `DB_SSLMODE=require`
4. Deploy and wait until healthy.

## 3) Run Laravel migration on Render

Run once after first deploy:

```bash
php artisan migrate --force
```

If needed:

```bash
php artisan db:seed --force
```

## 4) Deploy frontend to Vercel

1. In Vercel: **Add New Project** and import this repository.
2. Set Root Directory to `frontend`.
3. Add env vars:
   - `NUXT_PUBLIC_API_URL=https://<your-backend>.onrender.com/api`
   - `NUXT_PUBLIC_SITE_URL=https://<your-frontend>.vercel.app`
4. Deploy.

## 5) CORS/Sanctum checklist

- Backend `FRONTEND_URL` must match Vercel URL exactly.
- Backend `SANCTUM_STATEFUL_DOMAINS` must include Vercel domain without protocol.
- Frontend `NUXT_PUBLIC_API_URL` must point to Render backend `/api`.

