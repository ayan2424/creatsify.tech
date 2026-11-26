import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function whatsappLink(phone: string, text: string): string {
  const clean = (phone || '923002521199').replace(/[^0-9]/g, '');
  return `https://wa.me/${clean}?text=${encodeURIComponent(text)}`;
}

export function resolveAvatarUrl(index: number): string {
  const num = (Math.abs(index) % 12) + 1;
  return `/assets/images/avatars/avatar-${num}.jpg`;
}

export function hasValidLiveUrl(url?: string | null): boolean {
  return Boolean(url && url.trim() !== '' && url.trim() !== '#');
}
