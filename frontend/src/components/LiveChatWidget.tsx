'use client';

import React, { useCallback, useEffect, useRef, useState } from 'react';
import { AnimatePresence, motion } from 'motion/react';
import { MessageSquare, Send, X } from 'lucide-react';

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
  const [open, setOpen] = useState(false);
  const [messages, setMessages] = useState<ChatMsg[]>([]);
  const [input, setInput] = useState('');
  const [sending, setSending] = useState(false);
  const [isHandoff, setIsHandoff] = useState(false);
  const [unread, setUnread] = useState(0);
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
            if (!open) setUnread((u) => u + incoming.length);
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
        // Ignore transient network poll error
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

  return (
    <div className="fixed bottom-5 right-5 z-[65]">
      <AnimatePresence>
        {open && (
          <motion.div
            initial={{ opacity: 0, scale: 0.94, y: 12 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            exit={{ opacity: 0, scale: 0.94, y: 12 }}
            transition={{ duration: 0.22, ease: [0.16, 1, 0.3, 1] }}
            style={{ transformOrigin: 'bottom right' }}
            className="mb-3 flex h-[480px] w-[min(368px,calc(100vw-2.5rem))] flex-col overflow-hidden rounded-2xl border border-[var(--line-strong)] bg-[var(--surface)] shadow-[var(--shadow-elevated)]"
            role="dialog"
            aria-label="Creatsify Live Chat"
          >
            <div className="flex items-center justify-between border-b border-[var(--line)] bg-[var(--bg-subtle)] px-4 py-3.5">
              <div className="flex items-center gap-3">
                <div className="relative flex h-9 w-9 items-center justify-center rounded-xl border border-[var(--line)] bg-[var(--surface)]">
                  <img src="/assets/brand/creatsify-icon.svg" alt="" className="h-6 w-6" />
                  <span className="absolute -bottom-0.5 -right-0.5 h-2.5 w-2.5 rounded-full border-2 border-[var(--bg-subtle)] bg-[var(--emerald)]" />
                </div>
                <div>
                  <p className="font-display text-sm font-bold text-[var(--text)]">
                    Creatsify Agency Desk
                  </p>
                  <p className="text-[11.5px] text-[var(--muted)]">
                    {isHandoff ? 'Connected to Senior Specialist' : 'AI Strategist · Live Desk Ready'}
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
                Welcome to <strong>Creatsify</strong>. Ask about custom web &amp; Flutter apps, redesigns, SEO/ads, packages, or our ready-made platforms.
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

      <button
        type="button"
        onClick={() => setOpen((v) => !v)}
        aria-label={open ? 'Close live chat' : 'Open live chat'}
        className="relative ml-auto flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-[#00D2EE] to-[#0284C7] text-[#041021] shadow-[0_14px_34px_-8px_rgba(0,210,238,0.55)] transition-transform duration-150 hover:scale-105 active:scale-95 cursor-pointer"
      >
        {open ? <X className="h-5 w-5" /> : <MessageSquare className="h-5 w-5" />}
        {unread > 0 && !open && (
          <span className="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-[11px] font-bold text-white tabular-nums">
            {unread}
          </span>
        )}
      </button>
    </div>
  );
}
