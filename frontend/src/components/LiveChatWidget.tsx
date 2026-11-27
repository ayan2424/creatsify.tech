'use client';

import React, { useCallback, useEffect, useRef, useState } from 'react';
import dynamic from 'next/dynamic';
import { AnimatePresence, motion } from 'motion/react';
import { Send, X } from 'lucide-react';
import { useTheme } from './Providers';

const CreatsifyNekoCanvas = dynamic(
  () => import('@/components/ui/CreatsifyNekoCanvas').then((m) => m.CreatsifyNekoCanvas),
  { ssr: false }
);

interface ChatMsg {
  id: number | string;
  sender_type: 'visitor' | 'bot' | 'admin' | string;
  message: string;
  created_at?: string;
}

const QUICK_CHIPS = [
  'What are your service packages and pricing?',
  'Tell me about SEO and Social Media Ads',
  'What is your web and mobile app tech stack?',
  'I want to talk to a human specialist',
];

function getOrCreateToken(): string {
  try {
    const existing = localStorage.getItem('creatsify_chat_token');
    if (existing) return existing;
    const fresh = 'cs_' + Math.random().toString(36).slice(2) + Date.now().toString(36);
    localStorage.setItem('creatsify_chat_token', fresh);
    return fresh;
  } catch {
    return 'cs_' + Math.random().toString(36).slice(2);
  }
}

export function LiveChatWidget() {
  const { theme } = useTheme();
  const [open, setOpen] = useState(false);
  const [messages, setMessages] = useState<ChatMsg[]>([]);
  const [input, setInput] = useState('');
  const [sending, setSending] = useState(false);
  const [isHandoff, setIsHandoff] = useState(false);
  const [unread, setUnread] = useState(0);
  const [launcherCheer, setLauncherCheer] = useState(0);
  const [headerCheer, setHeaderCheer] = useState(0);
  const tokenRef = useRef<string>('');
  const lastIdRef = useRef<number>(0);
  const scrollRef = useRef<HTMLDivElement>(null);

  const scrollToBottom = useCallback(() => {
    requestAnimationFrame(() => {
      if (scrollRef.current) {
        scrollRef.current.scrollTop = scrollRef.current.scrollHeight;
      }
    });
  }, []);

  // Allow Hero "Ask Creatsify AI" button to open the live chat smoothly
  useEffect(() => {
    const onExternalOpen = () => {
      setOpen(true);
      setHeaderCheer((n) => n + 1);
    };
    window.addEventListener('creatsify:open-chat', onExternalOpen);
    return () => window.removeEventListener('creatsify:open-chat', onExternalOpen);
  }, []);

  useEffect(() => {
    tokenRef.current = getOrCreateToken();
    const loadInitial = async () => {
      try {
        const res = await fetch(
          `/api/chat/messages?token=${encodeURIComponent(tokenRef.current)}`,
          { headers: { Accept: 'application/json' } }
        );
        if (!res.ok) return;
        const data = await res.json();
        if (data?.success && Array.isArray(data.messages)) {
          setMessages(data.messages);
          const maxId = data.messages.reduce(
            (acc: number, m: ChatMsg) => Math.max(acc, Number(m.id) || 0),
            0
          );
          lastIdRef.current = maxId;
          if (data.session?.is_human_handoff) setIsHandoff(true);
        }
      } catch {
        // Silent offline fallback
      }
    };
    loadInitial();
  }, []);

  useEffect(() => {
    if (open) {
      setUnread(0);
      setHeaderCheer((n) => n + 1);
      scrollToBottom();
    }
  }, [open, messages.length, scrollToBottom]);

  useEffect(() => {
    if (!open && !isHandoff) return;
    const interval = setInterval(async () => {
      if (!tokenRef.current) return;
      try {
        const res = await fetch(
          `/api/chat/poll?token=${encodeURIComponent(tokenRef.current)}&last_id=${lastIdRef.current}`,
          { headers: { Accept: 'application/json' } }
        );
        if (!res.ok) return;
        const data = await res.json();
        if (data?.success && Array.isArray(data.messages) && data.messages.length > 0) {
          setMessages((prev) => {
            const seen = new Set(prev.map((p) => String(p.id)));
            const incoming = data.messages.filter((m: ChatMsg) => !seen.has(String(m.id)));
            if (incoming.length === 0) return prev;
            if (!open) {
              setUnread((u) => u + incoming.length);
              setLauncherCheer((n) => n + 1);
            } else {
              setHeaderCheer((n) => n + 1);
            }
            return [...prev, ...incoming];
          });
          const maxId = data.messages.reduce(
            (acc: number, m: ChatMsg) => Math.max(acc, Number(m.id) || 0),
            lastIdRef.current
          );
          lastIdRef.current = maxId;
          if (data.is_handoff) setIsHandoff(true);
        }
      } catch {
        // Ignore transient poll error
      }
    }, 5500);

    return () => clearInterval(interval);
  }, [open, isHandoff]);

  const sendMessage = async (text: string) => {
    const trimmed = text.trim();
    if (!trimmed || sending) return;
    setSending(true);
    setInput('');

    const tempId = `local-${Date.now()}`;
    setMessages((prev) => [...prev, { id: tempId, sender_type: 'visitor', message: trimmed }]);
    scrollToBottom();

    try {
      const res = await fetch('/api/chat/send', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify({ token: tokenRef.current, message: trimmed }),
      });
      const data = await res.json();
      if (data?.handoff) setIsHandoff(true);
      if (data?.reply) {
        setHeaderCheer((n) => n + 1);
        setMessages((prev) => [
          ...prev,
          { id: `bot-${Date.now()}`, sender_type: 'bot', message: data.reply },
        ]);
      }
      const sync = await fetch(
        `/api/chat/messages?token=${encodeURIComponent(tokenRef.current)}`,
        { headers: { Accept: 'application/json' } }
      );
      if (sync.ok) {
        const synced = await sync.json();
        if (synced?.success && Array.isArray(synced.messages)) {
          setMessages(synced.messages);
          lastIdRef.current = synced.messages.reduce(
            (acc: number, m: ChatMsg) => Math.max(acc, Number(m.id) || 0),
            0
          );
        }
      }
    } catch {
      setMessages((prev) => [
        ...prev,
        {
          id: `err-${Date.now()}`,
          sender_type: 'bot',
          message: 'Connection issue — please try again or reach us directly on WhatsApp.',
        },
      ]);
    } finally {
      setSending(false);
      scrollToBottom();
    }
  };

  const isDark = theme === 'dark';

  return (
    <div className="fixed bottom-5 right-5 z-[65]">
      <AnimatePresence>
        {open && (
          <motion.div
            initial={{ opacity: 0, scale: 0.94, y: 12 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            exit={{ opacity: 0, scale: 0.94, y: 12 }}
            transition={{ duration: 0.24, ease: [0.16, 1, 0.3, 1] }}
            style={{ transformOrigin: 'bottom right' }}
            className="mb-3 flex h-[495px] w-[min(376px,calc(100vw-2.5rem))] flex-col overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[var(--surface)] shadow-[var(--shadow-elevated)]"
            role="dialog"
            aria-label="Creatsify Live Chat"
          >
            {/* Header with Live Interactive Mini 3D Creatsify Robot */}
            <div className="flex items-center justify-between border-b border-[var(--line)] bg-[var(--bg-subtle)] px-4 py-3">
              <div className="flex items-center gap-3">
                <button
                  type="button"
                  onClick={() => setHeaderCheer((n) => n + 1)}
                  title="Tap to boop Neko!"
                  className="relative h-13 w-13 shrink-0 overflow-hidden rounded-full border border-[var(--accent)]/45 bg-[radial-gradient(circle_at_50%_35%,rgba(37,227,245,0.22),rgba(11,14,22,0.88))] cursor-pointer"
                >
                  <CreatsifyNekoCanvas
                    variant="chatbot-icon"
                    mode={sending ? 'think' : 'hover'}
                    speaking={sending}
                    isDark={isDark}
                    boopSignal={headerCheer}
                    globalPointer={true}
                    interactive={false}
                    className="h-full w-full"
                    ariaLabel="Creatsify AI Neko Assistant"
                  />
                  <span className="pointer-events-none absolute bottom-0.5 right-0.5 h-2.5 w-2.5 rounded-full border-2 border-[var(--bg-subtle)] bg-[var(--emerald)]" />
                </button>
                <div>
                  <p className="font-display text-sm font-bold text-[var(--text)]">
                    Creatsify AI Desk
                  </p>
                  <p className="text-[11.5px] text-[var(--muted)]">
                    {isHandoff
                      ? 'Connected to Senior Specialist'
                      : sending
                      ? 'Thinking...'
                      : 'Online · Instant replies'}
                  </p>
                </div>
              </div>
              <button
                type="button"
                onClick={() => setOpen(false)}
                aria-label="Close live chat"
                className="flex h-8 w-8 items-center justify-center rounded-full text-[var(--muted)] transition-colors hover:bg-[var(--surface-hover)] hover:text-[var(--text)] cursor-pointer"
              >
                <X className="h-4 w-4" />
              </button>
            </div>

            <div ref={scrollRef} className="flex-1 space-y-3 overflow-y-auto p-4 text-[13.5px]">
              <div className="rounded-2xl rounded-tl-sm border border-[var(--line)] bg-[var(--bg-subtle)] px-3.5 py-2.5 leading-relaxed text-[var(--text)]">
                Hi! I&apos;m the <strong>Creatsify</strong> studio assistant. Ask about custom websites, Flutter apps, branding, SEO/ads, packages, or our ready-made platforms.
              </div>

              {messages.length === 0 && (
                <div className="flex flex-wrap gap-1.5 pt-1">
                  {QUICK_CHIPS.map((chip) => (
                    <button
                      key={chip}
                      type="button"
                      onClick={() => sendMessage(chip)}
                      className="rounded-full border border-[var(--line-strong)] bg-[var(--surface-elevated)] px-3 py-1.5 text-left text-[12px] font-medium text-[var(--text)] transition-colors hover:border-[var(--accent)] hover:text-[var(--accent)] cursor-pointer"
                    >
                      {chip}
                    </button>
                  ))}
                </div>
              )}

              {messages.map((m) => {
                const isVisitor = m.sender_type === 'visitor';
                return (
                  <div
                    key={String(m.id)}
                    className={`flex ${isVisitor ? 'justify-end' : 'justify-start'}`}
                  >
                    <div
                      className={`max-w-[85%] whitespace-pre-line rounded-2xl px-3.5 py-2.5 leading-relaxed ${
                        isVisitor
                          ? 'rounded-tr-sm bg-[var(--accent)] font-medium text-[var(--on-accent)]'
                          : 'rounded-tl-sm border border-[var(--line)] bg-[var(--bg-subtle)] text-[var(--text)]'
                      }`}
                    >
                      {m.message.replace(/\*\*/g, '')}
                    </div>
                  </div>
                );
              })}
            </div>

            <form
              onSubmit={(e) => {
                e.preventDefault();
                sendMessage(input);
              }}
              className="flex items-center gap-2 border-t border-[var(--line)] bg-[var(--bg-subtle)] p-3"
            >
              <input
                type="text"
                value={input}
                onChange={(e) => setInput(e.target.value)}
                placeholder="Ask about web, apps, SEO, or pricing..."
                className="flex-1 rounded-xl border border-[var(--line)] bg-[var(--surface)] px-3.5 py-2.5 text-[13.5px] text-[var(--text)] placeholder:text-[var(--muted)] focus:border-[var(--accent)] focus:outline-none"
                disabled={sending}
              />
              <button
                type="submit"
                disabled={sending || !input.trim()}
                aria-label="Send message"
                className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[var(--accent)] text-[var(--on-accent)] transition-transform active:scale-95 disabled:opacity-40 cursor-pointer"
              >
                <Send className="h-4 w-4" />
              </button>
            </form>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Floating 3D Robot Launcher Orb */}
      <div className="group relative flex items-center justify-end">
        {!open && (
          <span className="pointer-events-none mr-2.5 hidden rounded-full border border-[var(--line-strong)] bg-[var(--surface)] px-3 py-1.5 font-display text-xs font-semibold text-[var(--text)] opacity-0 shadow-lg transition-all duration-200 group-hover:translate-x-0 group-hover:opacity-100 sm:inline-flex -translate-x-1">
            Ask Creatsify AI
          </span>
        )}
        <button
          type="button"
          onClick={() => setOpen((v) => !v)}
          onMouseEnter={() => {
            if (!open) setLauncherCheer((n) => n + 1);
          }}
          aria-label={open ? 'Close live chat' : 'Open live chat with Creatsify AI Robot'}
          className={`relative ml-auto flex items-center justify-center overflow-hidden rounded-full transition-transform duration-200 hover:scale-105 active:scale-95 cursor-pointer ${
            open
              ? 'h-13 w-13 bg-gradient-to-br from-[#00D2EE] to-[#0284C7] text-[#041021] shadow-[0_14px_34px_-8px_rgba(0,210,238,0.55)]'
              : 'h-[72px] w-[72px] border-2 border-[#00D2EE]/75 bg-[radial-gradient(circle_at_50%_30%,rgba(26,102,255,0.34),rgba(7,25,94,0.94))] shadow-[0_16px_38px_-6px_rgba(0,210,238,0.55)]'
          }`}
        >
          {open ? (
            <X className="h-5 w-5" />
          ) : (
            <CreatsifyNekoCanvas
              variant="chatbot-icon"
              mode="hover"
              isDark={true}
              boopSignal={launcherCheer}
              globalPointer={true}
              interactive={false}
              className="h-full w-full"
              ariaLabel="Creatsify 3D Neko Chat Assistant"
            />
          )}

          {!open && (
            <span className="pointer-events-none absolute bottom-1 right-1 h-3 w-3 rounded-full border-2 border-[#0a1022] bg-[#10b981]" />
          )}

          {unread > 0 && !open && (
            <span className="absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-[11px] font-bold text-white tabular-nums">
              {unread}
            </span>
          )}
        </button>
      </div>
    </div>
  );
}
