import path from 'node:path';
import type { NextConfig } from 'next';

const PHP_BACKEND = process.env.PHP_BACKEND_URL || 'http://127.0.0.1:8090';

const nextConfig: NextConfig = {
  reactStrictMode: true,
  poweredByHeader: false,
  outputFileTracingRoot: path.join(__dirname),
  images: {
    unoptimized: true,
  },
  async rewrites() {
    return [
      {
        source: '/api/:path*',
        destination: `${PHP_BACKEND}/api/:path*`,
      },
      {
        source: '/assets/:path*',
        destination: `${PHP_BACKEND}/assets/:path*`,
      },
      {
        source: '/uploads/:path*',
        destination: `${PHP_BACKEND}/uploads/:path*`,
      },
      {
        source: '/admin',
        destination: `${PHP_BACKEND}/admin`,
      },
      {
        source: '/admin/:path*',
        destination: `${PHP_BACKEND}/admin/:path*`,
      },
      {
        source: '/sitemap.xml',
        destination: `${PHP_BACKEND}/sitemap.xml`,
      },
      {
        source: '/favicon.ico',
        destination: `${PHP_BACKEND}/favicon.ico`,
      },
      {
        source: '/favicon.svg',
        destination: `${PHP_BACKEND}/favicon.svg`,
      },
      {
        source: '/apple-touch-icon.png',
        destination: `${PHP_BACKEND}/apple-touch-icon.png`,
      },
      {
        source: '/site.webmanifest',
        destination: `${PHP_BACKEND}/site.webmanifest`,
      },
    ];
  },
};

export default nextConfig;
