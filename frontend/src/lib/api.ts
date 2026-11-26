import type {
  BootstrapPayload,
  HomePayload,
  PackageItem,
  PostItem,
  ProjectItem,
  ReviewItem,
  ReviewStats,
  ServiceItem,
} from '@/types/creatsify';

const PHP_BACKEND = process.env.PHP_BACKEND_URL || 'http://127.0.0.1:8090';

const DEFAULT_CATEGORIES: Record<string, string> = {
  'web-dev': 'Web Design & Development',
  'mobile-app': 'Mobile App Development',
  'saas-crm': 'SaaS & CRM',
  'software-eng': 'Software & Product Engineering',
  'seo-geo': 'SEO & GEO',
  'digital-marketing': 'Digital Marketing',
  'ui-ux-branding': 'UI/UX Design & Branding',
};

const DEFAULT_SITE = {
  name: 'Creatsify',
  title: 'Creatsify — Web, Mobile & SaaS Development Agency',
  meta_description:
    'Custom web apps, Flutter mobile apps, SaaS, website redesigns, branding and SEO — built by a senior team with fixed milestones and full source-code ownership.',
  email: 'hello@creatsify.tech',
  whatsapp_number: '923002521199',
  logo_dark: '/assets/brand/creatsify-logo-dark.svg',
  logo_light: '/assets/brand/creatsify-logo-light.svg',
  icon: '/assets/brand/creatsify-icon.svg',
};

const DEFAULT_STATS: ReviewStats = {
  average: '4.9',
  avg_rating: '4.9',
  total: 8,
  five_star: 8,
  four_star: 0,
  three_star: 0,
  two_star: 0,
  one_star: 0,
  distribution: {
    5: { count: 8, percent: 100 },
    4: { count: 0, percent: 0 },
    3: { count: 0, percent: 0 },
    2: { count: 0, percent: 0 },
    1: { count: 0, percent: 0 },
  },
};

async function fetchBackend<T>(path: string, revalidate = 60): Promise<T | null> {
  try {
    const res = await fetch(`${PHP_BACKEND}${path}`, {
      headers: { Accept: 'application/json' },
      next: { revalidate },
    });
    if (!res.ok) return null;
    return (await res.json()) as T;
  } catch {
    return null;
  }
}

export async function getBootstrap(): Promise<BootstrapPayload> {
  const data = await fetchBackend<BootstrapPayload>('/api/v1/bootstrap', 60);
  if (data?.success) return data;
  return {
    success: true,
    site: DEFAULT_SITE,
    csrf_token: '',
    categories: DEFAULT_CATEGORIES,
    review_stats: DEFAULT_STATS,
    footer_products: [],
  };
}

export async function getHomeData(): Promise<HomePayload> {
  const data = await fetchBackend<HomePayload>('/api/v1/home', 60);
  if (data?.success) return data;
  return {
    success: true,
    site: DEFAULT_SITE,
    sections: {},
    categories: DEFAULT_CATEGORIES,
    projects: [],
    portfolio_works: [],
    ready_made_products: [],
    services: [],
    packages_by_track: { dev: [], design: [], growth: [] },
    package_tracks: {},
    posts: [],
    reviews: [],
    review_stats: DEFAULT_STATS,
  };
}

export async function getProjectsData(category?: string, softwareOnly = false): Promise<{
  categories: Record<string, string>;
  active_category: string;
  projects: ProjectItem[];
  software: ProjectItem[];
}> {
  const params = new URLSearchParams();
  if (category) params.set('cat', category);
  if (softwareOnly) params.set('software', '1');
  const qs = params.toString() ? `?${params.toString()}` : '';
  const data = await fetchBackend<{
    success: boolean;
    categories: Record<string, string>;
    active_category: string;
    projects: ProjectItem[];
    software: ProjectItem[];
  }>(`/api/v1/projects${qs}`, 60);

  if (data?.success) {
    return {
      categories: data.categories,
      active_category: data.active_category,
      projects: data.projects,
      software: data.software,
    };
  }
  return {
    categories: DEFAULT_CATEGORIES,
    active_category: category || 'web-dev',
    projects: [],
    software: [],
  };
}

export async function getProjectBySlug(slug: string): Promise<{
  project: ProjectItem | null;
  client_review: ReviewItem | null;
  related: ProjectItem[];
}> {
  const data = await fetchBackend<{
    success: boolean;
    project: ProjectItem;
    client_review: ReviewItem | null;
    related: ProjectItem[];
  }>(`/api/v1/projects/${encodeURIComponent(slug)}`, 60);

  if (data?.success && data.project) {
    return {
      project: data.project,
      client_review: data.client_review || null,
      related: data.related || [],
    };
  }
  return { project: null, client_review: null, related: [] };
}

export async function getServicesData(): Promise<ServiceItem[]> {
  const data = await fetchBackend<{ success: boolean; services: ServiceItem[] }>('/api/v1/services', 60);
  return data?.success ? data.services : [];
}

export async function getPackagesData(): Promise<{
  packages_by_track: Record<string, PackageItem[]>;
  packages: PackageItem[];
}> {
  const data = await fetchBackend<{
    success: boolean;
    packages_by_track: Record<string, PackageItem[]>;
    packages: PackageItem[];
  }>('/api/v1/packages', 60);
  if (data?.success) {
    return {
      packages_by_track: data.packages_by_track,
      packages: data.packages,
    };
  }
  return { packages_by_track: { dev: [], design: [], growth: [] }, packages: [] };
}

export async function getReviewsData(category = 'all'): Promise<{
  active_category: string;
  categories: string[];
  reviews: ReviewItem[];
  stats: ReviewStats;
}> {
  const qs = category && category !== 'all' ? `?category=${encodeURIComponent(category)}` : '';
  const data = await fetchBackend<{
    success: boolean;
    active_category: string;
    categories: string[];
    reviews: ReviewItem[];
    stats: ReviewStats;
  }>(`/api/v1/reviews${qs}`, 30);

  if (data?.success) {
    return {
      active_category: data.active_category,
      categories: data.categories,
      reviews: data.reviews,
      stats: data.stats,
    };
  }
  return {
    active_category: 'all',
    categories: [
      'Web Development',
      'Mobile App Development',
      'Website Redesign & UI/UX',
      'SaaS & Custom Software',
      'SEO & Social Media Ads',
      'Branding & Graphic Design',
      'White-Label Customization',
    ],
    reviews: [],
    stats: DEFAULT_STATS,
  };
}

export async function getPostsData(): Promise<PostItem[]> {
  const data = await fetchBackend<{ success: boolean; posts: PostItem[] }>('/api/v1/posts', 60);
  return data?.success ? data.posts : [];
}

export async function getPostBySlug(slug: string): Promise<PostItem | null> {
  const data = await fetchBackend<{ success: boolean; post: PostItem }>(
    `/api/v1/posts/${encodeURIComponent(slug)}`,
    60
  );
  return data?.success ? data.post : null;
}

export async function fetchClientCsrfToken(): Promise<string> {
  try {
    const res = await fetch('/api/v1/csrf', {
      credentials: 'include',
      headers: { Accept: 'application/json' },
      cache: 'no-store',
    });
    if (!res.ok) return '';
    const json = (await res.json()) as { csrf_token?: string };
    return json.csrf_token || '';
  } catch {
    return '';
  }
}
