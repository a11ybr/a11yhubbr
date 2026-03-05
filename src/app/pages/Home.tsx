import { Link } from "react-router";
import {
  FileText,
  Users,
  GraduationCap,
  Calendar,
  Wrench,
  Headphones,
  Monitor,
  Briefcase,
  Building2,
  HandMetal,
  Eye,
  FileSpreadsheet,
} from "lucide-react";

export function Home() {
  const contentCategories = [
    {
      title: "Artigos",
      description:
        "conteúdos escritos com análise, opinião ou estudo de caso",
      icon: FileText,
    },
    {
      title: "Comunidades",
      description:
        "espaços de networking e troca sobre acessibilidade",
      icon: Users,
    },
    {
      title: "Cursos e materiais",
      description:
        "formações estruturadas e trilhas educacionais",
      icon: GraduationCap,
    },
    {
      title: "Eventos",
      description:
        "conferências, workshops, meetups ou webinars",
      icon: Calendar,
    },
    {
      title: "Ferramentas",
      description:
        "recursos técnicos para auditoria e testes de acessibilidade",
      icon: Wrench,
    },
    {
      title: "Multimídia",
      description: "podcasts e canais de vídeo",
      icon: Headphones,
    },
    {
      title: "Sites e sistemas",
      description:
        "produtos digitais com foco em acessibilidade",
      icon: Monitor,
    },
  ];

  const communityProfiles = [
    {
      title: "Profissionais de tecnologia",
      description:
        "designers, desenvolvedores, QA, product managers",
      icon: Briefcase,
    },
    {
      title: "Empresas e ONGs",
      description:
        "organizações comprometidas com acessibilidade",
      icon: Building2,
    },
    {
      title: "Intérpretes de Libras",
      description:
        "profissionais de comunicação em língua de sinais",
      icon: HandMetal,
    },
    {
      title: "Audiodescritores",
      description:
        "especialistas em descrição de conteúdo visual",
      icon: Eye,
    },
    {
      title: "Tradutores de Braille",
      description:
        "profissionais especializados em escrita tátil",
      icon: FileSpreadsheet,
    },
  ];

  return (
    <div className="flex-1">
      {/* Hero Section */}
      <section
        className="bg-primary text-primary-foreground py-16 md:py-24"
        aria-labelledby="hero-title"
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="max-w-3xl">
            <span className="section-label mb-5 block">Hub colaborativo</span>
            <h1
              id="hero-title"
              className="text-4xl md:text-5xl mb-6"
            >Acessibilidade digital em português, <span className="text-white"><span className="font-bold">pela comunidade</span></span></h1>
            <p className="text-md text-primary-foreground/90 mb-8 text-[16px]">A <span className="font-bold">A11yBR</span> reúne artigos, tutoriais, projetos, recursos e profissionais dedicados à inclusão digital no Brasil. Conteúdo colaborativo, revisado e sempre gratuito.</p>
            <div className="flex flex-col sm:flex-row gap-4">
              <Link
                to="/conteudos"
                className="inline-block bg-white text-primary px-6 py-3 rounded-lg hover:bg-primary-foreground/90 transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary text-center font-bold"
              >
                Explorar conteúdos
              </Link>
              <Link
                to="/submeter"
                className="inline-block bg-transparent border-1 border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary text-center"
              >
                Submeter conteúdo
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Content Categories Section */}
      <section
        className="py-16 md:py-20"
        aria-labelledby="categories-title"
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2
            id="categories-title"
            className="text-3xl md:text-4xl mb-4"
          >
            Categorias de conteúdo
          </h2>
          <p className="text-lg text-muted-foreground mb-12 max-w-2xl">
            Explore recursos organizados por tipo para facilitar
            sua busca por conhecimento em acessibilidade.
          </p>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {contentCategories.map((category) => {
              const Icon = category.icon;
              const linkTo = category.title === "Eventos" 
                ? "/eventos" 
                : `/conteudos?tipo=${encodeURIComponent(category.title)}`;
              return (
                <Link
                  key={category.title}
                  to={linkTo}
                  className="bg-card border border-border rounded-lg p-6 hover:border-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
                >
                  <article>
                    <div className="flex items-start gap-4">
                      <div
                        className="bg-accent text-accent-foreground p-3 rounded-lg flex-shrink-0"
                        aria-hidden="true"
                      >
                        <Icon className="w-6 h-6" />
                      </div>
                      <div>
                        <h3 className="text-xl mb-2">
                          {category.title}
                        </h3>
                        <p className="text-muted-foreground">
                          {category.description}
                        </p>
                      </div>
                    </div>
                  </article>
                </Link>
              );
            })}
          </div>
        </div>
      </section>

      {/* Community Section */}
      <section
        className="bg-accent py-16 md:py-20"
        aria-labelledby="community-title"
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2
            id="community-title"
            className="text-3xl md:text-4xl mb-4"
          >
            Comunidade
          </h2>
          <p className="text-lg text-muted-foreground mb-12 max-w-2xl">
            Rede de profissionais e organizações que atuam com
            acessibilidade.
          </p>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            {communityProfiles.map((profile) => {
              const Icon = profile.icon;
              return (
                <Link
                  key={profile.title}
                  to={`/comunidade?tipo=${encodeURIComponent(profile.title)}`}
                  className="bg-white border border-border rounded-lg p-6 hover:border-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary"
                >
                  <article>
                    <div className="flex items-start gap-4">
                      <div
                        className="bg-primary text-primary-foreground p-3 rounded-lg flex-shrink-0"
                        aria-hidden="true"
                      >
                        <Icon className="w-6 h-6" />
                      </div>
                      <div>
                        <h3 className="text-xl mb-2">
                          {profile.title}
                        </h3>
                        <p className="text-muted-foreground">
                          {profile.description}
                        </p>
                      </div>
                    </div>
                  </article>
                </Link>
              );
            })}
          </div>

          <div className="text-center">
            <Link
              to="/comunidade"
              className="inline-block bg-primary text-primary-foreground px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
            >
              Ver toda a comunidade
            </Link>
          </div>
        </div>
      </section>

      {/* Newsletter CTA */}
      <section
        className="py-16 md:py-20"
        aria-labelledby="newsletter-cta-title"
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="bg-primary text-primary-foreground rounded-lg p-8 md:p-12 text-center">
            <h2
              id="newsletter-cta-title"
              className="text-3xl md:text-4xl mb-4"
            >
              Receba novos conteúdos sobre acessibilidade
            </h2>
            <p className="text-lg text-primary-foreground/90 mb-8 max-w-2xl mx-auto">
              Fique por dentro das últimas novidades, recursos e
              discussões sobre acessibilidade digital no Brasil.
            </p>
            <Link
              to="/newsletter"
              className="inline-block bg-white text-primary px-8 py-3 rounded-lg hover:bg-primary-foreground/90 transition-colors focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary"
            >
              Inscrever na newsletter
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
}