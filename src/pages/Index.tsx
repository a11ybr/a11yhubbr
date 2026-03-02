import { Link } from "react-router-dom";
import { ArrowRight, Users, FileText, CalendarDays, Layers, ClipboardList, Accessibility, Ear, PenTool, Code, Smartphone, type LucideIcon } from "lucide-react";
import { ContentCard } from "@/components/cards/ContentCard";
import { ProfileCard } from "@/components/cards/ProfileCard";
import { EventCard } from "@/components/cards/EventCard";
import { NewsletterModule } from "@/components/NewsletterModule";
import { Tag } from "@/components/ui/Tag";
import { Button } from "../components/ui/button";
import { Button as DayPickerButton } from "react-day-picker";
import { useEffect, useState } from "react";
import { supabase } from "@/lib/supabase"; // ajuste se necessário

const categories: { label: string; count: number; icon: LucideIcon }[] = [
  { label: "WCAG & Diretrizes", count: 48, icon: ClipboardList },
  { label: "Tecnologia Assistiva", count: 32, icon: Accessibility },
  { label: "Libras & Surdez", count: 27, icon: Ear },
  { label: "Design Inclusivo", count: 41, icon: PenTool },
  { label: "Desenvolvimento Web", count: 55, icon: Code },
  { label: "Mobile & Apps", count: 19, icon: Smartphone }];


const recentContent = [
  {
    title: "Implementando ARIA live regions para notificações acessíveis",
    excerpt: "Como usar aria-live, aria-atomic e aria-relevant para criar regiões dinâmicas que comunicam atualizações a usuários de leitores de tela.",
    type: "tutorial" as const,
    tags: ["ARIA", "leitores-de-tela", "HTML"],
    author: "Fernanda Oliveira",
    date: "15 fev 2025",
    readTime: "8 min",
    href: "/conteudo/aria-live-regions"
  },
  {
    title: "Contraste de cores no design de interfaces: além do AA",
    excerpt: "Uma análise aprofundada dos requisitos WCAG AAA e como implementar paletas de cores que garantam acessibilidade para usuários com baixa visão.",
    type: "artigo" as const,
    tags: ["cores", "design", "WCAG"],
    author: "Rafael Mendes",
    date: "12 fev 2025",
    readTime: "12 min",
    href: "/conteudo/contraste-cores"
  },
  {
    title: "Biblioteca React de componentes acessíveis: a11y-ui-br",
    excerpt: "Projeto open source com componentes React pré-testados com NVDA, JAWS e VoiceOver, documentação em português.",
    type: "projeto" as const,
    tags: ["React", "open-source", "componentes"],
    author: "Lucas Carvalho",
    date: "10 fev 2025",
    readTime: "5 min",
    href: "/conteudo/a11y-ui-br"
  }];


const featuredProfiles = [
  {
    name: "Dra. Camila Souza",
    role: "Pesquisadora em IHC e Acessibilidade",
    bio: "Professora da USP com foco em interfaces para pessoas com deficiência visual. Autora de mais de 30 publicações sobre acessibilidade web.",
    tags: ["Pesquisa", "IHC", "Deficiência Visual"],
    type: "profissional" as const,
    initials: "CS",
    location: "São Paulo, SP",
    links: [{ label: "LinkedIn", href: "#", icon: "linkedin" as const }]
  },
  {
    name: "Instituto Incluir Tech",
    role: "Organização de tecnologia inclusiva",
    bio: "ONG que oferece capacitação gratuita em tecnologia assistiva para pessoas com deficiência em todo o Brasil.",
    tags: ["Capacitação", "Tecnologia Assistiva", "ONG"],
    type: "empresa" as const,
    initials: "IT",
    location: "Curitiba, PR",
    links: [{ label: "Site", href: "#", icon: "globe" as const }]
  },
  {
    name: "Pedro Almeida",
    role: "Desenvolvedor Front-end & Especialista WCAG",
    bio: "10 anos de experiência em desenvolvimento web acessível. Consultor para empresas do setor financeiro e governamental.",
    tags: ["WCAG", "Front-end", "Auditoria"],
    type: "mentor" as const,
    initials: "PA",
    location: "Belo Horizonte, MG",
    links: [
      { label: "LinkedIn", href: "#", icon: "linkedin" as const },
      { label: "Site", href: "#", icon: "globe" as const }]

  }];


const upcomingEvents = [
  {
    title: "Workshop: Auditoria de Acessibilidade com NVDA",
    description: "Aprenda a realizar auditorias de acessibilidade usando o leitor de tela gratuito NVDA. Prático e hands-on.",
    date: "28 de fevereiro de 2025",
    time: "19h00 – 21h00",
    location: "Online (Zoom)",
    type: "workshop" as const,
    online: true,
    organizer: "a11yBR",
    registrationUrl: "#"
  },
  {
    title: "Meetup a11yBR São Paulo — Edição #12",
    description: "Encontro presencial da comunidade paulistana de acessibilidade digital. Palestras, networking e troca de experiências.",
    date: "8 de março de 2025",
    time: "14h00 – 18h00",
    location: "iMasters Hub — São Paulo, SP",
    type: "meetup" as const,
    online: false,
    organizer: "Comunidade a11yBR SP",
    registrationUrl: "#"
  }];




type CommunityStats = {
  active_profiles: number;
  represented_specializations: number;
  published_contents: number;
  approved_events: number;
};
export default function Index() {
  const [stats, setStats] = useState<CommunityStats | null>(null);

  useEffect(() => {
    async function fetchStats() {
      const { data } = await supabase
        .from("community_stats")
        .select("*")
        .eq("id", 1)
        .single();

      if (data) setStats(data);
    }

    fetchStats();

    const channel = supabase
      .channel("community-stats")
      .on(
        "postgres_changes",
        {
          event: "UPDATE",
          schema: "public",
          table: "community_stats",
          filter: "id=eq.1",
        },
        (payload) => {
          console.log("Realtime payload:", payload);
          setStats(payload.new as CommunityStats);
        }
      )
      .subscribe();

    return () => {
      supabase.removeChannel(channel);
    };
  }, []);

  return (
    <main id="main-content">
      {/* Hero */}
      <section
        aria-labelledby="hero-heading"
        className="border-b border-border"
        style={{ background: "hsl(var(--card))" }}>

        <div className="container-site py-16 lg:py-24">
          <div className="max-w-3xl">
            <span className="section-label mb-5 block">Hub colaborativo</span>
            <h1 id="hero-heading" className="mb-5 text-foreground">
              Acessibilidade digital em português,{" "}
              <span style={{ color: "hsl(var(--primary))" }}>pela comunidade</span>
            </h1>
            <p className="text-lg text-muted-foreground leading-relaxed mb-8 max-w-2xl">
              O <strong>A11yBR</strong> reúne artigos, tutoriais, projetos, recursos e profissionais dedicados à inclusão digital no Brasil. Conteúdo colaborativo, revisado e sempre gratuito.
            </p>
            <div className="flex flex-wrap gap-3">
              <Link
                to="/conteudo">
                <button
                  type="submit"
                  className="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-primary-foreground transition-colors flex-shrink-0"
                  style={{ background: "hsl(var(--primary))" }}
                  onMouseEnter={(e) => {
                    (e.currentTarget as HTMLElement).style.background = "hsl(var(--primary-hover))";
                  }}
                  onMouseLeave={(e) => {
                    (e.currentTarget as HTMLElement).style.background = "hsl(var(--primary))";
                  }}
                >
                  Inscrever-se
                  <ArrowRight size={15} aria-hidden />
                </button>
              </Link>
              <Link
                to="/submeter">
                <Button variant="ghost" className="flex items-center gap-2">
                  Submeter conteúdo
                </Button>
              </Link>

            </div>
          </div>
        </div>
        {/* Stats strip */}
        {stats && (
          <div
            aria-label="Estatísticas da comunidade"
            className="border-y container-site mb-16 rounded-lg border py-12"
            style={{ background: "hsl(var(--card))" }}
          >

            <div className="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-0 lg:divide-x lg:divide-white/20">

              {/* Conteúdos */}
              <div className="flex flex-col items-center text-center gap-3 lg:px-8">
                <div className="w-14 h-14 rounded-full bg-primary/15 flex items-center justify-center">
                  <FileText size={24} className="text-primary" aria-hidden />
                </div>
                <span className="text-3xl font-extrabold text-foreground">
                  {stats.published_contents}
                </span>
                <span className="text-xs text-muted-foreground">
                  Conteúdos <br />publicados
                </span>
              </div>

              {/* Perfis */}
              <div className="flex flex-col items-center text-center gap-3 lg:px-8">
                <div className="w-14 h-14 rounded-full bg-primary/15 flex items-center justify-center">
                  <Users size={24} className="text-primary" aria-hidden />
                </div>
                <span className="text-3xl font-extrabold text-foreground">
                  {stats.active_profiles}
                </span>
                <span className="text-xs text-muted-foreground">
                  Perfis <br />ativos
                </span>
              </div>

              {/* Eventos */}
              <div className="flex flex-col items-center text-center gap-3 lg:px-8">
                <div className="w-14 h-14 rounded-full bg-primary/15 flex items-center justify-center">
                  <CalendarDays size={24} className="text-primary" aria-hidden />
                </div>
                <span className="text-3xl font-extrabold text-foreground">
                  {stats.approved_events}
                </span>
                <span className="text-xs text-muted-foreground">
                  Eventos <br />catalogados
                </span>
              </div>

              {/* Especializações */}
              <div className="flex flex-col items-center text-center gap-3 lg:px-8">
                <div className="w-14 h-14 rounded-full bg-primary/15 flex items-center justify-center">
                  <Layers size={24} className="text-primary" aria-hidden />
                </div>
                <span className="text-3xl font-extrabold text-foreground">
                  {stats.represented_specializations}
                </span>
                <span className="text-xs text-muted-foreground">
                  Especializações <br />representadas
                </span>
              </div>

            </div>
          </div>
        )}
      </section>



      {/* Categories */}
      <section
        className="section-gap border-b border-border"
        aria-labelledby="categories-heading">

        <div className="container-site">
          <div className="flex items-start justify-between gap-4 mb-8">
            <div>
              <span className="section-label mb-2 block">Navegue por tema</span>
              <h2 id="categories-heading">Categorias em destaque</h2>
            </div>
            <Link
              to="/conteudo"
              className="hidden sm:flex items-center gap-1 text-sm font-medium text-primary hover:underline no-underline flex-shrink-0">

              Ver todos <ArrowRight size={14} aria-hidden />
            </Link>
          </div>

          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            {categories.map((cat) => {
              const Icon = cat.icon;
              return (
                <Link
                  key={cat.label}
                  to={`/conteudo?categoria=${encodeURIComponent(cat.label)}`}
                  className="group flex flex-col items-center text-center gap-3 p-5 rounded-xl border border-border bg-card no-underline transition-all hover:border-primary/30 hover:shadow-card-hover hover:-translate-y-0.5">

                  <Icon size={28} className="text-primary" aria-hidden />
                  <span className="text-sm font-semibold text-foreground group-hover:text-primary transition-colors leading-tight">
                    {cat.label}
                  </span>
                  <span className="text-xs text-muted-foreground">{cat.count} itens</span>
                </Link>
              );
            })}
          </div>
        </div>
      </section>

      {/* Recent content */}
      <section
        className="section-gap border-b border-border"
        style={{ background: "hsl(var(--card))" }}
        aria-labelledby="recent-heading">

        <div className="container-site">
          <div className="flex items-start justify-between gap-4 mb-8">
            <div>
              <span className="section-label mb-2 block">Publicações recentes</span>
              <h2 id="recent-heading">Últimas submissões</h2>
            </div>
            <Link
              to="/conteudo"
              className="hidden sm:flex items-center gap-1 text-sm font-medium text-primary hover:underline no-underline flex-shrink-0">

              Ver tudo <ArrowRight size={14} aria-hidden />
            </Link>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            {recentContent.map((item) =>
              <ContentCard key={item.href} {...item} />
            )}
          </div>
        </div>
      </section>

      {/* Featured professionals */}
      <section
        className="section-gap border-b border-border"
        aria-labelledby="professionals-heading">

        <div className="container-site">
          <div className="flex items-start justify-between gap-4 mb-8">
            <div>
              <span className="section-label mb-2 block">Comunidade</span>
              <h2 id="professionals-heading">Profissionais em destaque</h2>
            </div>
            <Link
              to="/comunidade"
              className="hidden sm:flex items-center gap-1 text-sm font-medium text-primary hover:underline no-underline flex-shrink-0">

              Ver diretório <ArrowRight size={14} aria-hidden />
            </Link>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            {featuredProfiles.map((p) =>
              <ProfileCard key={p.name} {...p} />
            )}
          </div>
        </div>
      </section>

      {/* Events */}
      <section
        className="section-gap border-b border-border"
        style={{ background: "hsl(var(--card))" }}
        aria-labelledby="events-heading">

        <div className="container-site">
          <div className="flex items-start justify-between gap-4 mb-8">
            <div>
              <span className="section-label mb-2 block">Agenda</span>
              <h2 id="events-heading">Próximos eventos</h2>
            </div>
            <Link
              to="/eventos"
              className="hidden sm:flex items-center gap-1 text-sm font-medium text-primary hover:underline no-underline flex-shrink-0">

              Ver agenda <ArrowRight size={14} aria-hidden />
            </Link>
          </div>
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-5">
            {upcomingEvents.map((evt) =>
              <EventCard key={evt.title} {...evt} />
            )}
          </div>
        </div>
      </section>

      {/* CTA + Newsletter */}
      <section className="section-gap" aria-labelledby="cta-heading">
        <div className="container-site">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            {/* Submit CTA */}
            <div
              className="rounded-xl p-8 flex flex-col gap-5"
              style={{ background: "hsl(var(--primary))" }}>

              <span className="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-white/70">
                <span className="w-4 h-0.5 bg-white/50" aria-hidden />
                Contribua
              </span>
              <h2 id="cta-heading" className="text-white text-2xl">
                Compartilhe seu conhecimento
              </h2>
              <p className="text-white/80 text-sm leading-relaxed">
                Tem um artigo, tutorial, projeto ou recurso sobre acessibilidade? Submeta para revisão e ajude a comunidade a crescer.
              </p>
              <div className="flex flex-wrap gap-2 mt-1">
                {["Artigos", "Tutoriais", "Projetos", "Recursos"].map((t) =>
                  <span
                    key={t}
                    className="tag-base bg-white/15 text-white text-xs">

                    {t}
                  </span>
                )}
              </div>
              <Link
                to="/submeter"
                className="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold no-underline transition-colors self-start bg-white text-primary hover:bg-white/90">

                Submeter conteúdo <ArrowRight size={14} aria-hidden />
              </Link>
            </div>

            {/* Newsletter */}
            <NewsletterModule />
          </div>
        </div>
      </section>
    </main>);

}