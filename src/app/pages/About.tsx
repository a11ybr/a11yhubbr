import {
  Upload,
  PersonStanding,
  CheckCircle,
  RefreshCw,
  Users as UsersIcon,
  Shield,
  Sparkles,
  BookOpen,
  MessageSquare,
  ExternalLink,
  Linkedin,
  Twitter,
  Globe,
  MapPin,
  Github,
} from "lucide-react";
import { useEffect } from "react";
import { useLocation, Link } from "react-router";
import { ProfileCard } from "../components/ProfileCard";
import { Breadcrumb } from "../components/Breadcrumb";

export function About() {
  const location = useLocation();

  useEffect(() => {
    // Handle anchor links
    if (location.hash) {
      const element = document.getElementById(
        location.hash.slice(1),
      );
      if (element) {
        element.scrollIntoView({ behavior: "smooth" });
      }
    }
  }, [location]);

  const howItWorks = [
    {
      title: "Submissão comunitária",
      description:
        "Qualquer pessoa pode sugerir conteúdo ou cadastrar perfil.",
      icon: Upload,
      color: "text-pink-500",
      bgColor: "bg-pink-50",
    },
    {
      title: "Revisão editorial",
      description:
        "Equipe verifica cada submissão antes de publicar.",
      icon: CheckCircle,
      color: "text-green-500",
      bgColor: "bg-green-50",
    },
    {
      title: "Publicação aprovada",
      description:
        "Conteúdos aprovados são publicados no diretório.",
      icon: Sparkles,
      color: "text-blue-500",
      bgColor: "bg-blue-50",
    },
    {
      title: "Atualização contínua",
      description:
        "A plataforma é atualizada semanalmente pela equipe.",
      icon: RefreshCw,
      color: "text-purple-500",
      bgColor: "bg-purple-50",
    },
  ];

  const team = [
    {
      name: "Wagner Beethoven",
      type: "Equipe",
      role: "Product Designer",
      location: "Olinda, PE",
      description:
        "Designer apaixonado por criar experiências inclusivas e acessíveis. Acredito que design bom é design acessível para todos, sem exceção.",
      profileImage:
        "https://wagnerbeethoven.com.br/wp-content/uploads/2025/12/Wagner-Beethoven-Desenvolvido-por-IA-2-1024x683.png",
      socialLinks: {
        linkedin: "https://linkedin.com/in/wagnerbeethoven",
        twitter: "https://twitter.com/wagnerbeethoven",
        website: "https://wagnerbeethoven.com",
      },
    },
    {
      name: "Bruno Pulis",
      type: "Equipe",
      role: "Engenheiro de software",
      location: "Belo Horizonte, MG",
      description:
        "Engenheiro de software focado em experiências inclusivas.",
      profileImage:
        "https://brunopulis.com/apple-touch-icon.png",
      socialLinks: {
        github: "https://github.com/brunopulis",
        twitter: "https://bsky.app/profile/brunopulis.com",
        website: "https://brunopulis.com/",
      },
    },
  ];

  const communityValues = [
    "Compartilhar informação de qualidade sobre acessibilidade",
    "Conectar profissionais de todas as regiões do Brasil",
    "Difundir linguagem inclusiva e anti-capacitista",
    "Seguir rigorosamente as diretrizes de acessibilidade",
    "Reconhecer e celebrar as vozes da comunidade",
    "Expandir conhecimento e empoderar pessoas",
  ];

  return (
    <div className="flex-1">
      {/* Header */}
      <section className="bg-primary text-primary-foreground py-12 md:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Breadcrumb items={[{ label: "Sobre" }]} />
          <div className="flex items-center gap-4 mb-6">
            <PersonStanding
              className="w-12 h-12"
              aria-hidden="true"
            />
            <h1 className="text-4xl md:text-5xl">
              Sobre a <span className="font-bold">A11YBR</span>
            </h1>
          </div>
          <p className="text-xl text-primary-foreground/90 max-w-2xl">
            O que somos, por que existimos e como funciona a
            plataforma.
          </p>
        </div>
      </section>

      {/* Mission Section */}
      <section
        className="py-16 md:py-20 bg-background"
        aria-labelledby="mission-title"
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <p className="text-sm uppercase tracking-wider text-muted-foreground mb-4">
            — Missão
          </p>
          <h2
            id="mission-title"
            className="text-3xl md:text-4xl mb-6"
          >
            O que é a <span className="font-bold">A11YBR</span>?
          </h2>

          <div className="prose prose-lg max-w-none">
            <p className="text-lg text-muted-foreground leading-relaxed mb-4">
              A{" "}
              <strong className="text-foreground">
                A11YBR{" "}
              </strong>
              é um <strong className="text-foreground">diretório brasileiro de acessibilidade digital</strong>. Reunimos conteúdos técnicos, artigos,
              projetos, eventos e profissionais que trabalham
              com acessibilidade em um único lugar.
            </p>
            <p className="text-lg text-muted-foreground leading-relaxed mb-4">
              Acreditamos que a acessibilidade digital ainda é
              pouco acessível a todos. Desinformação, falta de
              documentação local e desconexão entre
              profissionais são obstáculos reais. Por isso, o
              conteúdo da plataforma é gratuito e mantido pela
              comunidade.
            </p>
            <p className="text-lg text-muted-foreground leading-relaxed">
              O objetivo do <strong className="text-foreground">
                A11YBR{" "}
              </strong> é servir como plataforma para
              centralizar conteúdo de qualidade em português,
              durar décadas de recursos atualizados sobre WCAG,
              ARIA, tecnologia assistiva e legislação brasileira
              de acessibilidade.
            </p>
          </div>
        </div>
      </section>

      {/* How It Works Section */}
      <section
        className="bg-accent py-16 md:py-20"
        aria-labelledby="how-it-works-title"
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <p className="text-sm uppercase tracking-wider text-muted-foreground mb-4">
            — Funcionamento
          </p>
          <h2
            id="how-it-works-title"
            className="text-3xl md:text-4xl mb-12"
          >
            Como funciona
          </h2>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {howItWorks.map((step, index) => {
              const Icon = step.icon;
              return (
                <article
                  key={step.title}
                  className="bg-card border border-border rounded-lg p-6"
                >
                  <div
                    className={`${step.bgColor} w-12 h-12 rounded-lg flex items-center justify-center mb-4`}
                  >
                    <Icon
                      className={`w-6 h-6 ${step.color}`}
                      aria-hidden="true"
                    />
                  </div>
                  <h3 className="text-lg mb-2 font-semibold">
                    {step.title}
                  </h3>
                  <p className="text-muted-foreground text-sm">
                    {step.description}
                  </p>
                </article>
              );
            })}
          </div>
        </div>
      </section>

      {/* Team Section */}
      <section
        className="py-16 md:py-20 bg-background"
        aria-labelledby="team-title"
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <p className="text-sm uppercase tracking-wider text-muted-foreground mb-4">
            &mdash; Equipe
          </p>
          <h2
            id="team-title"
            className="text-3xl md:text-4xl mb-8"
          >
            Quem mantém a{" "}
            <span className="font-bold">A11YBR</span>
          </h2>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {team.map((member) => (
              <ProfileCard
                key={member.name}
                name={member.name}
                type={member.type}
                role={member.role}
                location={member.location}
                description={member.description}
                profileImage={member.profileImage}
                socialLinks={member.socialLinks}
              />
            ))}
          </div>

          <div className="bg-primary text-primary-foreground border border-border rounded-lg p-6">
            <h3 className="text-xl mb-4 flex items-center gap-2">
              <Github className="w-5 h-5" aria-hidden="true" />
              Open Source
            </h3>
            <p className="my-4">
              O código do a11yBR é open source. Contribua com o
              desenvolvimento da plataforma.
            </p>
            <a
              href="#"
              className="inline-flex items-center gap-2 text-primary hover:underline focus:outline-none focus:ring-2 focus:ring-primary rounded text-[#ffffff]"
            >
              Ver no GitHub
              <ExternalLink
                className="w-4 h-4"
                aria-hidden="true"
              />
            </a>
          </div>
        </div>
      </section>

      {/* Community Section */}
      <section
        id="codigo-conduta"
        className="bg-accent py-16 md:py-20"
        aria-labelledby="community-title"
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <p className="text-sm uppercase tracking-wider text-muted-foreground mb-4">
            — Código de conduta
          </p>
          <h2
            id="community-title"
            className="text-3xl md:text-4xl mb-6"
          >
            Nossa comunidade
          </h2>

          <p className="text-lg text-muted-foreground mb-8">
            A convivência a11yBR é um espaço respeitoso e
            inclusivo para todos. Esperamos que todos os
            participantes:
          </p>

          <ul className="space-y-3 mb-8" role="list">
            {communityValues.map((value, index) => (
              <li
                key={index}
                className="flex items-start gap-3"
              >
                <CheckCircle
                  className="w-5 h-5 text-primary flex-shrink-0 mt-0.5"
                  aria-hidden="true"
                />
                <span className="text-muted-foreground">
                  {value}
                </span>
              </li>
            ))}
          </ul>

          <p className="text-muted-foreground">
            Violações destas diretrizes devem ser reportadas
            para{" "}
            <a
              href="mailto:a11yhubbr@gmail.com"
              className="text-primary hover:underline focus:outline-none focus:ring-2 focus:ring-primary rounded"
            >
              a11yhubbr@gmail.com
            </a>
          </p>
        </div>
      </section>

      {/* Accessibility Statement Section */}
      <section
        id="acessibilidade"
        className="py-16 md:py-20 bg-background"
        aria-labelledby="accessibility-title"
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <p className="text-sm uppercase tracking-wider text-muted-foreground mb-4">
            — Acessibilidade do site
          </p>
          <h2
            id="accessibility-title"
            className="text-3xl md:text-4xl mb-6"
          >
            Declaração de acessibilidade
          </h2>

          <div className="prose prose-lg max-w-none">
            <p className="text-lg text-muted-foreground leading-relaxed mb-6">
              O a11yBR se compromete a atender os critérios de
              sucesso{" "}
              <strong className="text-foreground">
                WCAG 2.1 nível AA
              </strong>
              . Este é um compromisso contínuo com
              acessibilidade HTML, foco visível para todos os
              elementos interativos, contraste de cor adequado,
              navegação por teclado e labels claros em
              formulários.
            </p>

            <div className="bg-accent border border-border rounded-lg p-6 mb-6">
              <h3 className="text-xl mb-4">
                Encontrou algum problema de acessibilidade?
              </h3>
              <p className="text-muted-foreground mb-4">
                Reportar problemas de acessibilidade nos ajuda a
                melhorar continuamente.
              </p>
              <a
                href="mailto:a11yhubbr@gmail.com"
                className="text-primary hover:underline focus:outline-none focus:ring-2 focus:ring-primary rounded font-semibold"
              >
                a11yhubbr@gmail.com
              </a>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
