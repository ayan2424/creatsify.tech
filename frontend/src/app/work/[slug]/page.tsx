import { redirect } from 'next/navigation';

export default async function WorkSlugRedirectPage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  redirect(`/portfolio/${encodeURIComponent(slug)}`);
}
