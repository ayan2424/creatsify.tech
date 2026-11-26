module.exports = {
  apps: [
    {
      name: 'creatsify-frontend',
      cwd: '/www/wwwroot/creatsify.tech/frontend',
      script: 'node_modules/next/dist/bin/next',
      args: 'start -H 127.0.0.1 -p 3000',
      instances: 1,
      exec_mode: 'fork',
      autorestart: true,
      max_memory_restart: '512M',
      env: {
        NODE_ENV: 'production',
        PORT: '3000',
        PHP_BACKEND_URL: 'https://creatsify.tech',
        NEXT_PUBLIC_SITE_URL: 'https://creatsify.tech',
      },
    },
  ],
};
