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
  Search,
  X,
  ChevronLeft,
  ChevronRight,
} from "lucide-react";
import { useEffect, useMemo, useState } from "react";
import { useSearchParams } from "react-router";
import { Breadcrumb } from "../components/Breadcrumb";

type SortOption = "date-new" | "date-old" | "alpha-az" | "alpha-za";
type ItemsPerPage = 8 | 12 | 24;
type ContentType =
  | "Artigos"
  | "Comunidades"
  | "Cursos e materiais"
  | "Ferramentas"
  | "Multimídia"
  | "Sites e sistemas";

interface ContentItem {
  id: number;
  type: ContentType;
  title: string;
  description: string;
  author: string;
  date: string;
}

const contentTypes: {
  type: ContentType;
  icon: typeof FileText;
}[] = [
  { type: "Artigos", icon: FileText },
  { type: "Comunidades", icon: Users },
  { type: "Cursos e materiais", icon: GraduationCap },
  { type: "Ferramentas", icon: Wrench },
  { type: "Multimídia", icon: Headphones },
  { type: "Sites e sistemas", icon: Monitor },
];

const sampleContent: ContentItem[] = [
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
    title: "Axe DevTools - Extensão para testes de acessibilidade",
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
  {
    id: 6,
    type: "Comunidades",
    title: "Grupo de estudos em acessibilidade para design systems",
    description:
      "Comunidade de prática para revisar componentes, tokens e padrões acessíveis.",
    author: "Ana Monteiro",
    date: "2026-02-15",
  },
  {
    id: 7,
    type: "Sites e sistemas",
    title: "Benchmark de portais públicos acessíveis no Brasil",
    description:
      "Levantamento de boas práticas em sites governamentais com foco em navegação por teclado.",
    author: "Eduardo Rocha",
    date: "2026-02-12",
  },
  {
    id: 8,
    type: "Ferramentas",
    title: "Checklist automatizado para contraste de cor",
    description:
      "Script para validar contraste em paletas e componentes durante o fluxo de desenvolvimento.",
    author: "Bruna Costa",
    date: "2026-02-10",
  },
  {
    id: 9,
    type: "Artigos",
    title: "Como escrever microcopy inclusiva",
    description:
      "Técnicas de linguagem simples, inclusiva e orientada a tarefas para produtos digitais.",
    author: "Juliana Alves",
    date: "2026-02-08",
  },
  {
    id: 10,
    type: "Cursos e materiais",
    title: "Trilha prática de acessibilidade para dev front-end",
    description:
      "Sequência de estudos com exemplos reais de semântica, formulários e leitores de tela.",
    author: "Rafael Dias",
    date: "2026-02-05",
  },
  {
    id: 11,
    type: "Sites e sistemas",
    title: "Biblioteca de padrões de formulário acessível",
    description:
      "Coleção de referências para construir experiências de cadastro mais inclusivas.",
    author: "Lívia Prado",
    date: "2026-02-02",
  },
  {
    id: 12,
    type: "Multimídia",
    title: "Canal com revisões de acessibilidade em produtos reais",
    description:
      "Vídeos semanais com análises práticas de interfaces e sugestões de melhoria.",
    author: "Marcelo Nunes",
    date: "2026-01-30",
  },
];

export function Content() {
  const [searchParams, setSearchParams] = useSearchParams();
  const [searchDraft, setSearchDraft] = useState("");

  const activeFilter = searchParams.get("tipo");
  const sortBy = (searchParams.get("ordem") as SortOption) || "date-new";
  const itemsValue = Number(searchParams.get("itens"));
  const itemsToShow: ItemsPerPage =
    itemsValue === 12 || itemsValue === 24 ? itemsValue : 8;
  const searchQuery = searchParams.get("busca") || "";
  const pageFromQuery = Number(searchParams.get("pg")) || 1;

  const updateParams = (updates: Record<string, string | null>) => {
    const params = new URLSearchParams(searchParams);

    Object.entries(updates).forEach(([key, value]) => {
      if (value === null || value === "") {
        params.delete(key);
      } else {
        params.set(key, value);
      }
    });

    setSearchParams(params);
  };

  useEffect(() => {
    setSearchDraft(searchQuery);
  }, [searchQuery]);

  const contentMatchingSearch = useMemo(() => {
    if (!searchQuery.trim()) {
      return sampleContent;
    }

    const normalizedSearch = searchQuery.trim().toLowerCase();

    return sampleContent.filter((content) =>
      [content.title, content.description, content.author, content.type]
        .join(" ")
        .toLowerCase()
        .includes(normalizedSearch),
    );
  }, [searchQuery]);

  const countByType = useMemo(() => {
    const counts = new Map<ContentType, number>();
    contentTypes.forEach((item) => counts.set(item.type, 0));

    contentMatchingSearch.forEach((item) => {
      counts.set(item.type, (counts.get(item.type) || 0) + 1);
    });

    return counts;
  }, [contentMatchingSearch]);

  const filteredContent = useMemo(() => {
    if (!activeFilter) {
      return contentMatchingSearch;
    }

    return contentMatchingSearch.filter((content) => content.type === activeFilter);
  }, [activeFilter, contentMatchingSearch]);

  const sortedContent = useMemo(
    () =>
      [...filteredContent].sort((a, b) => {
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
      }),
    [filteredContent, sortBy],
  );

  const totalPages = Math.max(1, Math.ceil(sortedContent.length / itemsToShow));
  const currentPage = Math.min(Math.max(1, pageFromQuery), totalPages);
  const start = (currentPage - 1) * itemsToShow;
  const displayedContent = sortedContent.slice(start, start + itemsToShow);

  useEffect(() => {
    if (pageFromQuery !== currentPage) {
      updateParams({ pg: String(currentPage) });
    }
  }, [pageFromQuery, currentPage]);

  const handleFilterClick = (type: ContentType) => {
    if (activeFilter === type) {
      updateParams({ tipo: null, pg: "1" });
      return;
    }

    updateParams({ tipo: type, pg: "1" });
  };

  const hasActiveFilters = Boolean(
    activeFilter ||
      searchQuery ||
      sortBy !== "date-new" ||
      itemsToShow !== 8,
  );

  return (
    <div className="flex-1">
      <section className="bg-primary text-primary-foreground py-12 md:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Breadcrumb items={[{ label: "Conteúdos" }]} />
          <div className="flex items-center gap-4 mb-6">
            <NotebookTabs className="w-12 h-12" aria-hidden="true" />
            <h1 className="text-4xl md:text-5xl">Conteúdos</h1>
          </div>
          <p className="text-xl text-primary-foreground/90 max-w-2xl">
            Explore nossa coleção de recursos sobre acessibilidade digital,
            organizados por categoria.
          </p>
        </div>
      </section>

      <section className="py-12 md:py-16" aria-label="Tipos de conteúdo">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-2xl md:text-3xl mb-8">Navegue por tipo</h2>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-16">
            {contentTypes.map((item) => {
              const Icon = item.icon;
              const isActive = activeFilter === item.type;
              const count = countByType.get(item.type) || 0;

              return (
                <button
                  key={item.type}
                  onClick={() => handleFilterClick(item.type)}
                  className={`bg-card border rounded-lg p-6 hover:border-primary hover:bg-accent transition-all focus:outline-none focus:ring-2 focus:ring-primary text-left ${
                    isActive
                      ? "border-primary bg-accent ring-2 ring-primary"
                      : "border-border"
                  }`}
                  aria-label={`Filtrar por ${item.type} - ${count} itens`}
                  aria-pressed={isActive}
                >
                  <Icon
                    className="w-8 h-8 text-primary mb-3"
                    aria-hidden="true"
                  />
                  <h3 className="text-lg mb-1">{item.type}</h3>
                  <p className="text-muted-foreground">{count} itens</p>
                </button>
              );
            })}
          </div>

          <div>
            <div className="flex flex-col gap-4 mb-8">
              <h2 className="text-2xl md:text-3xl">
                {activeFilter ? `Conteúdos: ${activeFilter}` : "Conteúdos recentes"}
              </h2>

              <div className="flex flex-col lg:flex-row lg:items-end gap-3">
                <div className="relative flex-1 min-w-[260px]">
                  <Search
                    className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground pointer-events-none"
                    aria-hidden="true"
                  />
                  <label htmlFor="search-content" className="sr-only">
                    Buscar conteúdo
                  </label>
                  <input
                    id="search-content"
                    type="search"
                    value={searchDraft}
                    onChange={(event) => {
                      const value = event.target.value;
                      setSearchDraft(value);
                      updateParams({ busca: value.trim() ? value : null, pg: "1" });
                    }}
                    placeholder="Buscar por título, autor ou termo"
                    className="w-full h-11 pl-10 pr-10 bg-card border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                  />
                  {searchDraft ? (
                    <button
                      type="button"
                      onClick={() => {
                        setSearchDraft("");
                        updateParams({ busca: null, pg: "1" });
                      }}
                      className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                      aria-label="Limpar busca"
                    >
                      <X className="w-4 h-4" aria-hidden="true" />
                    </button>
                  ) : null}
                </div>

                <div className="flex flex-wrap items-center gap-3">
                  <div className="flex items-center gap-2">
                    <ArrowUpDown className="w-4 h-4 text-muted-foreground" aria-hidden="true" />
                    <label htmlFor="sort-select" className="text-sm text-muted-foreground whitespace-nowrap">
                      Ordenar por:
                    </label>
                    <select
                      id="sort-select"
                      value={sortBy}
                      onChange={(e) =>
                        updateParams({ ordem: e.target.value as SortOption, pg: "1" })
                      }
                      className="h-11 min-w-[200px] px-3 bg-card border border-border rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                      <option value="date-new">Data (mais recentes)</option>
                      <option value="date-old">Data (mais antigos)</option>
                      <option value="alpha-az">Título (A-Z)</option>
                      <option value="alpha-za">Título (Z-A)</option>
                    </select>
                  </div>

                  <div className="flex items-center gap-2">
                    <Grid3x3 className="w-4 h-4 text-muted-foreground" aria-hidden="true" />
                    <label htmlFor="items-select" className="text-sm text-muted-foreground whitespace-nowrap">
                      Exibir:
                    </label>
                    <select
                      id="items-select"
                      value={itemsToShow}
                      onChange={(e) => updateParams({ itens: e.target.value, pg: "1" })}
                      className="h-11 min-w-[160px] px-3 bg-card border border-border rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary"
                    >
                      <option value={8}>8 itens</option>
                      <option value={12}>12 itens</option>
                      <option value={24}>24 itens</option>
                    </select>
                  </div>
                </div>

                {hasActiveFilters ? (
                  <button
                    type="button"
                    onClick={() => {
                      setSearchDraft("");
                      setSearchParams({});
                    }}
                    className="h-11 px-4 border border-border rounded-lg text-sm hover:bg-accent whitespace-nowrap"
                  >
                    Limpar filtros
                  </button>
                ) : null}
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
                        {new Date(content.date).toLocaleDateString("pt-BR")}
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
                      Por <span className="text-foreground">{content.author}</span>
                    </p>
                  </article>
                ))}
              </div>
            ) : (
              <div className="text-center py-12">
                <p className="text-muted-foreground text-lg">
                  Nenhum conteúdo encontrado para os filtros atuais.
                </p>
              </div>
            )}

            <div className="mt-8 flex items-center justify-between gap-3">
              <button
                type="button"
                disabled={currentPage <= 1}
                onClick={() => updateParams({ pg: String(currentPage - 1) })}
                className="h-10 px-3 border border-border rounded-lg disabled:opacity-40 disabled:cursor-not-allowed hover:bg-accent inline-flex items-center gap-2"
              >
                <ChevronLeft className="w-4 h-4" aria-hidden="true" />
                Anterior
              </button>

              <p className="text-sm text-muted-foreground">
                Página {currentPage} de {totalPages}
              </p>

              <button
                type="button"
                disabled={currentPage >= totalPages}
                onClick={() => updateParams({ pg: String(currentPage + 1) })}
                className="h-10 px-3 border border-border rounded-lg disabled:opacity-40 disabled:cursor-not-allowed hover:bg-accent inline-flex items-center gap-2"
              >
                Próxima
                <ChevronRight className="w-4 h-4" aria-hidden="true" />
              </button>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
