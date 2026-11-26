'use client';

import React, { useState } from 'react';
import { CheckCircle2, LifeBuoy, Loader2, Send, Terminal } from 'lucide-react';
import type { ProjectItem, ServiceItem } from '@/types/creatsify';
import { fetchClientCsrfToken } from '@/lib/api';

const SUPPORT_TYPES = [
  { key: 'software_support', label: 'Purchased software technical support', refRequired: true },
  { key: 'software_deployment', label: 'Software installation or deployment', refRequired: true },
  { key: 'billing_license', label: 'License, billing, or Gumroad receipt', refRequired: true },
  { key: 'service_delivery', label: 'Existing agency service or project', refRequired: false },
  { key: 'retainer_support', label: 'Maintenance or retainer support', refRequired: false },
  { key: 'general_support', label: 'Other existing-customer help', refRequired: false },
];

export function SupportClient({
  projects,
  services,
  initialOffering = '',
}: {
  projects: ProjectItem[];
  services: ServiceItem[];
  initialOffering?: string;
}) {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [whatsapp, setWhatsapp] = useState('');
  const [company, setCompany] = useState('');
  const [supportType, setSupportType] = useState('software_support');
  const [offeringName, setOfferingName] = useState(
    initialOffering || projects[0]?.title || 'Cheetah Enterprise'
  );
  const [purchaseRef, setPurchaseRef] = useState('');
  const [serverEnv, setServerEnv] = useState('aaPanel / Nginx / PHP 8.4');
  const [domainUrl, setDomainUrl] = useState('');
  const [priority, setPriority] = useState('medium');
  const [subject, setSubject] = useState('');
  const [message, setMessage] = useState('');
  const [honeypot, setHoneypot] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [result, setResult] = useState<{
    type: 'success' | 'error';
    text: string;
    ticket?: string;
  } | null>(null);

  const selectedConfig = SUPPORT_TYPES.find((t) => t.key === supportType) || SUPPORT_TYPES[0];

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (submitting) return;
    setSubmitting(true);
    setResult(null);

    try {
      const csrf = await fetchClientCsrfToken();
      const res = await fetch('/api/support', {
        method: 'POST',
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-CSRF-Token': csrf,
        },
        body: JSON.stringify({
          _csrf_token: csrf,
          _hp_check: honeypot,
          name,
          email,
          company,
          whatsapp,
          support_type: supportType,
          offering_type: supportType.startsWith('software') || supportType === 'billing_license' ? 'software' : 'service',
          offering_name: offeringName,
          purchase_reference: purchaseRef,
          server_environment: serverEnv,
          domain_url: domainUrl,
          priority,
          subject,
          message,
        }),
      });
      const json = await res.json();
      if (res.ok && json?.success) {
        setResult({
          type: 'success',
          text: json.message,
          ticket: json.ticket_number,
        });
        setSubject('');
        setMessage('');
      } else {
        setResult({
          type: 'error',
          text: json?.error || 'Could not create support ticket. Please check required fields.',
        });
      }
    } catch {
      setResult({
        type: 'error',
        text: 'Network error submitting ticket. Please retry or message us on WhatsApp.',
      });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="mx-auto max-w-[1220px] px-5 py-14 sm:px-6 lg:py-20">
      <header className="max-w-3xl">
        <h1 className="font-display text-3xl font-extrabold tracking-tight text-[var(--text)] sm:text-5xl">
          First-Party Script &amp; Client Support Desk
        </h1>
        <p className="mt-4 text-base leading-relaxed text-[var(--muted)] sm:text-lg">
          Direct engineering support for Gumroad white-label software buyers and active Creatsify agency clients. Every ticket syncs straight into our CRM and alerts our senior desk on WhatsApp.
        </p>
      </header>

      <div className="mt-12 grid gap-8 lg:grid-cols-12">
        <form
          onSubmit={handleSubmit}
          className="space-y-4 rounded-2xl border border-[var(--line-strong)] bg-[var(--surface)] p-6 sm:p-8 lg:col-span-7"
        >
          <input
            type="text"
            name="_hp_check"
            value={honeypot}
            onChange={(e) => setHoneypot(e.target.value)}
            className="hidden"
            tabIndex={-1}
            autoComplete="off"
          />

          <div className="flex items-center gap-2.5 border-b border-[var(--line)] pb-4">
            <LifeBuoy className="h-5 w-5 text-[var(--accent)]" />
            <strong className="font-display text-lg font-bold text-[var(--text)]">
              Open a Technical Ticket
            </strong>
          </div>

          {result && (
            <div
              role="status"
              className={`flex items-start gap-2.5 rounded-xl border p-4 text-sm ${
                result.type === 'success'
                  ? 'border-emerald-500/35 bg-emerald-500/10 text-emerald-500'
                  : 'border-rose-500/35 bg-rose-500/10 text-rose-400'
              }`}
            >
              {result.type === 'success' && <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0" />}
              <div>
                <p>{result.text}</p>
                {result.ticket && (
                  <p className="mt-1 font-mono text-xs font-bold">
                    Reference ID: {result.ticket}
                  </p>
                )}
              </div>
            </div>
          )}

          <div className="grid gap-4 sm:grid-cols-2">
            <label className="block text-xs font-semibold text-[var(--text)]">
              Full name *
              <input
                type="text"
                required
                value={name}
                onChange={(e) => setName(e.target.value)}
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
            <label className="block text-xs font-semibold text-[var(--text)]">
              Email address *
              <input
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
          </div>

          <div className="grid gap-4 sm:grid-cols-2">
            <label className="block text-xs font-semibold text-[var(--text)]">
              WhatsApp / Phone
              <input
                type="tel"
                value={whatsapp}
                onChange={(e) => setWhatsapp(e.target.value)}
                placeholder="+92 / +1 / +44..."
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
            <label className="block text-xs font-semibold text-[var(--text)]">
              Company / Store Name
              <input
                type="text"
                value={company}
                onChange={(e) => setCompany(e.target.value)}
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
          </div>

          <div className="grid gap-4 sm:grid-cols-2">
            <label className="block text-xs font-semibold text-[var(--text)]">
              Support Type
              <select
                value={supportType}
                onChange={(e) => setSupportType(e.target.value)}
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              >
                {SUPPORT_TYPES.map((t) => (
                  <option key={t.key} value={t.key}>
                    {t.label}
                  </option>
                ))}
              </select>
            </label>
            <label className="block text-xs font-semibold text-[var(--text)]">
              Product or Service *
              <select
                value={offeringName}
                onChange={(e) => setOfferingName(e.target.value)}
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              >
                <optgroup label="Commercial Software">
                  {projects.map((p) => (
                    <option key={p.slug} value={p.title}>
                      {p.title}
                    </option>
                  ))}
                </optgroup>
                <optgroup label="Agency Services">
                  {services.map((s) => (
                    <option key={s.slug} value={s.title}>
                      {s.title}
                    </option>
                  ))}
                </optgroup>
              </select>
            </label>
          </div>

          <div className="grid gap-4 sm:grid-cols-2">
            <label className="block text-xs font-semibold text-[var(--text)]">
              Gumroad Order ID / License Key {selectedConfig.refRequired ? '*' : '(optional)'}
              <input
                type="text"
                required={selectedConfig.refRequired}
                value={purchaseRef}
                onChange={(e) => setPurchaseRef(e.target.value)}
                placeholder="GUM-XXXX or License Reference"
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
            <label className="block text-xs font-semibold text-[var(--text)]">
              Priority
              <select
                value={priority}
                onChange={(e) => setPriority(e.target.value)}
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              >
                <option value="low">Low — General question</option>
                <option value="medium">Medium — Standard help</option>
                <option value="high">High — Staging or deploy blocker</option>
                <option value="urgent">Urgent — Production outage</option>
              </select>
            </label>
          </div>

          <div className="grid gap-4 sm:grid-cols-2">
            <label className="block text-xs font-semibold text-[var(--text)]">
              Server Environment
              <input
                type="text"
                value={serverEnv}
                onChange={(e) => setServerEnv(e.target.value)}
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
            <label className="block text-xs font-semibold text-[var(--text)]">
              Target Domain URL
              <input
                type="url"
                value={domainUrl}
                onChange={(e) => setDomainUrl(e.target.value)}
                placeholder="https://yourdomain.com"
                className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
              />
            </label>
          </div>

          <label className="block text-xs font-semibold text-[var(--text)]">
            Ticket Subject *
            <input
              type="text"
              required
              value={subject}
              onChange={(e) => setSubject(e.target.value)}
              placeholder="Brief summary of the issue"
              className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
            />
          </label>

          <label className="block text-xs font-semibold text-[var(--text)]">
            Detailed Description &amp; Error Logs *
            <textarea
              rows={4}
              required
              value={message}
              onChange={(e) => setMessage(e.target.value)}
              placeholder="Share steps to reproduce, PHP/MySQL version, or exact error output..."
              className="mt-1.5 w-full rounded-xl border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 text-sm font-normal text-[var(--text)] focus:border-[var(--accent)] focus:outline-none"
            />
          </label>

          <button type="submit" disabled={submitting} className="btn-brand w-full sm:w-auto">
            {submitting ? (
              <>
                <Loader2 className="h-4 w-4 animate-spin" />
                <span>Creating ticket...</span>
              </>
            ) : (
              <>
                <Send className="h-4 w-4" />
                <span>Create Support Ticket</span>
              </>
            )}
          </button>
        </form>

        {/* Self-Service aaPanel Nginx Helpdesk */}
        <aside className="space-y-5 lg:col-span-5">
          <div className="rounded-2xl border border-[var(--line)] bg-[var(--surface)] p-6">
            <div className="flex items-center gap-2.5">
              <Terminal className="h-5 w-5 text-[var(--accent)]" />
              <h2 className="font-display text-lg font-bold text-[var(--text)]">
                Self-Service aaPanel Nginx Rewrite
              </h2>
            </div>
            <p className="mt-2 text-sm leading-relaxed text-[var(--muted)]">
              Deploying a Creatsify PHP MVC script on aaPanel? Paste strictly these rules inside your site&apos;s <strong>URL Rewrite</strong> box (never add an outer <code>server</code> or duplicate <code>gzip</code> block):
            </p>
            <pre className="mt-4 overflow-x-auto rounded-xl border border-[var(--line)] bg-[#080a10] p-4 font-mono text-xs leading-relaxed text-cyan-200">
{`location / {
    try_files $uri $uri/ /index.php?$query_string;
}

error_page 404 /index.php;

location ~ ^/(app|config|core|database|storage|views)/ {
    deny all;
    return 404;
}

location ~* (composer\\.(json|lock)|\\.env|\\.sql|\\.log)$ {
    deny all;
    return 404;
}`}
            </pre>
            <p className="mt-4 text-xs text-[var(--muted)]">
              Required PHP extensions: <code>pdo_mysql</code>, <code>mbstring</code>, <code>curl</code>, <code>zip</code>, <code>gd</code> (PHP 8.2–8.4).
            </p>
          </div>
        </aside>
      </div>
    </div>
  );
}
