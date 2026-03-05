import {
  FileText,
  Users,
  GraduationCap,
  Wrench,
  Headphones,
  NotebookTabs,
  Monitor,
  ArrowUpDown,
  Grid3x3,
} from "lucide-react";
import { useState, useEffect } from "react";
import { useSearchParams } from "react-router";
import { Breadcrumb } from "../components/Breadcrumb";

type SortOption = "date-new" | "date-old" | "alpha-az" | "alpha-za";
type ItemsPerPage = 8 | 16 | 24 | 32 | 40 | "all";

export function Content() {
  const [searchParams, setSearchParams] = useSearchParams();
  const [activeFilter, setActiveFilter] = useState<
    string | null
  >(null);
  const [sortBy, setSortBy] = useState<SortOption>("date-new");
  const [itemsToShow, setItemsToShow] = useState<ItemsPerPage>(8);

  // Initialize filter from URL on mount
  useEffect(() => {
    const filterFromUrl = searchParams.get("tipo");
    if (filterFromUrl) {
      setActiveFilter(filterFromUrl);
    }
  }, [searchParams]);

  const contentTypes = [
    { type: "Artigos", icon: FileText, count: 45 },
    { type: "Comunidades", icon: Users, count: 12 },
    {
      type: "Cursos e materiais",
      icon: GraduationCap,
      count: 28,
    },
    { type: "Ferramentas", icon: Wrench, count: 32 },
    { type: "Multimídia", icon: Headphones, count: 18 },
    { type: "Sites e sistemas", icon: Monitor, count: 21 },
  ];

  // Mock data for demonstration
  const sampleContent = [
    {
      id: 1,
      type: "Artigos",
      title: "Introdução à WCAG 2.1: O que você precisa saber",
      description:
        "Um guia completo sobre as diretrizes de acessibilidade para conteúdo web e como aplicá-las em seus projetos.",
      author: "Maria Silva",
      date: "2026-03-01",
    },
    {
      id: 2,
      type: "Ferramentas",
      title:
        "Axe DevTools - Extensão para testes de acessibilidade",
      description:
        "Ferramenta essencial para desenvolvedores testarem acessibilidade diretamente no navegador.",
      author: "a11yBR Team",
      date: "2026-02-28",
    },
    {
      id: 3,
      type: "Cursos e materiais",
      title: "Curso completo de acessibilidade web",
      description:
        "Aprenda desde os fundamentos até técnicas avançadas de implementação de acessibilidade digital.",
      author: "João Santos",
      date: "2026-02-25",
    },
    {
      id: 4,
      type: "Artigos",
      title: "Design inclusivo: princípios fundamentais",
      description:
        "Entenda como criar experiências digitais que atendam a diversidade de usuários.",
      author: "Pedro Lima",
      date: "2026-02-20",
    },
    {
      id: 5,
      type: "Multimídia",
      title: "Podcast Acessível: Episódio sobre ARIA",
      description:
        "Discussão aprofundada sobre o uso correto de ARIA em aplicações web modernas.",
      author: "Carla Souza",
      date: "2026-02-18",
    },
  ];

  // Filter content based on active filter
  const filteredContent = activeFilter
    ? sampleContent.filter(
        (content) => content.type === activeFilter,
      )
    : sampleContent;

  // Sort content based on selected option
  const sortedContent = [...filteredContent].sort((a, b) => {
    switch (sortBy) {
      case "date-new":
        return new Date(b.date).getTime() - new Date(a.date).getTime();
      case "date-old":
        return new Date(a.date).getTime() - new Date(b.date).getTime();
      case "alpha-az":
        return a.title.localeCompare(b.title, "pt-BR");
      case "alpha-za":
        return b.title.localeCompare(a.title, "pt-BR");
      default:
        return 0;
    }
  });

  // Limit content based on items to show
  const displayedContent =
    itemsToShow === "all"
      ? sortedContent
      : sortedContent.slice(0, itemsToShow);

  const handleFilterClick = (type: string) => {
    if (activeFilter === type) {
      // Remove filter if clicking on active filter
      setActiveFilter(null);
      setSearchParams({});
    } else {
      // Set new filter
      setActiveFilter(type);
      setSearchParams({ tipo: type });
    }
  };

  return (
    <div className="flex-1">
      {/* Header */}
      <section className="bg-primary text-primary-foreground py-12 md:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Breadcrumb items={[{ label: "Conteúdos" }]} />
          <div className="flex items-center gap-4 mb-6">
            <NotebookTabs className="w-12 h-12" aria-hidden="true" />
            <h1 className="text-4xl md:text-5xl">Conteúdos</h1>
          </div>
          <p className="text-xl text-primary-foreground/90 max-w-2xl">
            Explore nossa coleção de recursos sobre
            acessibilidade digital, organizados por categoria.
          </p>
        </div>
      </section>

      {/* Content Types Grid */}
      <section
        className="py-12 md:py-16"
        aria-label="Tipos de conteúdo"
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-2xl md:text-3xl mb-8">
            Navegue por tipo
          </h2>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-16">
            {contentTypes.map((item) => {
              const Icon = item.icon;
              const isActive = activeFilter === item.type;
              return (
                <button
                  key={item.type}
                  onClick={() => handleFilterClick(item.type)}
                  className={`bg-card border rounded-lg p-6 hover:border-primary hover:bg-accent transition-all focus:outline-none focus:ring-2 focus:ring-primary text-left ${
                    isActive
                      ? "border-primary bg-accent ring-2 ring-primary"
                      : "border-border"
                  }`}
                  aria-label={`Filtrar por ${item.type} - ${item.count} itens`}
                  aria-pressed={isActive}
                >
                  <Icon
                    className="w-8 h-8 text-primary mb-3"
                    aria-hidden="true"
                  />
                  <h3 className="text-lg mb-1">{item.type}</h3>
                  <p className="text-muted-foreground">
                    {item.count} itens
                  </p>
                </button>
              );
            })}
          </div>

          {/* Sample Content List */}
          <div>
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
              <h2 className="text-2xl md:text-3xl">
                {activeFilter
                  ? `Conteúdos: ${activeFilter}`
                  : "Conteúdos recentes"}
              </h2>
              
              {/* Filter Controls */}
              <div className="flex flex-wrap gap-3 w-full sm:w-auto">
                {/* Sort Dropdown */}
                <div className="flex-1 sm:flex-none min-w-[200px]">
                  <label htmlFor="sort-select" className="sr-only">
                    Ordenar conteúdos
                  </label>
                  <div className="relative">
                    <ArrowUpDown
                      className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground pointer-events-none"
                      aria-hidden="true"
                    />
                    <select
                      id="sort-select"
                      value={sortBy}
                      onChange={(e) => setSortBy(e.target.value as SortOption)}
                      className="w-full pl-10 pr-8 py-2 bg-card border border-border rounded-lg appearance-none cursor-pointer hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary transition-colors"
                    >
                      <option value="date-new">Mais recentes</option>
                      <option value="date-old">Mais antigos</option>
                      <option value="alpha-az">A-Z</option>
                      <option value="alpha-za">Z-A</option>
                    </select>
                    <div className="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                      <svg width="12" height="8" viewBox="0 0 12 8" fill="none" aria-hidden="true">
                        <path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                      </svg>
                    </div>
                  </div>
                </div>

                {/* Items Per Page Dropdown */}
                <div className="flex-1 sm:flex-none min-w-[200px]">
                  <label htmlFor="items-select" className="sr-only">
                    Itens por página
                  </label>
                  <div className="relative">
                    <Grid3x3
                      className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground pointer-events-none"
                      aria-hidden="true"
                    />
                    <select
                      id="items-select"
                      value={itemsToShow}
                      onChange={(e) =>
                        setItemsToShow(
                          e.target.value === "all"
                            ? "all"
                            : (parseInt(e.target.value) as ItemsPerPage)
                        )
                      }
                      className="w-full pl-10 pr-8 py-2 bg-card border border-border rounded-lg appearance-none cursor-pointer hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary transition-colors"
                    >
                      <option value={8}>8 itens</option>
                      <option value={16}>16 itens</option>
                      <option value={24}>24 itens</option>
                      <option value={32}>32 itens</option>
                      <option value={40}>40 itens</option>
                      <option value="all">Todos ({filteredContent.length})</option>
                    </select>
                    <div className="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                      <svg width="12" height="8" viewBox="0 0 12 8" fill="none" aria-hidden="true">
                        <path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round"/>
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {displayedContent.length > 0 ? (
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                {displayedContent.map((content) => (
                  <article
                    key={content.id}
                    className="bg-card border border-border rounded-lg p-6 hover:border-primary transition-colors focus-within:ring-2 focus-within:ring-primary"
                  >
                    <div className="flex items-start justify-between gap-4 mb-3">
                      <span className="inline-block bg-accent text-accent-foreground px-3 py-1 rounded text-sm">
                        {content.type}
                      </span>
                      <time
                        className="text-sm text-muted-foreground"
                        dateTime={content.date}
                      >
                        {new Date(
                          content.date,
                        ).toLocaleDateString("pt-BR")}
                      </time>
                    </div>
                    <h3 className="text-xl mb-2">
                      <a
                        href="#"
                        className="hover:text-primary focus:outline-none focus:underline"
                      >
                        {content.title}
                      </a>
                    </h3>
                    <p className="text-muted-foreground mb-4">
                      {content.description}
                    </p>
                    <p className="text-sm text-muted-foreground">
                      Por{" "}
                      <span className="text-foreground">
                        {content.author}
                      </span>
                    </p>
                  </article>
                ))}
              </div>
            ) : (
              <div className="text-center py-12">
                <p className="text-muted-foreground text-lg">
                  Nenhum conteúdo encontrado para esta
                  categoria.
                </p>
              </div>
            )}

            <div className="mt-12 text-center">
              <p className="text-muted-foreground text-lg">
                Esta é uma prévia da plataforma. Mais conteúdos
                serão adicionados em breve.
              </p>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}