export interface SiteConfig {
  name: string;
  title: string;
  meta_description: string;
  email: string;
  whatsapp_number: string;
  logo_dark: string;
  logo_light: string;
  icon: string;
}

export interface ProjectItem {
  id: number;
  title: string;
  slug: string;
  category: string;
  category_label: string;
  subcategory?: string | null;
  summary: string;
  description?: string | null;
  client_name?: string | null;
  tech_stack?: string | null;
  tech_list: string[];
  live_url?: string | null;
  gumroad_url?: string | null;
  price?: string | null;
  status?: string | null;
  featured?: number | boolean;
  is_software: boolean;
  image_url?: string | null;
  image_resolved: string;
  outcome_metric: string;
  sort_order?: number;
}

export interface ServiceItem {
  id: number;
  title: string;
  slug: string;
  icon?: string | null;
  short_description: string;
  full_description?: string | null;
  deliverables?: string | null;
  sort_order?: number;
  is_active?: number;
}

export interface PackageItem {
  id: number;
  title: string;
  slug: string;
  category_track: 'dev' | 'design' | 'growth' | string;
  tier_badge?: string | null;
  price_label: string;
  billing_cycle?: string | null;
  delivery_time?: string | null;
  subtitle?: string | null;
  features?: string | null;
  features_list: string[];
  cta_text?: string | null;
  is_popular: boolean;
  sort_order?: number;
}

export interface ReviewItem {
  id: number;
  client_name: string;
  client_role?: string | null;
  company_name?: string | null;
  service_category: string;
  rating: number;
  review_title: string;
  review_text: string;
  outcome_metric?: string | null;
  is_verified?: number;
  is_featured?: number;
  created_at?: string;
}

export interface ReviewStats {
  average: string;
  avg_rating: string;
  total: number;
  five_star: number;
  four_star: number;
  three_star: number;
  two_star: number;
  one_star: number;
  distribution: Record<number, { count: number; percent: number }>;
}

export interface PostItem {
  id: number;
  title: string;
  slug: string;
  excerpt?: string | null;
  content?: string | null;
  featured_image?: string | null;
  category?: string | null;
  tags?: string | null;
  author_name?: string | null;
  views_count?: number;
  published_at?: string | null;
  created_at?: string | null;
}

export interface HomePayload {
  success: boolean;
  site: SiteConfig;
  sections: Record<string, unknown>;
  categories: Record<string, string>;
  projects: ProjectItem[];
  portfolio_works: ProjectItem[];
  ready_made_products: ProjectItem[];
  services: ServiceItem[];
  packages_by_track: Record<string, PackageItem[]>;
  package_tracks: Record<string, { label: string; tagline?: string } | string>;
  posts: PostItem[];
  reviews: ReviewItem[];
  review_stats: ReviewStats;
}

export interface BootstrapPayload {
  success: boolean;
  site: SiteConfig;
  csrf_token: string;
  categories: Record<string, string>;
  review_stats: ReviewStats;
  footer_products: Array<{
    slug: string;
    title: string;
    price?: string | null;
    gumroad_url?: string | null;
  }>;
}
